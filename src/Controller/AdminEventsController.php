<?php


namespace App\Controller;


use App\Entity\Event;
use App\Form\EventEditForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class AdminEventsController extends AbstractController
{

    public function listOfEvents(){

        $events = $this->getDoctrine()->getRepository(Event::class)->listOfEventsQuery();

        if(!$events) throw new Exception("No events in database");

        return $this->render('admin/listOfEvents.html.twig',[
            'events' => $events
        ]);
    }


    public function deleteEvent($eventId){

        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);

        if(!$event) throw  new Exception('No event in database');

        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        $this->addFlash('succes', 'Event został usunięty z systemu!');

        return $this->redirect('/admin/events');
    }


}