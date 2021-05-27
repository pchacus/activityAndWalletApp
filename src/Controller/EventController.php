<?php


namespace App\Controller;

use App\Entity\Event;
use App\Entity\File;
use App\Entity\FileClosed;
use App\Entity\FileOpened;
use App\Entity\Transactions;
use App\Entity\User;
use App\Form\CreateEventForm;
use App\Form\EventEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends AbstractController
{
    public function createEvent(Request $request){

        $event = new Event();
        $form = $this->createForm(CreateEventForm::class, $event);
        $form->handleRequest($request);

        /**
         * @var User $user
         */
        $user = $this->getUser();

        if($form->isSubmitted() && $form->isValid()){

            $event = $form->getData();
            $event->setCreatorId($user->getId());

            if( $form['usersListId']->getData()){
                $event->setUsersListId($user->getId());
            }

            /**
             * @var UploadedFile $file
             */
            $file = $form['eventPhotoName']->getData();

            if($file){

                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                    $fileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file->guessExtension();

                try{
                    $file ->move(
                        $this->getParameter('events_photos_directory'),
                        $newFileName
                    );

                }catch(FileException $e) {

                    echo "Błąd zapisu pliku!!";
                }

                $event->setEventPhotoName($newFileName);
            }

            $event->setCurrentlyModifying(0);
            $em = $this-> getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();


            return $this->redirect('/');
        }

        return $this->render('event/createEvent.html.twig',[
            'form' =>$form->createView()
        ]);
    }

    public function showActiveEvents(){

        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        if(!$events) throw new Exception('No events in database!');

        $activeEvents = [];
        $userListJoined = [];

        for($i = 0; $i < sizeof($events); $i++){

            if( $events[$i]->getDate() >  (new\DateTime('now')) ){

                $activeEvents[] = $events[$i];
                $userListJoined[] =[$events[$i]->getId()  => explode(',',$events[$i]->getUsersListId())];

            }
        }

        return $this->render('event/activeEvents.html.twig',[
            'events' => $activeEvents,
            'joined' => $userListJoined
        ]);
    }


    public function joinToEvent($eventId){

        /**
         * @var Event $event
         */
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

        if(!$event) throw  new Exception('No event in database!');

        /**
         * @var User $user
         */
        $user = $this->getUser();

        if( $user->getStatusOfWallet() < $event->getPrice() ){

           $this->addFlash('failed', 'Brakuje środków na koncie, aby dołączyć do wydarzenia!');
           return $this->redirect('/');
        }


        if(!strlen($event->getUsersListId()))
            $event->setUsersListId($user->getId());
        else
            $event->setUsersListId($event->getUsersListId(). ',' . $user->getId());

        $event->setNumberOfSeats($event->getNumberOfSeats() -1 );
        $user->setStatusOfWallet($user->getStatusOfWallet() - $event->getPrice());

        $transaction = new Transactions();
        $transaction->setUserId($user->getId());
        $transaction->setType('payoff');
        $transaction->setAmount($event->getPrice());
        $transaction->setDate(new \DateTime('now'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($transaction);
        $em->persist($event);
        $em->flush();

        return $this->redirect('/');
    }

    public function createdEventsByUser(){

        /**
         * @var User $user
         */
        $user = $this->getUser();
        $userId = $user->getId();
        $events = $this->getDoctrine()->getRepository(Event::class)->eventsCreatedByUser($userId);

        return $this->render('user/userEvents.html.twig',[
            'eventsCreated' => $events
        ]);

    }

    public function pageOfEvent($eventId){

        /**
         * @var Event $event
         */
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

        if(!$event) throw new Exception('No event in database');


        $usersList = explode(',', $event->getUsersListId());
        $users = [];

        for($i = 0; $i < sizeof($usersList); $i++){

            $users[$i] = $this->getDoctrine()->getRepository(User::class)->find($usersList[$i]);
        }


        return $this->render('event/pageOfEvent.html.twig',[
            'event' => $event,
            'users' => $users
        ]);
    }

    public function joinedEvents(){

        $userId = $this->getUser()->getId();

        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        if(!$events) throw new Exception('No events in database!');


        $userEvents = [];
        /**
         * @var Event $event
         *
         */
        for( $i = 0; $i < sizeof($events); $i++ ) {

            $userList  = explode(',',$events[$i]->getUsersListId());

            for($j = 0; $j < sizeof($userList) ; $j++)

            if($userId == $userList[$j]){
                $userEvents[] = $events[$i];
            }

        }

        return $this->render('user/joinedEvents.html.twig',[
            "userEvents" => $userEvents

        ]);
    }

    //typowanie argumentów funkcji
    /**
     * @param $eventId
     * @param Request $request
     * @return RedirectResponse
     */
    public function editEventCheck($eventId, Request $request){



        /**
         * @var Event $event
         */
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId); //pobranie konkretnego eventu bazy

        if(!$event) throw new Exception('No event in database'); //sprawdzenie czy istnieje  event w bazie

        if($event->getCurrentlyModifying() == 0) {  //sprawdzenie czy obecnie nie jest edytowany

            $event->setCurrentlyModifying(1);   //zmiana stanu na jego edycje
            $em = $this->getDoctrine()->getManager();
            $em ->persist($event); // przygotowanie zapytania dla bazy
            $em->flush();  // zapisane stanu w bazie
            return $this->redirectToRoute('editEvent',['eventId' => $eventId]);  // przekierowanie do edycji eventu

        }

        // Jeżeli event jest obecnie aktualizowany zwraca komunikat
        $this->addFlash("fail","Event aktualnie edytowany przez innego Administratora!");
        return $this->redirect('/admin/events');
    }


    public function editEvent($eventId, Request $request){

        //pobranie eventu do edycji
        /**
         * @var Event $event
         */
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

        if(!$event) throw new Exception('No event in database'); //sprawdzenie czy istnieje event w bazie


        $form = $this->createForm(EventEditForm::class, $event); //utworzenie formularza oraz przekazanie akutalnych danych z bazy
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ // Jezeli formularz przechodzi walidacje i jest wysłany

            $event = $form->getData(); // przypisanie nowych danych do eventu
            $event->setCurrentlyModifying(0); // zmiana stanu na zakonczenie edycji
            $em = $this->getDoctrine()->getManager();
            $em->persist($event); //przygotowanie zapytania dla bazy
            $em->flush(); // wysłanie oraz zapisanie nowych danych

            $this->addFlash('succes', 'Post został pomyślnie zapisany!'); // potwierdzenie operacji komunikatem

            return $this->redirect('/admin/events'); // przekierowanie na strone z listą eventów
        }

        return $this->render('admin/editEvent.html.twig',[      // utworznie formularza na stronie
            'form' => $form->createView()
        ]);
    }





    public function eventReport($eventId){

        /**
         * @var Event $event
         */
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

        if(!$event)
            throw new Exception('No event in database');

        $fileName = $event->getName().".txt";

        $file = new File();
        $file->changeState(new FileOpened());
//        $file->open();
        $file->write($fileName, $event);
       // $file->close();
        return $this->redirect('/');
    }
}