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
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
        $diferences = [];
        $diffCount = 0;
        $value = 'No hay valor asignado';
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form['excel']->getData();
            // try {
            $archivo = file_get_contents($url, 'r');

            $newFile = fopen('uploadedfile.xlsx', 'w');
            fwrite($newFile, $archivo);
            // var_dump($newFile);
            $rutaArchivo = 'uploadedfile.xlsx';
            $documento = IOFactory::load($rutaArchivo);
            // var_dump($documento->getSheet(1))   ;
            $valores = [];
            $totalDeHojas = $documento->getSheetCount();
            $rutaArchivo = 'file.xlsx';
            $olddocumento = IOFactory::load($rutaArchivo);

            $totalDeHojas = $documento->getSheetCount();

            # Iterar hoja por hoja
            for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {
                # Obtener hoja en el índice que vaya del ciclo
                $hojaActual = $documento->getSheet($indiceHoja);

                # Iterar filas
                foreach ($hojaActual->getRowIterator() as $fkey => $fila) {
                    foreach ($fila->getCellIterator() as $ckey => $celda) {
                        $valorRaw = $celda->getValue();

                        if (
                            $olddocumento
                                ->getSheet($indiceHoja)
                                ->getCell($ckey . $fkey)
                                ->getValue() != $valorRaw
                        ) {
                            $hoja = $indiceHoja + 1;
                            $diferences[$diffCount] =
                                'Se encontro una diferencia en la hoja N° ' .
                                $hoja .
                                ' Celda ' .
                                $ckey .
                                $fkey .
                                ' Antes ' .
                                $olddocumento
                                    ->getSheet($indiceHoja)
                                    ->getCell($ckey . $fkey)
                                    ->getValue() .
                                ' Ahora' .
                                $valorRaw;
                            $diffCount++;
                            $newFile = fopen('file.xlsx', 'w');
                            fwrite($newFile, $archivo);
                        }
                    }
                }
            }
            if ($diffCount > 0) {
                $value = $diferences;
            }
        }

        return $this->render('excel/index.html.twig', [
            'controller_name' => 'ExcelController',
            'form' => $form->createView(),
            'celda' => $value,
        ]);
    }
}
