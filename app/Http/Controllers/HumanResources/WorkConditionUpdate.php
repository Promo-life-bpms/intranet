<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\Style\Language;

class WorkConditionUpdate extends Controller
{
    public function workConditionUpdate($name, $lastname,$position )
    {
       
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

        $section11 = "<p>Por medio de la presente se hace constar que <b>$name $lastname</b> de nacionalidad <b>NACIONALIDAD</b> con fecha de nacimiento <b>DÍA, MES, AÑO</b>, <b>GÉNERO (MASCULINO/FEMENINO)</b>, estado civil ______, con RFC ___________ con domicilio en _____________________ y con correo electrónico: _________________ desempeño mis funciones y actividades, bajo las siguientes condiciones: </p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>FECHA DE INGRESO: <b>FORMATO DÍA, MES, AÑO (DD, MM, AAAA)</b></p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>PUESTO: <b>PUESTO</b></p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>CONTRATACIÓN: <b>3 MESES</b></p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>SALARIO: <b>SUELDO MENSUAL (NETO/BRUTO)</b>  DIA DE PAGO:<b>15 Y ÚLTIMO DE CADA MES</b></p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>HORARIO: DE L A V DE 9:00 A.M. A 06:00 P.M. Y S DE 9:00 A.M. A 2:00 P.M.</p>";
        $htmlsection->addHtml($section, $section11);

        $section11 = "<p>DIAS DE DESCANSO: DOMINGOS.</p>";
        $htmlsection->addHtml($section, $section11);
        
        $section->addText(
            'LUGAR Y FORMA DE PAGO: Estado de México- Transferencia',
            $titleStyle
        );

        $section->addText(
            'PRESTACIONES LEGALES: En términos de la Ley Federal del Trabajo.',
            $titleStyle
        );

        $section->addText(
            'Así mismo hago mención que recibo constantemente capacitación y adiestramiento, en los términos y bajo los lineamientos que establece la Ley Federal del Trabajo, así como los Planes y Programas que para tal efecto resultan aplicables en el domicilio del Patrón.',
            $titleStyle
        );

        $section11 = "<p>Por último, manifiesto mi conformidad en que a partir del día: <b>FECHA DE INGRESO (FORMATO DD,MM,AAAA)</b>, <b>mis recibos de pago de salario se me harán llegar vía correo electrónico a la cuenta que yo proporcione</b>, en el entendido que tendré 5 días para solicitar cualquier corrección, con posterioridad a dicho plazo, se entenderá como una aceptación tácita, con fundamentó en el artículo 99, de la Ley del Impuesto sobre la Renta.</p>";
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
            $name. ' '.$lastname,
            $titleCenterBoldStyle, $center
        );
        $section->addText(
            $position,
            $titleCenterBoldStyle, $center
        );

        //Setting page margins
        $phpWord->getSettings()->setMirrorMargins(false);
        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginTop(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));
        $sectionStyle->setMarginBottom(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'CONVENIO DE CONFIDENCIALIDAD ' . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
       
    }
}
