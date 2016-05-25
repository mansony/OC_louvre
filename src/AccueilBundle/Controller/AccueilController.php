<?php

namespace AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function indexAction()
    {
        return $this->render('AccueilBundle:Accueil:accueil.html.twig');
    }
}
