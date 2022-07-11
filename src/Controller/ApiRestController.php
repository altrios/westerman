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
        $value='No se ubica el JSON';
        $response='';
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->get('apy_rest')['edpoint'];


            $curl = curl_init();
        
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
          
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            $value=$response;
        }
        return $this->render('api_rest/index.html.twig', [
            'controller_name' => 'ApiRestController',
            'form' => $form->createView(),
            'resultado' => $value,
            'response'=>$response
        ]);
    }
}
