<?php
namespace App\Controller;

use App\Utils\ImportPrices;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ImporterController extends Controller
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

    /**
     * //Page for importing
     * @param Request $request
     * @return Response
     */
    public function importPage(Request $request)
    {
        $mess = ['message' => 'Import File' ];
        $form = $this->createFormBuilder($mess)
        ->add('file',FileType::class,['label' => 'Upload file with petrol prices'])
        ->add('save', SubmitType::class, array('label' => 'Submit file'))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['file']->getData();
            $tempDir = $_SERVER['DOCUMENT_ROOT'].'/../var/data/';
            $name = md5(rand(1, 99999)).$file->getClientOriginalName();
            $file->move($tempDir, $name);
            $f = new File($tempDir.$name);
            $import = new ImportPrices();
            $decodedFile = $import->import($f);
            unlink($_SERVER['DOCUMENT_ROOT'].'/../var/data/'.$name);

            return $this->render('import_page.html.twig', array(
                'form' => $form->createView(),
                'decoded_file' => $decodedFile
            ));
        }

        return $this->render('import_page.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
