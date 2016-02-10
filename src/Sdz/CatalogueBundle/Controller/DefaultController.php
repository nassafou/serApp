<?php

namespace Sdz\CatalogueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CatalogueBundle:Default:index.html.twig', array('name' => $name));
    }
}
