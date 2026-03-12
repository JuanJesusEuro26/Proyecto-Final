<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;


class PokeSearchController extends Controller{

    /**
     * @Route("index/PokeSearch/{slug}", name="PokeSearch")
     */
    public function pokeSearchAction($slug){ //Cambiamos el paso del nombre mediante request para pasarle unicamente lo que nos hace falta
        $nombreInput = strtolower($slug);

        //Uso de validators:
        $validator = $this->get('validator');
        //Definimos las reglas para el validator
        $reglas=array(
            new Assert\NotBlank(array('message'=>'El nombre del pokemon es obligatorio')),
            new Assert\Regex(array('pattern'=>'/^[a-zA-Z\-]+$/', 'message' => 'El nombre solo puede contener letras y guiones. La ñ no está incluida en las letras validas')),
            new Assert\Length(array('max'=>25,'maxMessage'=>'Nombre demasiago largo'))            
        );

        //Validamos el dato
        $listaErrores= $validator->validate($nombreInput, $reglas);
        
        if (count($listaErrores) > 0) {
            // Si hay errores, devolvemos el primero en un JSON 400 (Bad Request)
            $error = $listaErrores[0]; 
            return new JsonResponse(array('error' => $error->getMessage()), 400);
        }
    
        //La llamada a la api la haremos en un servicio aparte
        $pokesearchservice=$this->container->get('search_pokemon');

        $resultado=$pokesearchservice->buscarPokemon($nombreInput); //Ejecutamos la funcion del servicio
        $status=$resultado['status'];

        if ($status == true) { //Si el contenido se ha cargado correctamente (status 200)
            $datos = json_decode($resultado['data'], true);
            
            $resultado= array(
                'nombre'=> ucfirst($datos['name']),
                'id'=> $datos['id'],
                'tipo' => ucfirst($datos['types'][0]['type']['name']),
                'foto' => $datos['sprites']['front_default']
            );

            return new JsonResponse($resultado);
        } else {
            return new JsonResponse(array('error' => $resultado['error']), 
                $resultado['code']
            ); //Para que el index detecte el error tenemos que pasarle un array con el error y el codigo de error. 
        }   
    }
    
}

?>