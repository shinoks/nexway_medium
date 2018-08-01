<?php
namespace App\Utils;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class ImportPrices
{
    public function __construct()
    {
    }

    public function import(File $file)
    {
        $extension = $file->getExtension();
        $filePath = $file->getRealPath();
        $fileName = $file->getBasename('.'.$extension);
        $filesystem = new Filesystem();

        switch($extension){
            case 'zip':
                if($this->unzip($filePath, '../var/data/'.$fileName)){
                    $parsedData = $this->getFileFromDir($file, $fileName);
                    $filesystem->remove('../var/data/'.$fileName);
                };
            break;
            case 'rar':
                if($this->unrar($filePath, '../var/data/'.$fileName)){
                    $parsedData = $this->getFileFromDir($file, $fileName);
                    $filesystem->remove('../var/data/'.$fileName);
                };
            break;
            default:
                $content = file_get_contents($filePath);
                $parsedData []= $this->fileParser($content, $extension);
        }

        return $parsedData;
    }

    private function unzip($location, $name)
    {
        if(exec("unzip $location -d $name",$arr)){

            return true;
        }else {

            return false;
        }
    }

    private function unrar($location, $name)
    {
        $filesystem = new Filesystem();
        if(!$filesystem->exists($name)){
            mkdir($name);
        }

        if(exec("unrar x $location $name",$arr)){

            return true;
        }else {

            return false;
        }
    }

    private function getFileFromDir(File $file, $fileName)
    {
        $parsedData = [];
        $finder = new Finder();
        $finder->files()->in($file->getPath().'/'.$fileName);
        foreach ($finder as $file) {
            $content = file_get_contents($file->getRealPath());
            $parsedData []= $this->fileParser($content, $file->getExtension());
        }

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

    private function xmlDecoder($data)
    {
        $encoder = new XmlEncoder();
        $encoded = $encoder->decode($data,[]);

        return $encoded;
    }
}
