<?php
namespace App\Utils;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class ImportPrices
{
    public function __construct()
    {
    }

    public function decoder(File $file)
    {
        $extension = $file->getExtension();
        $filePath = $file->getRealPath();
        $content = file_get_contents($filePath);
        $parsedData = $this->fileParser($content, $extension);

        return $parsedData;
    }

    private function fileParser($data, $extension)
    {
        switch($extension){
            case 'json':
                $parsed = $this->jsonDecoder($data);
            break;
            case 'csv':
                $parsed = $this->csvDecoder($data);
            break;
            case 'xml':
                $parsed = $this->xmlDecoder($data);
            break;
            default:
                return false;
        }

        return $parsed;
    }

    private function jsonDecoder($data)
    {
        $encoder = new JsonEncoder();
        $encoded = $encoder->decode($data,[]);

        return $encoded;
    }

    private function jsonEncoder($data)
    {
        $encoder = new JsonEncoder();
        $encoded = $encoder->encode($data,'json');

        return $encoded;
    }

    private function csvDecoder($data)
    {
        $encoder = new CsvEncoder();
        $encoded = $encoder->decode($data,[]);
        $parse = [];

        $city = null;
        foreach($encoded as $row){
            $fuels = [];
            foreach($row as $k=>$r){
                if($k == 'city' || $k == ''){
                    $city = $r;
                }else{
                    $fuels [$k]= $r;
                }
                $parse [$city]= $fuels;
            }
        }

        return $parse;
    }

    private function csvEncoder($data)
    {
        $encoder = new CsvEncoder();
        $encoded = $encoder->encode($data,'csv');

        return $encoded;
    }

    private function xmlDecoder($data)
    {
        $encoder = new XmlEncoder();
        $encoded = $encoder->decode($data,[]);

        return $encoded;
    }
}
