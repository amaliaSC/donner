<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(): Response
    {
        
        return $this->redirectToRoute('ad_list_last');
    }

     /**
     * @Route("/mentions_legales", name="mentions")
     */
    public function mentions(): Response
    {
        
        return $this->render('mentions.html.twig');
    }
}
