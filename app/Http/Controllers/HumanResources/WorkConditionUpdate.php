<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\Style\Language;

class WorkConditionUpdate extends Controller
{
    public function workConditionUpdate($postulant,$postulant_details, $company_id)
    {
        $company = "";
        $employer = "";
        $name = strtoupper($postulant->name);
        $lastname = strtoupper($postulant->lastname); 
        $nacionality = strtoupper($postulant_details->nacionality);  
        $civil_status = strtoupper($postulant_details->civil_status) ;
        $domicile = strtoupper($postulant_details->address);
        $mail = strtoupper($postulant->mail) ;
        $age = $postulant_details->age;
        $curp = strtoupper($postulant_details->curp);
        $rfc = strtoupper($postulant_details->rfc);
        $gender = strtoupper($postulant_details->gender);
        $position = strtoupper($postulant_details->position);
        $position_objetive =  strtoupper($postulant_details->position_objetive);
        $birthdate = date('d,m,Y', strtotime($postulant_details->birthdate));
        $date_admission = date('d,m,Y', strtotime($postulant_details->date_admission));
        $next_sign_date = date('d,m,Y',strtotime('+3 months', strtotime($postulant_details->date_admission)));
        $month_salary_net = $postulant_details->month_salary_net; 
        $horary= "DE L A J DE 8:00 A.M. A 05:00 P.M. Y V DE 8:30 A.M. A 5:00 P.M.";
        $rest_days = "SABADOS Y DOMINGOS";

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->getSettings()->setMirrorMargins(true);
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

        //Global styles
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10);
        
        //Font Styles
        $phpWord->setDefaultParagraphStyle(
            array(
                'align' => 'both',
                'lineHeight' => 1.15
            )
        );
        $titleStyle = array(
            'align' => 'both',
            'lineHeight' => 1.0,
            'bold' => false
        );
        $titleBoldStyle = array(
            'align' => 'both',
            'lineHeight' => 2.0,
            'bold' => true
        ); 
        $titleCenterBoldStyle = array(
            'lineHeight' => 1.0,
            'bold' => true
        ); 

        //Paragraph Styles
        $center = array(
            'align'=> 'center'
        );

        $justify_center = array(
            'align' => 'both',
        );

        $list = array(
            'lineHeight' => 0.5,
        );

        //Secctions
        $section = $phpWord->addSection();
        $htmlsection= new \PhpOffice\PhpWord\Shared\Html();

        $section->addText(
            'CONSTANCIA DE ACTUALIZACIÓN DE CONDICIONES DE TRABAJO',
            $titleCenterBoldStyle, $center
        );

        //Unipromtex
        if($company_id== 5){
            $horary = "DE L A V DE 9:00 A.M. A 06:00 P.M. Y S DE 9:00 A.M. A 2:00 P.M."; 
            $rest_days = "DOMINGOS";
           
        } 


        $section11 = "<p>Por medio de la presente se hace constar que <b>$name $lastname</b> de nacionalidad <b>$nacionality</b> con fecha de nacimiento <b>$birthdate</b>, <b>GÉNERO $gender</b>, estado civil <b>$civil_status</b>, con RFC <b>$rfc</b> con domicilio en <b>$domicile</b> y con correo electrónico: <b>$mail</b> desempeño mis funciones y actividades, bajo las siguientes condiciones: </p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>FECHA DE INGRESO: <b>$date_admission</b></p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>PUESTO: <b>$position</b></p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>CONTRATACIÓN: <b>3 MESES</b></p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>SALARIO: <b>SUELDO MENSUAL: $month_salary_net</b>    DIA DE PAGO:<b>15 Y ÚLTIMO DE CADA MES</b></p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>HORARIO: <b>$horary</b></p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>DIAS DE DESCANSO: <b>$rest_days</b>.</p>";
        $htmlsection->addHtml($section, $section11);
        
        
        $section11 = "<p>LUGAR Y FORMA DE PAGO: <b>Estado de México- Transferencia</b>.</p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>PRESTACIONES LEGALES: <b>En términos de la Ley Federal del Trabajo</b>.</p>";
        $htmlsection->addHtml($section, $section11);

        $section->addText(
            'Así mismo hago mención que recibo constantemente capacitación y adiestramiento, en los términos y bajo los lineamientos que establece la Ley Federal del Trabajo, así como los Planes y Programas que para tal efecto resultan aplicables en el domicilio del Patrón.',
            $titleStyle
        );

        $section11 = "<p>Por último, manifiesto mi conformidad en que a partir del día: <b>$date_admission</b>, <b>mis recibos de pago de salario se me harán llegar vía correo electrónico a la cuenta que yo proporcione</b>, en el entendido que tendré 5 días para solicitar cualquier corrección, con posterioridad a dicho plazo, se entenderá como una aceptación tácita, con fundamentó en el artículo 99, de la Ley del Impuesto sobre la Renta.</p>";
        $htmlsection->addHtml($section, $section11);
       
        $section->addText(
            '',
            $titleCenterBoldStyle, $center
        );
        
        $section->addText(
            'ATENTAMENTE',
            $titleCenterBoldStyle, $center
        );

        $section->addText(
            '',
            $titleCenterBoldStyle, $center
        );
        $section->addText(
            '______________________________',
            $titleCenterBoldStyle, $center
        );
        $section->addText(
            $name. ' '.$lastname . '<w:br/>' .  $position,
            $titleCenterBoldStyle, $center
        );
       /*  $section->addText(
            $position,
            $titleCenterBoldStyle, $center
        );
 */
        //Setting page margins
        $phpWord->getSettings()->setMirrorMargins(false);
        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginTop(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));
        $sectionStyle->setMarginBottom(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'CONSTANCIA DE ACTUALIZACION DE CONDICIONES DE TRABAJO ' .$name. ' ' . $lastname . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
       
    }
}
