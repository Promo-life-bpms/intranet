<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\Style\Language;

class ConfidentialityAgreement extends Controller
{
    public function confidentialityAgreement($postulant, $postulant_details)
    {
        $company = "";
        $employer = "";
        $name = strtoupper($postulant->name);
        $lastname = strtoupper($postulant->lastname);
        $date_admission = date('d/m/Y', strtotime( $postulant_details->date_admission));

         //Promolife
        if($postulant->company_id == 1){
            $company = "PROMO LIFE, S. DE R.L. DE C.V.";
            $employer = "C. RAÚL TORRES MÁRQUEZ";
        }

        //BH tardemarket
        if($postulant->company_id == 2){
            $company = "BH TRADE MARKET, S.A. DE C.V.";
            $employer = "C. DAVID LEVY HANO";
        }

        //Promo zale
        if($postulant->company_id == 3){
            $company = "PROMO ZALE S.A. DE C.V."; 
            $employer = "C. DANIEL LEVY HANO";
        }

        //Trademarket 57
        if($postulant->company_id== 4){
            $company = "TRADE MARKET 57, S.A. DE C.V."; 
            $employer = "C. MÓNICA REYES RESENDIZ";
        } 

        //Unipromtex
        if($postulant->company_id== 5){
            $company = "UNIPROMTEX S.A. DE C.V."; 
            $employer = "DAVID LEVY HANO";
        } 

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->getSettings()->setMirrorMargins(true);
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

        //Global styles
        $phpWord->setDefaultFontName('Georgia');
        $phpWord->setDefaultFontSize(12);
        
        //Font Styles
        $phpWord->setDefaultParagraphStyle(
            array(
                'align' => 'both',
                'lineHeight' => 1.0
            )
        );
        $titleStyle = array(
            'align' => 'both',
            'lineHeight' => 1.0,
            'bold' => false
        );
      
        $titleCenterBoldStyle = array(
            'lineHeight' => 1.0,
            'bold' => true
        ); 

        //Paragraph Styles
        $center = array(
            'align'=> 'center'
        );

        //Secctions
        $section = $phpWord->addSection();
        $htmlsection= new \PhpOffice\PhpWord\Shared\Html();

        //Setting page margins
        $phpWord->getSettings()->setMirrorMargins(false);
        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginTop(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));
        $sectionStyle->setMarginBottom(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));


        //Word Document
        $section1 = "<p>CONVENIO DE CONFIDENCIALIDAD QUE CELEBRAN POR UNA PARTE <b>$name $lastname</b> POR SU PROPIO DERECHO EN SU CARÁCTER DE <b>EMPLEADO</b> Y POR LA OTRA PARTE, <b>$company</b>, EN SU CARÁCTER DE EMPLEADOR AL TENOR DE LAS SIGUIENTES ANTECEDENTES DECLARACIONES Y CLAUSULAS:</p>";
        $htmlsection->addHtml($section, $section1);
       

        $section->addText(
            'DECLARACIONES',
            $titleCenterBoldStyle, $center
        );

        $section->addText(
            'En virtud de lo anterior las partes se reconocen mutuamente la personalidad con la que '.
            'se ostentan por lo que están de acuerdo en sujetarse al tenor de las siguientes:',
            $titleStyle,
        );

        $section->addText(
            'CLAUSULAS',
            $titleCenterBoldStyle, $center
        );
        
        $section2 = "<p><b>PRIMERA.- OBJETO: </b>El empleado se obliga por medio del presente instrumento a no divulgar la información así como a preservar y proteger la naturaleza confidencial de dicha Información Confidencial y, en consecuencia, no la revelará a terceros, sin el consentimiento previo y por escrito del EMPLEADOR.</p>";
        $htmlsection->addHtml($section, $section2);

        $section->addText(
            'El empleado utilizará la información Confidencial únicamente para cumplir sus obligaciones '.
            'o para ejercer sus derechos en virtud del Contrato señalando en antecedentes y revelará la '.
            'Información Confidencial exclusivamente a aquellos miembros de su organización que necesiten '. 
            'conocerla para ejecutar sus funciones con previo consentimiento por escrito del EMPLEADOR y de '. 
            'ninguna manera la aprovechará en beneficio propio o en contra de los objetivos señalados en el '.
            'contrato señalado en antecedentes.',
            $titleStyle,
        );

        $section3 = "<p><b>SEGUNDA.- DEFINICION DE CONFIDENCIALIDAD: </b>Para los efectos de presente instrumento, significa información confidencial toda información o materiales proporcionados por el EMPLEADOR, así como cualquier otra información de la que se tenga conocimiento durante la vigencia individual de trabajo.</p>";
        $htmlsection->addHtml($section, $section3);

        $section4 = "<p><b>TERCERA.- RESTRICCIONES A LA REVELACION Y EL USO: </b>Queda estrictamente prohibido al empleado:</p>";
        $htmlsection->addHtml($section, $section4);

        $section->addListItem('1. Revelar cualquier Información Confidencial a tercero alguno;', 0, null, 'multilevel');
        $section->addListItem('2. Utilizar de cualquier modo la Información Confidencial, excepto para la realización del fin anteriormente señalado, o', 0, null, 'multilevel');
        $section->addListItem('3. Ofrecer la Información Confidencial a cualquiera de sus compañeros de trabajo, o consultores, excepto aquellos que hayan firmado un acuerdo con estipulaciones relativas a la revelación y uso de la información similares a las establecidas en el presente convenio y que tengan “necesidad de conocer” tal información para la realización del mencionado fin.', 0, null, 'multilevel');
        $section->addListItem('4. Cualquier otra obligación a su cargo, establecida en el presente convenio.', 0, null, 'multilevel');
        $section->addListItem('El empleado deberá aplicar los mismos criterios de precaución dedicados a su propia información y materiales de naturaleza similar, que en todo caso deberán corresponder a una precaución razonable.', 0, null, 'multilevel');

        $section5 = "<p><b>CUARTA.- VIGENCIA DE OBLIGACION DE CONFIDENCIALIDAD: </b>Este convenio regirá la revelación de información entre las partes durante la vigencia del contrato señalado en antecedentes, así como por un plazo de cinco años posteriores a la terminación de la vigencia.</p>";
        $htmlsection->addHtml($section, $section5);


        $section5 = "<p><b>QUINTA.- PROHIBICION DE REPRODUCCION: </b>En empleado no creará, ni producirá por escrito, comunicados de ningún tipo, materiales impresos de marketing u otras publicaciones o anuncios relacionados en forma alguna con el presente convenio, ni autorizará o colaborará con un tercero en la creación o producción de los mismos, sin el consentimiento previo por escrito del EMPLEADOR.</p>";
        $htmlsection->addHtml($section, $section5);

        $section6 = "<p><b>SEXTA.- CONFIDENCIALIDAD DEL PRESENTE CONVENIO: </b>Consentimiento que podrá ser negado sin motivos razonables. Los términos del presente Contrato y sus Anexos correspondientes, aunque no su existencia, constituyen Información Confidencial.</p>";
        $htmlsection->addHtml($section, $section6);

        $section7 = "<p><b>SEPTIMA.- INDEMNIZACION: EL EMPLEADO </b>indemnizará a EL EMPLEADOR contra toda pérdida, gastos, daños, honorarios, peticiones, fuga de información de clientes actuales y demandas que pudiesen surgir de o en relación con el incumplimiento del presente convenio.</p>";
        $htmlsection->addHtml($section, $section7);

        $section->addText(
            'El empleado no podrá contactar en un periodo de 5 años a los clientes actuales del '.
            'patrón, de hacerlo se hará acreedor a una indemnización por daños que pudiera ocasionar '.
            'ante las autoridades correspondientes. ',
            $titleStyle,
        );

        $section8 = "<p><b>OCTAVA.- PARTE INTEGRANTE: </b>El presente convenio forma parte del contrato celebrado entre EL EMPLEADO y EL EMPLEADOR señalado en el capítulo de antecedentes. Por lo que el incumplimiento a cualquier obligación establecido en el mismo y en el presente convenio, será causa de rescisión inmediata del contrato antes señalado, sin necesidad de declaración judicial.</p>";
        $htmlsection->addHtml($section, $section8);

        $section9 = "<p><b>NOVENA.- PROHIBICION DE CESION DE OBLIGACIONES: </b>El presente convenio no podrá ser cedido, ni ninguno de los derechos ni obligaciones derivados del mismo, salvo que esta obtenga previamente el consentimiento escrito del EMPLEADOR, la cual podrá establecer restricciones o limitaciones en relación con dichas cesaciones posteriores a dicha notificación.</p>";
        $htmlsection->addHtml($section, $section9);

        $section->addText(
            'Las Partes expresamente convienen en que EMPLEADOR podrá ceder el presente instrumento en '.
            'cualquier momento y por cualquier causa.',
            $titleStyle,
        );

        $section10 = "<p><b>DECIMA PRIMERA.- NOTIFICACIONES: </b>Cualquier aviso o notificación que las partes tuvieran que hacer la una a la otra, será efectuada en los domicilios que se señalan en las Declaraciones <b>I</b> y <b>II</b> del presente convenio.</p>";
        $htmlsection->addHtml($section, $section10);

        $section->addText(
            'Cualquiera de las partes podrá, de tiempo en tiempo, señalar como su domicilio '.
            'para los fines de este acuerdo cualquier otro domicilio, previo aviso por escrito '. 
            'al respecto entregado con diez (10) días calendario de anticipación del cambio a la otra parte.',
            $titleStyle,
        );

        $section11 = "<p><b>DECIMA SEGUNDA.- AUSENCIA DE VICIOS: </b>Previa lectura del presente convenio de las partes que intervienen del mismo, éstas manifiestan que en el mismo no existe ignorancia, dolo, incapacidad, lesión ni cualquier otro vicio del consentimiento que pueda afectar su validez.</p>";
        $htmlsection->addHtml($section, $section11);

        $section12 = "<p><b>DECIMA TERCERA.- ENCABEZADOS Y TITULOS: </b>Los encabezamientos y títulos que se utilizan en este convenio son únicamente por conveniencia y no se considerarán de naturaleza sustancial para interpretación del mismo.</p>";
        $htmlsection->addHtml($section, $section12);

        $section13 = "<p><b>DECIMA CUARTA.- JURISDICCION: </b>Para todo lo que se refiere a la interpretación y cumplimiento del presente convenio, “LAS PARTES” se someten expresamente a la jurisdicción y competencia de las Leyes y Tribunales del Estado de México renunciando expresamente a cualquier otro fuero que por razones de su domicilio presente o futuro pudiera corresponderles.</p>";
        $htmlsection->addHtml($section, $section13);

        $section14 = "<p>Leído el presente contrato y enteradas las partes de su contenido y alcance, lo firman en dos tantos de plena conformidad, en la Estado de México, el día <b>$date_admission.</b></p>";
        $htmlsection->addHtml($section, $section14);

        $section15 = "<p></p>";
        $htmlsection->addHtml($section, $section15);

        $cellRowSpan = array('width' => 5000);
     
        $table = $section->addTable([]);
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('EMPLEADOR',$titleCenterBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('EMPLEADO',$titleCenterBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText($company,$titleCenterBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$titleCenterBoldStyle, $center);
       
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$titleCenterBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$titleCenterBoldStyle, $center);
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$titleCenterBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$titleCenterBoldStyle, $center);
     

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText($employer,$titleCenterBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('C. '. $name. ' '. $lastname,$titleCenterBoldStyle, $center);
       

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'CONVENIO DE CONFIDENCIALIDAD ' . ' ' . strtoupper($name) .' '. strtoupper($lastname) . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
    }

}
