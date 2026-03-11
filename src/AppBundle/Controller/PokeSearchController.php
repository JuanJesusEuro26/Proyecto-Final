<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class PokeSearchController extends Controller{

    /**
     * @Route("index/PokeSearch", name="PokeSearch")
     */
    public function pokeSearchAction(){
        return $this->render("proyectfiles/index.html.twig");
    }

    
}

?>