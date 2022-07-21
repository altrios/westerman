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
        $diferences=[];
        $isDiferent=false;
        $diferencesCount=0;

        if ($form->isSubmitted() && $form->isValid()) {
            $file = file_get_contents($form['xml']->getData());
            $xml = simplexml_load_string($file);
            if (file_exists('file.xml')) {
                $old=json_encode(simplexml_load_string(file_get_contents('file.xml')));
                $new=json_encode($xml);
                // var_dump($old==$new);
                $index=0;
                $oldFile=simplexml_load_string(file_get_contents('file.xml'));
                foreach ($xml->Articulo as $key=>$valores){
                    //obtenemos el codigo de cada articulo
                    $oldCodigo=json_encode($oldFile->Articulo[$index]->Codigo[0]);
                    $newCodigo=json_encode($valores->Codigo[0]);
                    
                    //obtenemos la Descripcion de cada articulo
                    $oldDescripcion=json_encode($oldFile->Articulo[$index]->Descripcion[0]);
                    $newDescripcion=json_encode($valores->Descripcion[0]);
                    
                    //obtenemos el CodigoBarras de cada articulo
                    $oldCodigoBarras=json_encode($oldFile->Articulo[$index]->CodigoBarras);
                    $newCodigoBarras=json_encode($valores->CodigoBarras);
                    
                    //obtenemos el Precio de cada articulo
                    $oldPrecio=json_encode($oldFile->Articulo[$index]->Precio[0]);
                    $newPrecio=json_encode($valores->Precio[0]);
                    
                    //obtenemos el PrecioBase de cada articulo
                    $oldPrecioBase=json_encode($oldFile->Articulo[$index]->PrecioBase[0]);
                    $newPrecioBase=json_encode($valores->PrecioBase[0]);
                    
                    //obtenemos el Surtido de cada articulo
                    $oldSurtido=json_encode($oldFile->Articulo[$index]->Surtido[0]);
                    $newSurtido=json_encode($valores->Surtido[0]);
                    
                    //obtenemos Cantidad Cantidad de cada articulo
                    $oldCantidad=json_encode($oldFile->Articulo[$index]->Cantidad[0]);
                    $newCantidad=json_encode($valores->Cantidad[0]);
                    
                    //obtenemos el StockReal de cada articulo
                    $oldStockReal=json_encode($oldFile->Articulo[$index]->StockReal[0]);
                    $newStockReal=json_encode($valores->StockReal[0]);
                    
                    //obtenemos el StockTeorico de cada articulo
                    $oldStockTeorico=json_encode($oldFile->Articulo[$index]->StockTeorico[0]);
                    $newStockTeorico=json_encode($valores->StockTeorico[0]);
                    
                    //obtenemos el StockDisponible de cada articulo
                    $oldStockDisponible=json_encode($oldFile->Articulo[$index]->StockDisponible[0]);
                    $newStockDisponible=json_encode($valores->StockDisponible[0]);
                    
                    //obtenemos el VMD de cada articulo
                    $oldVMD=json_encode($oldFile->Articulo[$index]->VMD[0]);
                    $newVMD=json_encode($valores->VMD[0]);
                    
                    if($oldCodigo!=$newCodigo){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el Codigo: Antes ".
                        $oldCodigo." Ahora ".$newCodigo;
                        $diferencesCount++;
                    }
                    if($newDescripcion!=$oldDescripcion){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en la Descripcion: Antes ".
                        $oldDescripcion." Ahora ".$newDescripcion;
                        $diferencesCount++;
                    }
                    foreach(json_decode($newCodigoBarras) as $rncb){
                        
                    }
                    foreach(json_decode($oldCodigoBarras) as $rocb){
                        
                    }

                    if($rncb!=$rocb){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el CodigoBarras: Antes ".
                        (string)$rocb." Ahora ". (string)$rncb;
                        $diferencesCount++;
                    }
                    if($newPrecio!=$oldPrecio){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el Precio: Antes ".
                        $oldPrecio." Ahora ".$newPrecio;
                        $diferencesCount++;
                    }
                    if($newPrecioBase!=$oldPrecioBase){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el PrecioBase: Antes ".
                        $oldPrecioBase." Ahora ".$newPrecioBase;
                        $diferencesCount++;
                    }
                    if($newSurtido!=$oldSurtido){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el Surtido: Antes ".
                        $oldSurtido." Ahora ".$newSurtido;
                        $diferencesCount++;
                    }
                    
                    if($newCantidad!=$oldCantidad){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el Cantidad: Antes ".
                        $oldCantidad." Ahora ".$newCantidad;
                        $diferencesCount++;
                    }
                    if($newStockReal!=$oldStockReal){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el StockReal: Antes ".
                        $oldStockReal." Ahora ".$newStockReal;
                        $diferencesCount++;
                    }
                    if($newStockTeorico!=$oldStockTeorico){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el StockTeorico: Antes ".
                        $oldStockTeorico." Ahora ".$newStockTeorico;
                        $diferencesCount++;
                    }
                    if($newStockDisponible!=$oldStockDisponible){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el StockDisponible: Antes ".
                        $oldStockDisponible." Ahora ".$newStockDisponible;
                        $diferencesCount++;
                    }
                    if($newVMD!=$oldVMD){
                        $diferences[$diferencesCount]="Articulo N°: $index, diferencias en el VMD: Antes ".
                        $oldVMD." Ahora ".$newVMD;
                        $diferencesCount++;
                    }
                    // var_dump($valores->Codigo[0]);
                    // var_dump($oldFile->Articulo[$index]->Codigo[0]);
                    // exit();
                    $index++;
                }
                if($diferences>0){
                    $newFile = fopen('file.xml', 'w');
                    fwrite($newFile, $file);
                    // var_dump($diferences);
                }
                // var_dump($xml->Articulo[2]);
                // echo "El fichero file.xml existe";die;
            } else {
                $newFile = fopen('file.xml', 'w');
                fwrite($newFile, $file);
                // echo "El fichero file.xml no existe";die;
            }
            $json = json_encode($xml);

            $crawler = new Crawler($file);

            $response = $json;
            foreach ($crawler as $domElement) {
                $variable = $domElement->nodeName;
                // var_dump(explode($domElement->nodeName, $file) );
            }
        }
        return $this->render('xml/index.html.twig', [
            'controller_name' => 'ApiRestController',
            'form' => $form->createView(),
            'deferences'=>$diferences,
            'response' => $response,
        ]);
    }
}
