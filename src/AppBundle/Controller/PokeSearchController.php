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
    public function pokeSearchAction(Request $request){
        $nombreInput = strtolower(trim($request->request->get('pokemon')));
    
        if(empty($nombreInput)){
            return new JsonResponse(array("error" => "ERROR: introduce el nombre de un pokemon."), 400);
        }

        $url = "https://pokeapi.co/api/v2/pokemon/" . $nombreInput;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // No imprime, guarda en variable
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Sigue redirecciones
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas de certificados SSL localmente

        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) { //Si el contenido se ha cargado correctamente (status 200)
            $datos = json_decode($respuesta, true);
            
            $resultado= array(
                'nombre'=> ucfirst($datos['name']),
                'id'=> $datos['id'],
                'tipo' => ucfirst($datos['types'][0]['type']['name']),
                'foto' => $datos['sprites']['front_default']
            );

            return new JsonResponse($resultado);
        } else if($httpCode === 404){
            return new JsonResponse(array("error"=>"ERROR: pokemon ".$nombreInput." no encontrado"), 404);
        }
            
    
        
        
    }

    
}

?>