<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use App\Models\Department;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UpDocument extends Controller
{
    public function UpDocument($postulant, $postulant_details, $postulant_beneficiaries, $company_id )
    {
        $departmet = Department::all()->where('id',$postulant->department_id)->last();
        $company_name = '';
        $horary = '';
        $postulant_has_infonavit = "";
        $postulant_has_fonacot = "";
        $postulant_no_has_infonavit = "";
        $postulant_no_has_fonacot = "";

        $name_postulant_beneficiary1 = "";
        $name_postulant_beneficiary2 = "";
        $name_postulant_beneficiary3 = "";

        $porcentage_postulant_beneficiary1 = "";
        $porcentage_postulant_beneficiary2 = "";
        $porcentage_postulant_beneficiary3 = "";

         //Promolife
         if($company_id == 1){
            $company_name = "PROMO LIFE, S. DE R.L. DE C.V.";
            $horary = 'DE L A J DE 8:00 AM A 5:00 PM Y V DE 8:30 AM A 5:00 PM';
        }

        //BH tardemarket
        if($company_id == 2){
            $company_name = "BH TRADE MARKET, S.A. DE C.V.";
            $horary = 'DE L A J DE 8:00 A.M. A 5:00 P.M. Y V DE 8:30 A.M. A 5:00 P.M.';
        }

        //Promo zale
        if($company_id == 3){
            $company_name = "PROMO ZALE S.A. DE C.V."; 
            $horary = 'DE L A J DE 8:00 A.M. A 5:00 P.M. Y V DE 8:30 A.M. A 5:00 P.M.';
        }

        //Trademarket 57
        if($company_id== 4){
            $company_name = "TRADE MARKET 57, S.A. DE C.V."; 
            $horary = 'DE L A J DE 8:00 A.M. A 5:00 P.M. Y V DE 8:30 A.M. A 5:00 P.M.';
        } 

        //Unipromtex
        if($company_id== 5){
            $company_name = "UNIPROMTEX S.A. DE C.V."; 
            $horary = 'DE L A V DE 9:00 A.M. A 6:00 P.M. Y S DE 9 A.M. A 2:00 P.M.';
        } 
        
        if($postulant_details->infonavit_credit == "si"){
            $postulant_has_infonavit = "*"; 
        }else{
            $postulant_no_has_infonavit = "*";
        }

        if($postulant_details->fonacot_credit == "si"){
            $postulant_has_fonacot = "*";
        }else{
            $postulant_no_has_fonacot = "*";
        }

       
        if($postulant_beneficiaries<>null){
            if(count($postulant_beneficiaries)==1){
                $name_postulant_beneficiary1 = $postulant_beneficiaries[0]->name;
                $porcentage_postulant_beneficiary1 = $postulant_beneficiaries[0]->porcentage;
            }
    
            if(count($postulant_beneficiaries)==2){
                $name_postulant_beneficiary1 = $postulant_beneficiaries[0]->name;
                $porcentage_postulant_beneficiary1 = $postulant_beneficiaries[0]->porcentage;
                $name_postulant_beneficiary2 = $postulant_beneficiaries[1]->name;
                $porcentage_postulant_beneficiary2 = $postulant_beneficiaries[1]->porcentage;
            }
    
            if(count($postulant_beneficiaries)==3){
                $name_postulant_beneficiary1 = $postulant_beneficiaries[0]->name;
                $porcentage_postulant_beneficiary1 = $postulant_beneficiaries[0]->porcentage;
                $name_postulant_beneficiary2 = $postulant_beneficiaries[1]->name;
                $porcentage_postulant_beneficiary2 = $postulant_beneficiaries[1]->porcentage;
                $name_postulant_beneficiary3 = $postulant_beneficiaries[2]->name;
                $porcentage_postulant_beneficiary3 = $postulant_beneficiaries[2]->porcentage;
            }
        }        

        //Personal de alta
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $styleBorders = [
           'borders' => [
               'outline' => [
                   'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                   'color' => ['argb' => 'FF000000'],
                ],
           ],
           'font' => [
               'name' => 'Arial',
               'size' => 12
           ]
        ];

        $styleExclusive = [
           'borders' => [
                'outline' => [
                   'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                   'color' => ['argb' => 'FF000000'],
                ],
            ],
           'font' => [
               'name' => 'Arial',
               'size' => 12,
               'bold'  => true,
            ],
           'fill' => [ 
               'fillType' => Fill::FILL_SOLID,
               'startColor' => array('argb' => 'FFD9D9D9')
            ],
        ];

        $styleExclusiveBody = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'font' => [
                'name' => 'Arial',
                'size' => 12,
                'bold'  => true,
            ],
        ];

        $styleRows = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $styleTitle = [
            'font' => [
                'bold'  => true,
                'name' => 'Arial',
                'size' => 12,    
            ]
        ];

        $styleBeneficiary = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'font' => [
                'name' => 'Arial',
                'size' => 12
            ]
        ];
        $sheet->getStyle('A1:G51')->applyFromArray($styleBorders);
        $sheet->getStyle('A53:G56')->applyFromArray($styleBorders);
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(34);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(36);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(4);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(8);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(6);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(4);
        $spreadsheet->getActiveSheet()->getRowDimension('52')->setRowHeight(5);
        $sheet->getStyle('A2')->applyFromArray($styleTitle);
        $sheet->getStyle('B4:G4')->applyFromArray($styleRows);
        $sheet->getStyle('B6:G6')->applyFromArray($styleRows);
        $sheet->getStyle('B8:G8')->applyFromArray($styleRows);
        $sheet->getStyle('B10:G10')->applyFromArray($styleRows);
        $sheet->getStyle('B12:G12')->applyFromArray($styleRows);
        $sheet->getStyle('B14:G14')->applyFromArray($styleRows);
        $sheet->getStyle('B16')->applyFromArray($styleRows);
        $sheet->getStyle('F16:G16')->applyFromArray($styleRows);
        $sheet->getStyle('B19:G19')->applyFromArray($styleRows);
        $sheet->getStyle('B21:G21')->applyFromArray($styleRows);
        $sheet->getStyle('B23:G23')->applyFromArray($styleRows);
        $sheet->getStyle('B25:G25')->applyFromArray($styleRows);
        $sheet->getStyle('B27:G27')->applyFromArray($styleRows);
        $sheet->getStyle('B29:G29')->applyFromArray($styleRows);
        $sheet->getStyle('B31:G31')->applyFromArray($styleRows);
        $sheet->getStyle('B33:G33')->applyFromArray($styleRows);
        $sheet->getStyle('B35:G35')->applyFromArray($styleRows);
        $sheet->getStyle('B37:G37')->applyFromArray($styleRows);

        $sheet->getStyle('B39:D39')->applyFromArray($styleBeneficiary);
        $sheet->getStyle('E39')->applyFromArray($styleBeneficiary);
        $sheet->getStyle('F39')->applyFromArray($styleBeneficiary);
        $sheet->getStyle('G39:F39')->applyFromArray($styleBeneficiary);

        $sheet->getStyle('B40:D40')->applyFromArray($styleBeneficiary);
        $sheet->getStyle('E40')->applyFromArray($styleBeneficiary);
        $sheet->getStyle('F40')->applyFromArray($styleBeneficiary);
        $sheet->getStyle('G40:F40')->applyFromArray($styleBeneficiary);

        $sheet->getStyle('B41:D41')->applyFromArray($styleBeneficiary);
        $sheet->getStyle('E41')->applyFromArray($styleBeneficiary);
        $sheet->getStyle('F41')->applyFromArray($styleBeneficiary);
        $sheet->getStyle('G41:F41')->applyFromArray($styleBeneficiary);

        $sheet->getStyle('B43:G43')->applyFromArray($styleRows);
        $sheet->getStyle('B45:G45')->applyFromArray($styleRows);
        $sheet->getStyle('B47:G47')->applyFromArray($styleRows);
        $sheet->getStyle('B48:G48')->applyFromArray($styleRows);
        $sheet->getStyle('B49:G49')->applyFromArray($styleRows);
        $sheet->getStyle('B50:G50')->applyFromArray($styleRows);
        $sheet->getStyle('A53:G53')->applyFromArray($styleExclusive);

        $sheet->getStyle('E54:G54')->applyFromArray($styleExclusiveBody);
        $sheet->getStyle('E55:G55')->applyFromArray($styleExclusiveBody);
        $sheet->getStyle('C54:D55')->applyFromArray($styleExclusiveBody);
        $sheet->getStyle('A56')->applyFromArray($styleExclusiveBody);
        $sheet->getStyle('B54:B55')->applyFromArray($styleExclusiveBody);
        $sheet->getStyle('B56')->applyFromArray($styleExclusiveBody);
        $sheet->getStyle('C56:G56')->applyFromArray($styleExclusiveBody);

        $sheet->setCellValue('A2', '                   ALTA  (  *  )                      BAJA  (    )                      MODIFICACION  (    )');
        $sheet->setCellValue('A4', 'EMPRESA:');
        $sheet->setCellValue('B4', strtoupper($company_name));
        $sheet->setCellValue('A6', 'NOMBRE:'); 
        $sheet->setCellValue('B6', strtoupper($postulant->name .' '. $postulant->lastname));
        $sheet->setCellValue('A8', 'LUGAR DE NACIMIENTO:');
        $sheet->setCellValue('B8', strtoupper($postulant_details->place_of_birth));
        $sheet->setCellValue('A10', 'FECHA DE NACIMIENTO:');
        $sheet->setCellValue('B10', date('d-m-Y', strtotime($postulant_details->birthdate))); 
        $sheet->setCellValue('A12', 'NOMBRE DEL PADRE:');
        $sheet->setCellValue('B12', strtoupper($postulant_details->fathers_name));
        $sheet->setCellValue('A14', 'NOMBRE DE LA MADRE:');
        $sheet->setCellValue('B14', strtoupper($postulant_details->mothers_name));
        $sheet->setCellValue('A16', 'ESTADO CIVIL:');
        $sheet->setCellValue('B16', strtoupper($postulant_details->civil_status));
        $sheet->setCellValue('E16', 'EDAD:');
        $sheet->setCellValue('F16', strtoupper($postulant_details->age));
        $sheet->setCellValue('A18', 'DIRECCION:');
        $sheet->setCellValue('B18', strtoupper($postulant_details->address));
        $sheet->setCellValue('A19', 'CALLE:');
        $sheet->setCellValue('B19', strtoupper($postulant_details->street));
        $sheet->setCellValue('A21', 'COLONIA:');
        $sheet->setCellValue('B21', strtoupper($postulant_details->colony));
        $sheet->setCellValue('A23', 'DELEGACION O MUNICIPIO:');
        $sheet->setCellValue('B23', strtoupper($postulant_details->delegation));
        $sheet->setCellValue('C23', 'C.P.:');
        $sheet->setCellValue('D23', strtoupper($postulant_details->postal_code));
        $sheet->setCellValue('A25', 'TELEFONO:');
        $sheet->setCellValue('B25', strtoupper('CEL: '.$postulant_details->cell_phone));
        $sheet->setCellValue('C25', 'CASA:');
        $sheet->setCellValue('D25', strtoupper(strval($postulant_details->home_phone)));
        $sheet->setCellValue('A27', 'CURP:');
        $sheet->setCellValue('B27', strtoupper($postulant_details->curp));
        $sheet->setCellValue('C27', 'R.F.C.:');
        $sheet->setCellValue('D27', strtoupper($postulant_details->rfc));
        $sheet->setCellValue('A29', 'NO. AFILIACION IMSS:');
        $sheet->setCellValue('B29', strtoupper($postulant_details->imss_number));
        $sheet->setCellValue('C29', 'C.P. FISCAL:');
        $sheet->setCellValue('D29', strtoupper($postulant_details->fiscal_postal_code));
        $sheet->setCellValue('A31', 'PUESTO:');
        $sheet->setCellValue('B31', strtoupper($postulant_details->position));
        $sheet->setCellValue('C31', 'AREA:');
        $sheet->setCellValue('D31', strtoupper($departmet->name));
        $sheet->setCellValue('A33', 'SUELDO:');
        $sheet->setCellValue('B33', strtoupper($postulant_details->month_salary_net));
        $sheet->setCellValue('C33', 'HORARIO:');
        $sheet->setCellValue('D33', strtoupper($horary));
        $sheet->setCellValue('A35', 'FECHA DE INGRESO:');
        $sheet->setCellValue('B35', date('d/m/Y', strtotime($postulant_details->date_admission)));
        $sheet->setCellValue('A37', 'NO. TARJETA / NO. CUENTA:');
        $sheet->setCellValue('B37', strtoupper($postulant_details->card_number));
        $sheet->setCellValue('C37', 'BANCO:');
        $sheet->setCellValue('D37', strtoupper($postulant_details->bank_name));
        $sheet->setCellValue('A39', 'BENEFICIARIOS:');
        $sheet->setCellValue('B39', strtoupper($name_postulant_beneficiary1));
        $sheet->setCellValue('B40', strtoupper($name_postulant_beneficiary2));
        $sheet->setCellValue('B41', strtoupper($name_postulant_beneficiary3));
        $sheet->setCellValue('E39', '%');
        $sheet->setCellValue('E40', '%');
        $sheet->setCellValue('E41', '%');
        $sheet->setCellValue('F39', strtoupper($porcentage_postulant_beneficiary1) );
        $sheet->setCellValue('F40', strtoupper($porcentage_postulant_beneficiary2) );
        $sheet->setCellValue('F41', strtoupper($porcentage_postulant_beneficiary3));
        $sheet->setCellValue('A43', 'CREDITO INFONAVIT:');
        $sheet->setCellValue('B43', 'SI ( '. $postulant_has_infonavit .' )   NO ( ' . $postulant_no_has_infonavit . '  )   No. CREDITO FACTOR:');
        $sheet->setCellValue('E43', strtoupper($postulant_details->factor_credit_number));
        $sheet->setCellValue('A45', 'CREDITO FONACOT:');
        $sheet->setCellValue('B45', 'SI ( '. $postulant_has_fonacot .' )   NO ( ' . $postulant_no_has_fonacot . ' )   No. CREDITO DESCUENTO:');
        $sheet->setCellValue('E45', strtoupper($postulant_details->discount_credit_number));
        $sheet->setCellValue('A47', 'REFERENCIAS DOMICILIO:');
        $sheet->setCellValue('B47', strtoupper($postulant_details->home_references));
        $sheet->setCellValue('A49', 'CARACTERISTICAS DE CASA:');
        $sheet->setCellValue('B49', strtoupper($postulant_details->house_characteristics));
        $sheet->setCellValue('A50', 'CORREO ELECTRONICO:');
        $sheet->setCellValue('B50', strtoupper($postulant->mail));
        $sheet->setCellValue('C54', 'SD');
        $sheet->setCellValue('C55', 'SBC');
        $sheet->setCellValue('A56', 'OBSERVACIONES');
        $sheet->setCellValue('B56', 'NUM DE EMPLEADO EN NOMINA');
        $sheet->setCellValue('D56', 'SALARIO');
        $sheet->setCellValue('B53','EXCLUSIVO RECURSOS HUMANOS');

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . 'ALTA PERSONAL ' . strtoupper($postulant->name) . ' ' . strtoupper($postulant->lastname)  . '.xls');
        header('Cache-Control: max-age=0');
          
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }
}
