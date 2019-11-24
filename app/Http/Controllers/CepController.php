<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function consultaCep(Request $request)
    {   
        $webservice = "http://webservices.salvador.ba.gov.br/retornaEnderecoViaCEP/index.php";
        $arr = array(
        'cep' => $request->cep
        );

        $params = http_build_query($arr);  
        $api_url =  $webservice . '?' . $params;
        $res = $this->getRequest($api_url);

        $data = json_decode($res, true);

        $obj = json_decode($data["endereco"]);

        $data = array();
        $data['logradouro'] = $obj[0]->Nome_Log;
        $data['bairro'] = $obj[0]->Bairro_Log;

        return json_encode($data);
    }

    public function getRequest($url) {
        if( ! isset( $url ) ) return false;
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $data = curl_exec( $ch );
        curl_close( $ch );
        
        return $data;
    }

}
