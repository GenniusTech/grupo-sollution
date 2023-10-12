<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WhatsAppController extends Controller
{
    public function enviaFicha($ficha, $telefone) {
        $client = new Client();
        $url = 'https://api.z-api.io/instances/3C4A8E53B4F920B268F98288A1562AF0/token/5F57EA31B175B5CFAE720429/send-document/pdf';

        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'phone'     => '55' . $telefone,
                'caption'   => "Ficha associativa",
                'document'   => $ficha,
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        if (isset($responseData['id'])) {
            return true;
        } else {
            return false;
        }
    }
}
