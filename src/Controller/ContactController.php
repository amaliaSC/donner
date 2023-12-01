<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer): Response
    {
        $contact = new Contact;

        $formContact = $this->CreateForm(ContactType::class, $contact);
        $formContact->handleRequest($request);

        if($formContact->isSubmitted() && $formContact->isValid())
        {
            $message = (new \Swift_Message('Formulaire de contact'))
                ->setFrom($contact->getEmail())
                ->setTo('contact@test.com')
                ->setBody(
                    $this->renderView(
                        // templates/emails/registration.html.twig
                        'emails/test.html.twig',
                        ['contact' => $contact]
                    ),
                    'text/html'
                ); 
                   
        $mailer->send($message);  

        $this->addFlash('message_send_success', 'Message envoyé avec succes');   
        return $this->redirectToRoute('default');
        }

        return $this->render('contact/index.html.twig', [
            'formContact' => $formContact->createView()
        ]);
    }
}
