<?php
namespace App\Utils;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class ImportPrices
{
    CONST TEMP = '../var/data/';

    /**
     * @param File $file
     * @return array
     */
    public function import(File $file): array
    {
        $parsedData = [];
        $extension = $file->getExtension();
        $filePath = $file->getRealPath();
        $fileName = $file->getBasename('.'.$extension);
        $filesystem = new Filesystem();

        switch($extension){
            case 'zip':
                if($this->unzip($filePath, $this::TEMP.$fileName)){
                    $parsedData = $this->getParseFilesFromDir($fileName);
                    $filesystem->remove($this::TEMP.$fileName);
                };
            break;
            case 'rar':
                if($this->unrar($filePath, $this::TEMP.$fileName)){
                    $parsedData = $this->getParseFilesFromDir($fileName);
                    $filesystem->remove($this::TEMP.$fileName);
                };
            break;
            default:
                $content = file_get_contents($filePath);
                $parsedData []= $this->fileParser($content, $extension);
        }

        return $parsedData;
    }

    /**
     * @param $location
     * @param $name
     * @return bool
     */
    private function unzip($location, $name): bool
    {
        if(exec("unzip $location -d $name",$arr)){

            return true;
        }else {

            return false;
        }
    }

    /**
     * @param $location
     * @param $name
     * @return bool
     */
    private function unrar($location, $name): bool
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

    /**
     * @param $fileName
     * @return array
     */
    private function getParseFilesFromDir($fileName): array
    {
        $parsedData = [];
        $finder = new Finder();
        $finder->files()->in($this::TEMP.'/'.$fileName);
        foreach ($finder as $file) {
            $content = file_get_contents($file->getRealPath());
            $parsedData []= $this->fileParser($content, $file->getExtension());
        }

        return $parsedData;
    }

    /**
     * @param $data
     * @param $extension
     * @return array
     */
    public function fileParser($data, $extension): array
    {
        $parsed = [];
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
        }

        return $parsed;
    }

    /**
     * @param $data
     * @return array
     */
    public function jsonDecoder($data): array
    {
        $encoder = new JsonEncoder();
        $encoded = $encoder->decode($data,[]);

        return $encoded;
    }

    /**
     * @param $data
     * @return array
     */
    public function csvDecoder($data): array
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
                    $fuels [$k]= number_format($r,2);
                }
                $parse [$city]= $fuels;
            }
        }

        return $parse;
    }

    /**
     * @param $data
     * @return array
     */
    public function xmlDecoder($data): array
    {
        $encoder = new XmlEncoder();
        $encoded = $encoder->decode($data,[]);

        return $encoded;
    }

    public function htmlDecoder($data): array
    {
        $crawler = new Crawler($data);
        $a =$crawler->filter("#petrol-prices > tr");
        $parsed = $a->each(
            function (Crawler $a){
                $t = $a->filter('td');
                $pp = $t->each(
                    function (Crawler $t){
                        $p = $t->text();

                        return $p;
                    }
                );

                return $pp;
            }
        );
        $encoded = [];
        $fuels = [];
        $i = 0;
        foreach($parsed as $row){
            if($i == 0){
                $fuels = $row;
            }else {
                $e = [];
                $c = 0;
                foreach($row as $r){
                    if($c != 0){
                        $e [$fuels[$c]]= $r;
                    }
                    $c = $c+1;
                }
                $encoded [$row[0]]= $e;
            }
            $i = $i+1;
        }

        return $encoded;
    }
}
