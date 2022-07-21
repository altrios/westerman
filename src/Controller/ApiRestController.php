<?php

namespace App\Controller;

use App\Entity\ApyRest;
use App\Form\ApyRestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ApiRestController extends AbstractController
{
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api/rest', name: 'app_api_rest')]
    public function index(Request $request): Response
    {
        $url = '';
        $ftp = new ApyRest();
        $form = $this->createForm(ApyRestType::class);
        $form->handleRequest($request);
        $value = 'No se ubica el JSON';
        $response = '';
        $diferences = [];
        $diffCount = 0;
        if ($form->isSubmitted() && $form->isValid()) {
            $file = json_decode(file_get_contents($form['edpoint']->getData()));
            $oldfile = json_decode(file_get_contents('file.json'));
            // var_dump(json_decode($file));die;
            if (file_exists('file.json')) {
                if ($oldfile->Result != $file->Result) { 
                    $diferences[0] = "hay diferencia en el indice Result: Antes $oldfile->Result, Ahora $file->Result";
                    $diffCount++;
                }
                foreach ($file->Data as $key => $jsonData) {
                    foreach ($jsonData as $index => $datos) {
                        if ($oldfile->Data[$key]->$index != $datos) {

                            if (is_array($datos)) {
                                foreach ($datos as $jindex => $AData) {
                                    if (
                                        $oldfile->Data[$key]->$index[$jindex] !=
                                        $AData
                                    ) {
                                        if (is_object($AData)) {
                                            foreach (
                                                (array) $AData
                                                as $subJ => $subAData
                                            ) {
                                                if (
                                                    $subAData !=
                                                    $oldfile->Data[$key]
                                                        ->$index[$jindex]->$subJ
                                                ) {
                                                    $diferences[$diffCount] =
                                                        "se encontro diferencia en la siguiente ruta: $key -> $index 
                                                -> $jindex -> $subJ, antes " .
                                                        $oldfile->Data[$key]
                                                            ->$index[$jindex]
                                                            ->$subJ .
                                                        " ahora $subAData";
                                                    $diffCount++;
                                                }
                                            }
                                        } else {
                                            // var_dump($diffCount);die;
                                            $diferences[$diffCount] =
                                                "se encontro diferencia en la siguiente ruta: 
                                            $key -> 
                                            $index 
                                        -> $jindex, antes " .
                                                $oldfile->Data[$key]->$index[
                                                    $jindex
                                                ] .
                                                " ahora $AData";
                                            $diffCount++;
                                        }
                                    }
                                    //   die;
                                }
                            }else{
                                $diferences[$diffCount] =
                                                "se encontro diferencia en la siguiente ruta: 
                                            $key -> 
                                            $index, antes " .
                                                $oldfile->Data[$key]->$index.
                                                " ahora $datos";
                                            $diffCount++;
                            }

                            // $diffCount++;
                        }
                       
                    }
                   
                }  
                
                // var_dump(file_get_contents($form['edpoint']->getData()));die;
                $newFile = fopen('file.json', 'w');
                fwrite($newFile, file_get_contents($form['edpoint']->getData()));
            } else {
                $newFile = fopen('file.json', 'w');
                fwrite($newFile, file_get_contents($form['edpoint']->getData()));
            }
            // $url = $request->get('apy_rest')['edpoint'];

            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //   CURLOPT_URL => $url,
            //   CURLOPT_RETURNTRANSFER => true,
            //   CURLOPT_ENCODING => '',
            //   CURLOPT_MAXREDIRS => 10,
            //   CURLOPT_TIMEOUT => 0,
            //   CURLOPT_FOLLOWLOCATION => true,
            //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //   CURLOPT_CUSTOMREQUEST => 'GET',

            //   CURLOPT_HTTPHEADER => array(
            //     'Content-Type: application/json'
            //   ),
            // ));

            // $response = curl_exec($curl);

            // curl_close($curl);
            // $value=$response;
        }
        return $this->render('api_rest/index.html.twig', [
            'controller_name' => 'ApiRestController',
            'form' => $form->createView(),
            'diferences' => $diferences,
            'response' => $response,
        ]);
    }
}

function isJSON($string)
{
    return is_string($string) &&
        is_array(json_decode($string, true)) &&
        json_last_error() == JSON_ERROR_NONE
        ? true
        : false;
}
