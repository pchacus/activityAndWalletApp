<?php


namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\AdminEditUserAccountForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;


class AdminUsersController extends AbstractController
{

    public function  listOfUsers(){

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        if(!$users) throw new Exception('No users in database!');

        return $this->render('admin/listOfUsers.html.twig',[
           'users' => $users
        ]);
    }

    public function deleteUserAccount($userId){

        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        if(!$user) throw  new Exception('No user in database');

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('succes', 'Użytkownik został usunięty z systemu!');

        return $this->redirect('/admin/users');
    }


    public function editUserAccount($userId, Request $request){

        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if(!$user) throw new Exception('No user in database');

        $form = $this->createForm(AdminEditUserAccountForm::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('succes',"Konto zostalo edytowane pomyślnie!");

            return $this->redirect('/admin/users');
        }

        return $this->render('admin/editUserAccount.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function promoUserToAdmin($userId){

        /**
         * @var User $user
         */
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if(!$user) throw new Exception('No user in database');

        $user->setRoles(["ROLE_ADMIN"]);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash('succes', "Konto zostalo pomyślnie wypromowane do Administratora!");

        return $this->redirect('/admin/users');
    }
}