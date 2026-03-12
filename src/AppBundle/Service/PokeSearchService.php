<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class PokeSearchService{
    

    public function buscarPokemon($nombrepk){
        // Opciones por defecto
        $curlOptions = array(
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_CONNECTTIMEOUT  => 30,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1
        );

        $url = "https://pokeapi.co/api/v2/pokemon/" . $nombrepk;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // No imprime, guarda en variable
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Sigue redirecciones
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas de certificados SSL localmente

        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if(curl_errno($ch)){
            $httpCode=500;
        }
        curl_close($ch);

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