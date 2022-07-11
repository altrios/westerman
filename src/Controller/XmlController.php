<?php

namespace App\Controller;

use App\Entity\Xml;
use App\Form\XmlType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DomCrawler\Crawler;

class XmlController extends AbstractController
{
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/xml', name: 'app_xml')]
    public function index(Request $request): Response
    {
        $variable = '';
        $ftp = new Xml();
        $form = $this->createForm(XmlType::class);
        $form->handleRequest($request);
        $response = 'No se ubica el archivo xml';
        

        if ($form->isSubmitted() && $form->isValid()) {
            $file = file_get_contents($form['xml']->getData());
            $xml = simplexml_load_string($file);
            $json = json_encode($xml);

            $crawler = new Crawler($file);

            $response = $json;
            foreach ($crawler as $domElement) {
                $variable = $domElement->nodeName;
                // var_dump(explode($domElement->nodeName, $file) );
            }
        }
        return $this->render('api_rest/index.html.twig', [
            'controller_name' => 'ApiRestController',
            'form' => $form->createView(),
            // 'resultado' => $value,
            'response' => $response,
        ]);
    }
}
