<?php

namespace App\Controller;

use App\Entity\Ftp;
use App\Form\ExcelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Finder\Finder;

class ExcelController extends AbstractController
{
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'app_excel')]
    public function index(Request $request): Response
    {
        $url = '';
        $ftp = new Ftp();
        $form = $this->createForm(ExcelType::class);
        $form->handleRequest($request);
        $value='No hay valor asignado';
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $request->get('excel')['excel'];
            try {
                $archivo = file_get_contents($url, 'r');

                $newFile = fopen('file.xlsx', 'w');
                fwrite($newFile, $archivo);
                // var_dump($newFile);
                $rutaArchivo = 'file.xlsx';
                $documento = IOFactory::load($rutaArchivo);
                // var_dump($documento->getSheet(1))   ;
                $valores = [];
                $totalDeHojas = $documento->getSheetCount();

                //     # Obtener hoja en el índice que vaya del ciclo
                $hojaActual = $documento->getSheet(
                    $request->get('excel')['hoja']-1
                );
                //     var_dump($request->get('excel')['coordenadas']);

                $coordenadas = $request->get('excel')['coordenadas'];

                //     # Lo que hay en A1
                $celda = $hojaActual->getCell($coordenadas);

                //     # El valor, así como está en el documento
                $valorRaw = $celda->getValue();

                $valores[$request->get('excel')['hoja']][
                    'valorRaw'
                ] = $valorRaw;
                $value=$valores[$request->get('excel')['hoja']]['valorRaw'];
            } catch (\Exception $e) {
            }
        }




        return $this->render('excel/index.html.twig', [
            'controller_name' => 'ExcelController',
            'form' => $form->createView(),
            'celda' => $value,
        ]);
    }
}
