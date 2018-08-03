<?php
namespace App\Controller;

use App\Utils\ImportPrices;
use App\Utils\DbConnection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ImporterController extends Controller
{
    /**
     * Page for importing
     * @param Request $request
     * @return Response
     */
    public function importPage(Request $request, DbConnection $dbConnection)
    {
        $form = $this->createFormBuilder([])
        ->add('type',ChoiceType::class,[
            'label' => 'Choose import type',
            'choices' => [
                'From db' => 'db',
                'From file' => 'file'
            ]
        ])
        ->add('dbname',TextType::class,['label' => 'Database name', 'required' => false])
        ->add('dbuser',TextType::class,['label' => 'Database user', 'required' => false])
        ->add('dbpassword',TextType::class,['label' => 'Database password', 'required' => false])
        ->add('dbhost',TextType::class,['label' => 'Database host', 'required' => false])
        ->add('dbtype',TextType::class,['label' => 'Database type', 'required' => false])
        ->add('dbtable',TextType::class,['label' => 'Database table', 'required' => false])
        ->add('dbcitycolumn',TextType::class,['label' => 'Database column with city name', 'required' => false])
        ->add('dbpetrolcolumn',TextType::class,['label' => 'Database column with petrol name', 'required' => false])
        ->add('dbpricecolumn',TextType::class,['label' => 'Database column with petrol price', 'required' => false])
        ->add('file',FileType::class,['label' => 'Upload file with petrol prices', 'required' => false])
        ->add('save', SubmitType::class, array('label' => 'Submit file'))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $import = new ImportPrices();
            if($form['type']->getData() == 'db'){
                $conn = $dbConnection->getConnection($form['dbname']->getData(),$form['dbuser']->getData(),$form['dbpassword']->getData(),
                    $form['dbhost']->getData(),$form['dbtype']->getData());
                $qb = $conn->createQueryBuilder();
                $r = $qb
                    ->select($form[
                        'dbcitycolumn']->getData().' as `city`',
                        $form['dbpetrolcolumn']->getData().' as `petrol`',
                        $form['dbpricecolumn']->getData().' as `price`'
                    )
                    ->from($form['dbtable']->getData())
                    ->execute();
                $result = $r->fetchAll();
                $decodedFile = $import->dbDecoder($result);
            }elseif($form['type']->getData() == 'file'){
                $file = $form['file']->getData();
                $tempDir = $_SERVER['DOCUMENT_ROOT'].'/../var/data/';
                $name = md5(rand(1, 99999)).$file->getClientOriginalName();
                $file->move($tempDir, $name);
                $f = new File($tempDir.$name);
                $decodedFile = $import->import($f);
                unlink($tempDir.$name);
            }

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
