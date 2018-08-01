<?php
namespace App\Controller;

use App\Utils\ImportPrices;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

class ImporterController
{
    public function index()
    {
        $import = new ImportPrices();
        $fileJson = new File($_SERVER['DOCUMENT_ROOT'].'/../var/data/test.json');
        $fileXml = new File($_SERVER['DOCUMENT_ROOT'].'/../var/data/test.xml');
        $fileCsv = new File($_SERVER['DOCUMENT_ROOT'].'/../var/data/test.csv');
        return new Response('<html>'.var_dump($import->decoder($fileJson)).'<br/>'.var_dump($import->decoder($fileXml)).'<br/>'.var_dump($import->decoder($fileCsv)).'</html>');
    }
}
