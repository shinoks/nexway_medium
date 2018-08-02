<?php
namespace App\Tests\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\ImportPrices ;

class ImportPricesTest extends TestCase
{
    CONST TEMP = '../var/data/';
    CONST DATA = ['Katowice'=>['Pb95' =>4.50,'Pb98' =>4.80,'ON' =>4.40],'Sosnowiec' =>['Pb95' =>4.55,'Pb98' =>4.75,'ON' =>4.45]];

    public function testDecodeJson()
    {
        $dataTest = $this::DATA;
        $import = new ImportPrices();
        $data = file_get_contents('tests/sample/test.json');
        $decoded = $import->jsonDecoder($data);

        $this->assertTrue($dataTest['Katowice'] == $decoded['Katowice']);
        $this->assertTrue($dataTest['Sosnowiec'] == $decoded['Sosnowiec']);
    }

    public function testDecodeXml()
    {
        $dataTest = $this::DATA;
        $import = new ImportPrices();
        $data = file_get_contents('tests/sample/test.xml');
        $decoded = $import->xmlDecoder($data);

        $this->assertTrue($dataTest['Katowice'] == $decoded['Katowice']);
        $this->assertTrue($dataTest['Sosnowiec'] == $decoded['Sosnowiec']);
    }

    public function testDecodeCsv()
    {
        $dataTest = $this::DATA;
        $import = new ImportPrices();
        $data = file_get_contents('tests/sample/test.csv');
        $decoded = $import->csvDecoder($data);

        $this->assertTrue($dataTest['Katowice'] == $decoded['Katowice']);
        $this->assertTrue($dataTest['Sosnowiec'] == $decoded['Sosnowiec']);
    }

    public function testFileParser()
    {
        $dataTest = $this::DATA;
        $import = new ImportPrices();
        $extensions = [
            'json' => 'tests/sample/test.json',
            'xml' => 'tests/sample/test.xml',
            'csv' => 'tests/sample/test.csv'
        ];
        foreach($extensions as $extension => $sample){
            $data = file_get_contents($sample);
            $decoded = $import->fileParser($data,$extension);

            $this->assertTrue($dataTest['Katowice'] == $decoded['Katowice']);
            $this->assertTrue($dataTest['Sosnowiec'] == $decoded['Sosnowiec']);
        }
    }
}
