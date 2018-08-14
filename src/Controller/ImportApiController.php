<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\ImportPrices;
use App\Entity\FuelPrices;

class ImportApiController extends AbstractController
{
    public function decodeFile(ImportPrices $import)
    {
        $files = $_FILES;
        if(!isset($files)){

            return new Response('Error - file not send',Response::HTTP_BAD_REQUEST,['content-type' => 'text/html']);
        }
        $encoded = json_encode($import->import($files));

        return new Response($encoded,Response::HTTP_OK,['content-type' => 'application/json']);
    }

    public function getPrices(ImportPrices $import): Response
    {
        $fuelPrices = $this->getDoctrine()
            ->getRepository(FuelPrices::class)
            ->findAll();
        $prices = [];
        foreach($fuelPrices as $rows){
            $f []= ['city' => $rows->getCity(), 'petrol' => $rows->getFuelName(), 'price' => $rows->getPrice()];
            $prices = $import->dbDecoder($f);
        }
        $encoded = json_encode($prices);

        return new Response($encoded,Response::HTTP_OK,['content-type' => 'application/json']);
    }

    public function getPricesByCity(string $city, ImportPrices $import): Response
    {
        $fuelPrices = $this->getDoctrine()
            ->getRepository(FuelPrices::class)
            ->findBy(['city' => $city]);
        $prices = [];
        foreach($fuelPrices as $rows){
            $f []= ['city' => $rows->getCity(), 'petrol' => $rows->getFuelName(), 'price' => $rows->getPrice()];
            $prices = $import->dbDecoder($f);
        }
        $encoded = json_encode($prices);

        return new Response($encoded,Response::HTTP_OK,['content-type' => 'application/json']);
    }

    public function getPricesByFuel(string $fuel, ImportPrices $import): Response
    {
        $fuelPrices = $this->getDoctrine()
            ->getRepository(FuelPrices::class)
            ->findBy(['fuelName' => $fuel]);
        $prices = [];
        foreach($fuelPrices as $rows){
            $f []= ['city' => $rows->getCity(), 'petrol' => $rows->getFuelName(), 'price' => $rows->getPrice()];
            $prices = $import->dbDecoder($f);
        }
        $encoded = json_encode($prices);

        return new Response($encoded,Response::HTTP_OK,['content-type' => 'application/json']);
    }
}
