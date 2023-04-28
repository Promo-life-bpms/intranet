<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StadisticReport extends Controller
{
    public function stadisticReport($promolife_users,$bhtrade_users,$promozale_users,$tradenarket57_users,  $down_promolife_users,$down_bhtrade_users,$down_promozale_users,$down_tradenarket57_users)
    {
        $styleTitle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                 ],
            ],
            'font' => [
                'name' => 'Arial',
                'size' => 10
            ]
        ];

        $styleSubtitle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                 ],
            ],
            'font' => [
                'name' => 'Arial',
                'size' => 10
            ],
            'fill' => array(
                'fillType' => Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFEDEFF0')
            )
        ];

        $styleBody = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                 ],
            ],
            'font' => [
                'name' => 'Arial',
                'size' => 10
            ],
            
        ];




        //Pagina de Promolife - Altas

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->setTitle("Promo life");

        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $sheet->getStyle('A1:F1')->applyFromArray($styleTitle);
        $sheet->getStyle('A2:F2')->applyFromArray($styleSubtitle);

        $loop = 0; 
        $start = 2;
        
        $sheet->setCellValue('A1', 'PROMO LIFE S. DE R.L. DE C.V. (ALTAS)');

        $sheet->setCellValue('A2', '#');
        $sheet->setCellValue('B2', 'Nombre');  
        $sheet->setCellValue('C2', 'Apellidos');
        $sheet->setCellValue('D2', 'Departamento');
        $sheet->setCellValue('E2', 'Puesto');
        $sheet->setCellValue('F2', 'Fecha de ingreso');

        foreach($promolife_users as $user){
            $start = $start +1;
            $loop = $loop +1;
            $sheet->setCellValue('A'.$start, $loop);
            $sheet->setCellValue('B'.$start, $user->name);
            $sheet->setCellValue('C'.$start, $user->lastname);
            $sheet->setCellValue('D'.$start, isset($user->employee->position->department->name)?$user->employee->position->department->name :'' );
            $sheet->setCellValue('E'.$start, isset($user->employee->position->name)?$user->employee->position->name:'');
            $sheet->setCellValue('F'.$start, $user->employee->date_admission->format('d-m-Y'));
        }
      
        $sheet->getStyle('A2:F'.$start)->applyFromArray($styleBody);

        //Pagina de Promolife - Bajas
        $table_loop = 0;
        $down_start = $start + 5;
        $down_loop = $start + 6;

        $sheet->getStyle('A'.$down_start.':G'.$down_start)->applyFromArray($styleTitle);
        $sheet->getStyle('A'.$down_loop.':G'.$down_loop)->applyFromArray($styleSubtitle);
        
        $sheet->setCellValue('A'.$down_start, 'PROMO LIFE S. DE R.L. DE C.V. (BAJAS)');
        
        $sheet->setCellValue('A'.$down_loop, '#');
        $sheet->setCellValue('B'.$down_loop, 'Nombre');  
        $sheet->setCellValue('C'.$down_loop, 'Apellidos');
        $sheet->setCellValue('D'.$down_loop, 'Departamento');
        $sheet->setCellValue('E'.$down_loop, 'Puesto');
        $sheet->setCellValue('F'.$down_loop, 'Fecha de ingreso');
        $sheet->setCellValue('G'.$down_loop, 'Fecha de baja');

        foreach($down_promolife_users as $user){
            $table_loop = $table_loop +1 ;
            $down_loop = $down_loop +1;
            $sheet->setCellValue('A'.$down_loop, $table_loop);
            $sheet->setCellValue('B'.$down_loop, $user->name);
            $sheet->setCellValue('C'.$down_loop, $user->lastname);
            $sheet->setCellValue('D'.$down_loop, isset($user->employee->position->department->name)?$user->employee->position->department->name :'' );
            $sheet->setCellValue('E'.$down_loop, isset($user->employee->position->name)?$user->employee->position->name:'');
            $sheet->setCellValue('F'.$down_loop, $user->employee->date_admission->format('d-m-Y'));
            $sheet->setCellValue('G'.$down_loop, isset($user->userDetails->date_down)?$user->userDetails->date_down:'');
        }

        $sheet->getStyle('A'.$down_start.':G'.$down_loop)->applyFromArray($styleBody);




        //Pagina de BH Trademarket - Alta

        $bhspreadsheet=$spreadsheet->createSheet();
        $bhspreadsheet->setTitle("BH Trademarket");
   
        $bhspreadsheet->getColumnDimension('A')->setWidth(4);
        $bhspreadsheet->getColumnDimension('B')->setAutoSize(true);
        $bhspreadsheet->getColumnDimension('C')->setAutoSize(true);
        $bhspreadsheet->getColumnDimension('D')->setAutoSize(true);
        $bhspreadsheet->getColumnDimension('E')->setAutoSize(true);
        $bhspreadsheet->getColumnDimension('F')->setAutoSize(true);
        $bhspreadsheet->getColumnDimension('G')->setAutoSize(true);

        $bhspreadsheet->getStyle('A1:F1')->applyFromArray($styleTitle);
        $bhspreadsheet->getStyle('A2:F2')->applyFromArray($styleSubtitle);

        $loop = 0; 
        $start = 2;

        $bhspreadsheet->setCellValue('A1', 'BH TRADEMARKET S.A. DE C.V. (ALTAS)');

        $bhspreadsheet->setCellValue('A2', '#');
        $bhspreadsheet->setCellValue('B2', 'Nombre');  
        $bhspreadsheet->setCellValue('C2', 'Apellidos');
        $bhspreadsheet->setCellValue('D2', 'Departamento');
        $bhspreadsheet->setCellValue('E2', 'Puesto');
        $bhspreadsheet->setCellValue('F2', 'Fecha de ingreso');

        foreach($bhtrade_users as $user){
            $start = $start +1;
            $loop = $loop +1;
            $bhspreadsheet->setCellValue('A'.$start, $loop);
            $bhspreadsheet->setCellValue('B'.$start, $user->name);
            $bhspreadsheet->setCellValue('C'.$start, $user->lastname);
            $bhspreadsheet->setCellValue('D'.$start, isset($user->employee->position->department->name)?$user->employee->position->department->name :'' );
            $bhspreadsheet->setCellValue('E'.$start, isset($user->employee->position->name)?$user->employee->position->name:'');
            $bhspreadsheet->setCellValue('F'.$start, $user->employee->date_admission->format('d-m-Y'));
        }
      
        $bhspreadsheet->getStyle('A2:F'.$start)->applyFromArray($styleBody);

        //Pagina de BH Trademarket - Baja

        $table_loop = 0;
        $down_start = $start + 5;
        $down_loop = $start + 6;

        $bhspreadsheet->getStyle('A'.$down_start.':G'.$down_start)->applyFromArray($styleTitle);
        $bhspreadsheet->getStyle('A'.$down_loop.':G'.$down_loop)->applyFromArray($styleSubtitle);
        
        $bhspreadsheet->setCellValue('A'.$down_start, 'BH TRADEMARKET S.A. DE C.V. (BAJAS)');
        
        $bhspreadsheet->setCellValue('A'.$down_loop, '#');
        $bhspreadsheet->setCellValue('B'.$down_loop, 'Nombre');  
        $bhspreadsheet->setCellValue('C'.$down_loop, 'Apellidos');
        $bhspreadsheet->setCellValue('D'.$down_loop, 'Departamento');
        $bhspreadsheet->setCellValue('E'.$down_loop, 'Puesto');
        $bhspreadsheet->setCellValue('F'.$down_loop, 'Fecha de ingreso');
        $bhspreadsheet->setCellValue('G'.$down_loop, 'Fecha de baja');

        foreach($down_bhtrade_users as $user){
            $table_loop = $table_loop +1 ;
            $down_loop = $down_loop +1;
            $bhspreadsheet->setCellValue('A'.$down_loop, $table_loop);
            $bhspreadsheet->setCellValue('B'.$down_loop, $user->name);
            $bhspreadsheet->setCellValue('C'.$down_loop, $user->lastname);
            $bhspreadsheet->setCellValue('D'.$down_loop, isset($user->employee->position->department->name)?$user->employee->position->department->name :'' );
            $bhspreadsheet->setCellValue('E'.$down_loop, isset($user->employee->position->name)?$user->employee->position->name:'');
            $bhspreadsheet->setCellValue('F'.$down_loop, $user->employee->date_admission->format('d-m-Y'));
            $bhspreadsheet->setCellValue('G'.$down_loop, isset($user->userDetails->date_down)?$user->userDetails->date_down:'');
        }

        $bhspreadsheet->getStyle('A'.$down_start.':G'.$down_loop)->applyFromArray($styleBody);

        

        //Pagina de Promo Zale - Alta
      
        $pzspreadsheet=$spreadsheet->createSheet();
        $pzspreadsheet->setTitle("Promo Zale");
   
        $pzspreadsheet->getColumnDimension('A')->setWidth(4);
        $pzspreadsheet->getColumnDimension('B')->setAutoSize(true);
        $pzspreadsheet->getColumnDimension('C')->setAutoSize(true);
        $pzspreadsheet->getColumnDimension('D')->setAutoSize(true);
        $pzspreadsheet->getColumnDimension('E')->setAutoSize(true);
        $pzspreadsheet->getColumnDimension('F')->setAutoSize(true);
        $pzspreadsheet->getColumnDimension('G')->setAutoSize(true);

        $pzspreadsheet->getStyle('A1:F1')->applyFromArray($styleTitle);
        $pzspreadsheet->getStyle('A2:F2')->applyFromArray($styleSubtitle);

        $loop = 0; 
        $start = 2;

        $pzspreadsheet->setCellValue('A1', 'PROMO ZALE S.A. DE C.V. (ALTAS)');

        $pzspreadsheet->setCellValue('A2', '#');
        $pzspreadsheet->setCellValue('B2', 'Nombre');  
        $pzspreadsheet->setCellValue('C2', 'Apellidos');
        $pzspreadsheet->setCellValue('D2', 'Departamento');
        $pzspreadsheet->setCellValue('E2', 'Puesto');
        $pzspreadsheet->setCellValue('F2', 'Fecha de ingreso');

        foreach($promozale_users as $user){
            $start = $start +1;
            $loop = $loop +1;
            $pzspreadsheet->setCellValue('A'.$start, $loop);
            $pzspreadsheet->setCellValue('B'.$start, $user->name);
            $pzspreadsheet->setCellValue('C'.$start, $user->lastname);
            $pzspreadsheet->setCellValue('D'.$start, isset($user->employee->position->department->name)?$user->employee->position->department->name :'' );
            $pzspreadsheet->setCellValue('E'.$start, isset($user->employee->position->name)?$user->employee->position->name:'');
            $pzspreadsheet->setCellValue('F'.$start, $user->employee->date_admission->format('d-m-Y'));
        }
      
        $pzspreadsheet->getStyle('A2:F'.$start)->applyFromArray($styleBody);

        //Pagina de Promo Zale - Baja

        $table_loop = 0;
        $down_start = $start + 5;
        $down_loop = $start + 6;

        $pzspreadsheet->getStyle('A'.$down_start.':G'.$down_start)->applyFromArray($styleTitle);
        $pzspreadsheet->getStyle('A'.$down_loop.':G'.$down_loop)->applyFromArray($styleSubtitle);
        
        $pzspreadsheet->setCellValue('A'.$down_start, 'PROMO ZALE S.A. DE C.V. (BAJAS)');
        
        $pzspreadsheet->setCellValue('A'.$down_loop, '#');
        $pzspreadsheet->setCellValue('B'.$down_loop, 'Nombre');  
        $pzspreadsheet->setCellValue('C'.$down_loop, 'Apellidos');
        $pzspreadsheet->setCellValue('D'.$down_loop, 'Departamento');
        $pzspreadsheet->setCellValue('E'.$down_loop, 'Puesto');
        $pzspreadsheet->setCellValue('F'.$down_loop, 'Fecha de ingreso');
        $pzspreadsheet->setCellValue('G'.$down_loop, 'Fecha de baja');

        foreach($down_promozale_users as $user){
            $table_loop = $table_loop +1 ;
            $down_loop = $down_loop +1;
            $pzspreadsheet->setCellValue('A'.$down_loop, $table_loop);
            $pzspreadsheet->setCellValue('B'.$down_loop, $user->name);
            $pzspreadsheet->setCellValue('C'.$down_loop, $user->lastname);
            $pzspreadsheet->setCellValue('D'.$down_loop, isset($user->employee->position->department->name)?$user->employee->position->department->name :'' );
            $pzspreadsheet->setCellValue('E'.$down_loop, isset($user->employee->position->name)?$user->employee->position->name:'');
            $pzspreadsheet->setCellValue('F'.$down_loop, $user->employee->date_admission->format('d-m-Y'));
            $pzspreadsheet->setCellValue('G'.$down_loop, isset($user->userDetails->date_down)?$user->userDetails->date_down:'');
        }

        $pzspreadsheet->getStyle('A'.$down_start.':G'.$down_loop)->applyFromArray($styleBody);




        //Pagina de Trade Market 57 - Alta

        $tm57spreadsheet=$spreadsheet->createSheet();
        $tm57spreadsheet->setTitle("Trade Market 57");
   
        $tm57spreadsheet->getColumnDimension('A')->setWidth(4);
        $tm57spreadsheet->getColumnDimension('B')->setAutoSize(true);
        $tm57spreadsheet->getColumnDimension('C')->setAutoSize(true);
        $tm57spreadsheet->getColumnDimension('D')->setAutoSize(true);
        $tm57spreadsheet->getColumnDimension('E')->setAutoSize(true);
        $tm57spreadsheet->getColumnDimension('F')->setAutoSize(true);
        $tm57spreadsheet->getColumnDimension('G')->setAutoSize(true);

        $tm57spreadsheet->getStyle('A1:F1')->applyFromArray($styleTitle);
        $tm57spreadsheet->getStyle('A2:F2')->applyFromArray($styleSubtitle);

        $loop = 0; 
        $start = 2;

        $tm57spreadsheet->setCellValue('A1', 'TRADE MARKET 57 S.A. DE C.V. (ALTAS)');

        $tm57spreadsheet->setCellValue('A2', '#');
        $tm57spreadsheet->setCellValue('B2', 'Nombre');  
        $tm57spreadsheet->setCellValue('C2', 'Apellidos');
        $tm57spreadsheet->setCellValue('D2', 'Departamento');
        $tm57spreadsheet->setCellValue('E2', 'Puesto');
        $tm57spreadsheet->setCellValue('F2', 'Fecha de ingreso');

        foreach($tradenarket57_users as $user){
            $start = $start +1;
            $loop = $loop +1;
            $tm57spreadsheet->setCellValue('A'.$start, $loop);
            $tm57spreadsheet->setCellValue('B'.$start, $user->name);
            $tm57spreadsheet->setCellValue('C'.$start, $user->lastname);
            $tm57spreadsheet->setCellValue('D'.$start, isset($user->employee->position->department->name)?$user->employee->position->department->name :'' );
            $tm57spreadsheet->setCellValue('E'.$start, isset($user->employee->position->name)?$user->employee->position->name:'');
            $tm57spreadsheet->setCellValue('F'.$start, $user->employee->date_admission->format('d-m-Y'));
        }
      
        $tm57spreadsheet->getStyle('A2:F'.$start)->applyFromArray($styleBody);

        //Pagina de Trade Market 57 - Baja

        $table_loop = 0;
        $down_start = $start + 5;
        $down_loop = $start + 6;

        $tm57spreadsheet->getStyle('A'.$down_start.':G'.$down_start)->applyFromArray($styleTitle);
        $tm57spreadsheet->getStyle('A'.$down_loop.':G'.$down_loop)->applyFromArray($styleSubtitle);
        
        $tm57spreadsheet->setCellValue('A'.$down_start, 'TRADE MARKET 57 S.A. DE C.V. (BAJAS)');
        
        $tm57spreadsheet->setCellValue('A'.$down_loop, '#');
        $tm57spreadsheet->setCellValue('B'.$down_loop, 'Nombre');  
        $tm57spreadsheet->setCellValue('C'.$down_loop, 'Apellidos');
        $tm57spreadsheet->setCellValue('D'.$down_loop, 'Departamento');
        $tm57spreadsheet->setCellValue('E'.$down_loop, 'Puesto');
        $tm57spreadsheet->setCellValue('F'.$down_loop, 'Fecha de ingreso');
        $tm57spreadsheet->setCellValue('G'.$down_loop, 'Fecha de baja');

        foreach($down_tradenarket57_users as $user){
            $table_loop = $table_loop +1;
            $down_loop = $down_loop +1;
            $tm57spreadsheet->setCellValue('A'.$down_loop, $table_loop);
            $tm57spreadsheet->setCellValue('B'.$down_loop, $user->name);
            $tm57spreadsheet->setCellValue('C'.$down_loop, $user->lastname);
            $tm57spreadsheet->setCellValue('D'.$down_loop, isset($user->employee->position->department->name)?$user->employee->position->department->name :'' );
            $tm57spreadsheet->setCellValue('E'.$down_loop, isset($user->employee->position->name)?$user->employee->position->name:'');
            $tm57spreadsheet->setCellValue('F'.$down_loop, $user->employee->date_admission->format('d-m-Y'));
            $tm57spreadsheet->setCellValue('G'.$down_loop, isset($user->userDetails->date_down)?$user->userDetails->date_down:'');
        }

        $tm57spreadsheet->getStyle('A'.$down_start.':G'.$down_loop)->applyFromArray($styleBody);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . 'listausuarios ' . '.xls');
        header('Cache-Control: max-age=0');
          
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }
}
