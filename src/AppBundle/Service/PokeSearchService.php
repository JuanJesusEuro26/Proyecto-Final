<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class PokeSearchService{
    

    public function buscarPokemon($nombrepk){
        $url = "https://pokeapi.co/api/v2/pokemon/" . $nombrepk;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // No imprime, guarda en variable
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Sigue redirecciones
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas de certificados SSL localmente

        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $resultado=array("respuesta"=>$respuesta,"httpCode"=>$httpCode);

        return $resultado;
    }
}

?>