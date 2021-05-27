<?php


namespace App\Controller;

use App\Entity\Rating;
use App\Controller\EventController;
use App\Form\RatingForm;
use App\Entity\Event;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class RatingController extends AbstractController
{

    public function listOfUsersOnEvent($eventId){

        /**
         * @var Event $event
         */
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

        /**
         * @var Rating[] $rating
         */
        $rating = $this->getDoctrine()->getRepository(Rating::class)->findByEventId($eventId, $this->getUser()->getId());

        //var_dump($rating);
        if(!$event) throw new Exception('No event in database');


        $usersList = explode(',',$event->getUsersListId());
        $users = [];
        
        if(!$rating)
            for($i = 0; $i < sizeof($usersList) ; $i++) {
                $users[$i] = $this->getDoctrine()->getRepository(User::class)->find($usersList[$i]);
            }

        return $this->render('event/listOfUsersOnEvent.html.twig',[
            "users" => $users,
            'eventId' => $eventId
        ]);
    }

    public function ratedUsers(Request $request, $eventId){

        /**
         * @var Event $event
         */
        $event = $this->getDoctrine()->getRepository(Event::class) ->find($eventId);

        /**
         * @var User $giver
         */
        $giver = $this->getUser();
        $usersList = explode(',',$event->getUsersListId());

        $em = $this->getDoctrine()->getManager();

        /**
         * @var Rating $rating
         */
        for($i = 0; $i < sizeof($usersList); $i++){

            if($request->get("game".$usersList[$i])){

                $rating = new Rating();

                $rating->setGiverId($giver->getId());
                $rating->setRatedUserId($usersList[$i]);
                $rating->setEventId($eventId);

                $rating->setGameLevelGrade(intval($request->get("game" . $usersList[$i])));
                $rating->setUserTrustGrade(intval($request->get("trust" . $usersList[$i])));
                $rating->setCultureAndAttitudeGrade(intval($request->get("culture" . $usersList[$i])));

                $em->persist($rating);
                $em->flush();
            }
        }
        return $this->redirect('/');
    }
}