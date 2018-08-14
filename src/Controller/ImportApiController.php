<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Utils\ImportPrices;

class ImportApiController
{
    public function decodeFile()
    {
        $files = $_FILES;
        if(!isset($files)){

            return new Response('Error - file not send',Response::HTTP_BAD_REQUEST,['content-type' => 'text/html']);
        }
        $import = new ImportPrices();
        $encoded = json_encode($import->import($files));

        return new Response($encoded,Response::HTTP_OK,['content-type' => 'application/json']);
    }

    public function getPrices()
    {
        #TODO prices from entity
        $prices = [];
        $encoded = json_encode($prices);

        return new Response($encoded,Response::HTTP_OK,['content-type' => 'application/json']);
    }
}
