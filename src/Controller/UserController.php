<?php


namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Transactions;
use App\Entity\User;
use App\Form\EditUserAccountForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    public function showUserAccount($username){

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);

        if(!$user) throw new Exception('NO USER WITH GOT USERNAME');


        return $this->render('user/showUserAccount.html.twig',[
            "user" => $user,
            "grades" => $this->avgUserRating()
        ]);

    }


    public function showAccount(){

        $id = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if(!$user) throw new Exception('NO USER WITH GOT ID');


        return $this->render('user/userAccount.html.twig',[
            "user" => $user,
            "grades" => $this->avgUserRating(),
            "transactions" => $this->userTransaction()
        ]);

    }


    public function userTransaction(){

        /**
         * @var User $user
         */
        $user = $this->getUser();

        return $this->getDoctrine()->getRepository(Transactions::class)->selectLastTransactions($user->getId());
    }

    public function avgUserRating(){

        /**
         * @var User $user
         */
        $user = $this->getUser();

        /**
         * @var array| Rating $grades
         */
        $grades = $this->getDoctrine()->getRepository(Rating::class)->findBy(['ratedUserId' => $user->getId()]);

        $gameSum = 0;
        $trustSum = 0;
        $cultureSum = 0;

        for($i = 0; $i < sizeof($grades); $i++){

            $cultureSum += $grades[$i]->getCultureAndAttitudeGrade();
            $trustSum += $grades[$i]->getUserTrustGrade();
            $gameSum += $grades[$i]->getGameLevelGrade();
        }

        $avg['gameLevelGrade'] = $gameSum / sizeof($grades);
        $avg['cultureGrade'] = $cultureSum / sizeof($grades);
        $avg['trustGrade'] = $trustSum / sizeof($grades);

        return $avg;
    }

    public function editAccount(Request $request){

        $id = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if(!$user) throw new Exception("No user with id");

        $form = $this->createForm(EditUserAccountForm::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect('/user/account');
        }

        return $this->render('user/editAccount.html.twig', [
            'form' => $form->createView()
        ]);
    }


}