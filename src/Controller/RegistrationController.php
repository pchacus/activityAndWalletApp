<?php

namespace App\Controller;

use App\Entity\ProxyMailer;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        /**
         * @var User $user
         */
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form ->getData();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles($user->getRoles());
            $user->setStatusOfWallet(0);

            /**
             * @var UploadedFile $file
             */
            $file = $form['profilePhotoName']->getData();

            if($file){

                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                    $fileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file->guessExtension();

                try{
                    $file ->move(
                        $this->getParameter('profile_photos_directory'),
                        $newFileName
                    );

                }catch(FileException $e) {

                    echo "Błąd zapisu pliku!!";
                }

                $user->setProfilePhotoName($newFileName);
            }

            $mail = new ProxyMailer();

            $title  = "Rejestracja użytkownika w serwisie GC-Activity!";
            $body = "Twoje konto zostało założone. Witamy ".$user->getUsername()." !";

            $mail->createMessage($title,$body,$user->getEmail());


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('index');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
