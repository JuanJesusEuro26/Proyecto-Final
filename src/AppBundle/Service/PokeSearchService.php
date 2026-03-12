<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class PokeSearchService{
    

    public function buscarPokemon($nombrepk){

        $url = "https://pokeapi.co/api/v2/pokemon/" . $nombrepk;

        // Opciones por defecto
        $curlOptions = array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_CONNECTTIMEOUT  => 30,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1
        );

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions); //Implementado array de opciones para nuestro curl

        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch); // Capturamos errores de red
        curl_close($ch);

        if ($respuesta == false) {
            $httpCode = 500;
            $respuesta = "Error de red: " . $curlError;
        }
       

        return $this->tratarRespuesta($respuesta, $httpCode, $nombrepk);
    }

    public function tratarRespuesta($respuesta, $httpCode, $nombrepk){ //Hacemos esta funcion para poder tratar los diferentes codigos de http en el mismo sitio donde tratamos la llamada a la api
        if($httpCode >= 200 && $httpCode <= 300){
            return array(
                'status' => true,
                'data'   => $respuesta,
            );
        }

        if($httpCode == 404){
            return array(
                'status' => false,
                'error'=> "El pokemon ".$nombrepk." no existe en la pokeapi.",
                'code' => $httpCode
            );
        }

        if ($httpCode == 0 || $httpCode >= 500) {
            return array(
                'status' => false,
                'error'=> "Error de conexión con PokeAPI.",
                'code' => $httpCode
            );
        }

        // Otros casos
        return array(
            'status' => false,
            'error'=> "Error inesperado (Código: $httpCode)",
            'code' => $httpCode
        );


    }
}

?>