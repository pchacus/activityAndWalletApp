<?php


namespace App\Controller;


use App\Entity\Transactions;
use App\Entity\User;
use App\Form\AddFundsForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;


class TransactionsController extends AbstractController
{

    public function addFunds(Request $request){

        /**
         * @var User $user
         */
        $userId = $this->getUser()->getId();

        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if(!$user) throw new Exception('No user in database');


        $form = $this->createForm(AddFundsForm::class);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            /**
             * @var Transactions $transaction
             */
            $transaction = new Transactions();

            $transaction = $form ->getData();
            $transaction->setUserId($user->getId());
            $transaction->setType('payment');
            $transaction->setDate(new \DateTime('now'));

            $user->setStatusOfWallet($user->getStatusOfWallet() + $transaction->getAmount());
            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $em->persist($user);
            $em->flush();


            return $this->redirect('/user/account');
        }


        return $this->render('transactions/addFunds.html.twig',[
            "form" => $form->createView()
        ]);
    }

}