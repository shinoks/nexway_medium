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
        $fileJson = new File($_SERVER['DOCUMENT_ROOT'].'/../tests/sample/test.json');
        $fileJsonZip = new File($_SERVER['DOCUMENT_ROOT'].'/../tests/sample/testJson.zip');
        $fileAllZip = new File($_SERVER['DOCUMENT_ROOT'].'/../tests/sample/testall.zip');
        $fileAllRar = new File($_SERVER['DOCUMENT_ROOT'].'/../tests/sample/testrar.rar');
        $fileXml = new File($_SERVER['DOCUMENT_ROOT'].'/../tests/sample/test.xml');
        $fileCsv = new File($_SERVER['DOCUMENT_ROOT'].'/../tests/sample/test.csv');

        return new Response('<html>'.var_dump($import->import($fileJson)).'<br/>'.var_dump($import->import($fileJsonZip)).'<br/>'.var_dump($import->import($fileAllZip)).'<br/>'.var_dump($import->import($fileAllRar)).'<br/>'.var_dump($import->import($fileXml)).'<br/>'.var_dump($import->import($fileCsv)));
    }
}
