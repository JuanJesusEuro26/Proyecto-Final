<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class IndexController extends Controller{

    /**
     * @Route("index", name="index")
     */
    public function goIndexAction(){
        return $this->render("proyectfiles/index.html.twig");
    }

    /**
     * @Route("index/sendEmail", name="sendEmail")
     */
    public function sendEmailAction(){
        return $this->render("proyectfiles/index.html.twig");
    }
}

?>