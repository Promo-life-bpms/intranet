<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\Element\Header;
use PhpOffice\PhpWord\Style\Language;

class LetterForBank extends Controller
{
    public function letterForBank($postulant,$postulant_details, $company_id)
    {
        $social_reason = "";
        $employer = "";
        $name = strtoupper($postulant->name);
        $lastname = strtoupper($postulant->lastname); 
        $nacionality = " ";  
        $civil_status = strtoupper($postulant_details->civil_status) ;
        $domicile = strtoupper($postulant_details->address) ;
        $age = $postulant_details->age;
        $curp = strtoupper($postulant_details->curp);
        $rfc = strtoupper($postulant_details->rfc);
        $position = strtoupper($postulant_details->position);
        $date_admission = date('d/m/Y', strtotime($postulant_details->date_admission));


         //Promolife
         if($company_id == 1){
            $social_reason = "PROMO LIFE, S. DE R.L. DE C.V.";
            $employer = "C. RAÚL TORRES MÁRQUEZ";
            $footerText = "La empresa PROMO LIFE, S DE RL DE CV con domicilio ubicado en SAN ANDRES ATOTO #155, PISO 1 LOCAL B, CP.53550, COL.UNIDAD SAN ESTEBAN, NAUCALPAN DE JUAREZ, ESTADO DE MEXICO    ";

        }

        //BH tardemarket
        if($company_id == 2){
            $social_reason = "BH TRADE MARKET, S.A. DE C.V.";
            $employer = "C. DAVID LEVY HANO";
            $footerText = "LA EMPRESA BH TRADE MARKET S.A. DE C.V.  con domicilio ubicado en SAN ANDRES ATOTO #155, PISO 1 LOCAL A, CP.53550, COL.UNIDAD SAN ESTEBAN, NAUCALPAN DE JUAREZ, ESTADO DE MEXICO. 52909100";

        }

        //Promo zale
        if($company_id == 3){
            $social_reason = "PROMO ZALE S.A. DE C.V."; 
            $employer = "C. DANIEL LEVY HANO";
            $footerText = "La empresa PROMO ZALE SA DE CV con domicilio ubicado en SAN ANDRES ATOTO #155, PISO 1 LOCAL E, CP.53550, COL.UNIDAD SAN ESTEBAN, NAUCALPAN DE JUAREZ, ESTADO DE MEXICO    ";

        }

        //Trademarket 57
        if($company_id== 4){
            $social_reason = "TRADE MARKET 57, S.A. DE C.V."; 
            $employer = "C. MÓNICA REYES RESENDIZ";
            $footerText = "LA EMPRESA TRADE MARKET 57 S.A. DE C.V.  con domicilio ubicado en SAN ANDRES ATOTO #155, PLANTA BAJA, CP.53550, COL.UNIDAD SAN ESTEBAN, NAUCALPAN DE JUAREZ, ESTADO DE MEXICO";

        } 

        //Unipromtex
        if($company_id== 5){
            $social_reason = "UNIPROMTEX S.A. DE C.V."; 
            $employer = "DAVID LEVY HANO";
            $footerText = "UNIPROMTEX, S.A. DE C.V. con domicilio ubicado en, CIELITO LINDO 18B, COL. PARQUE INDUSTRIAL IZCALLI, NEZAHUALCÓYOTL, ESTADO DE MÉXICO, C.P. 57810.";
        } 


        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

        //Font name
        $TextStyle = 'Arial';

        //Font styles
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);

        $phpWord->setDefaultParagraphStyle(
            array(
                'align' => 'both',
                'lineHeight' => 1.0
            )
        );
        
        $rightText =  array(
            'align' => 'right',
            'lineHeight' => 1.0
        );

        $footerStyleText =  array(
            'align' => 'both',
            'lineHeight' => 1.5,
            'size' => 7, 
            'bold' => false,
        );

        $bodyNormalStyle = array(
            'lineHeight' => 1.0,
            'bold' => false,
        ); 

        $bodyCenterBoldStyle = array(
            'lineHeight' => 1.0,
            'bold' => true,
        ); 

        $bodyRightBoldStyle = array(
            'lineHeight' => 1.0,
            'bold' => true,
        );

        $headerImageStyle = array(
            'positioning' => 'absolute',
            'marginLeft' => -72,
            'marginTop' => -72,
            'width' => 595,
            'wrappingStyle' => 'behind',
            'posHorizontal' => 'absolute',
            'posVertical' => 'absolute',
        );

        $center = array(
            'align'=> 'center'
        );
        $left = array(
            'align'=> 'left'
        );
        $right = array(
            'align'=> 'right',
        );
        //create section component
        $section = $phpWord->addSection();
        $htmlsection= new \PhpOffice\PhpWord\Shared\Html();
        $header = $section->addHeader(Header::FIRST);
        $bh_header = public_path('img\bh_header.png');


        $section->addImage($bh_header,  $headerImageStyle);

        $section->addText('');

            
        $section->addText(
            'Naucalpan de Juárez, Estado de México a ' .$date_admission  ,
            $bodyNormalStyle, $right
        );

        
   
        $section->addText('');
        $section->addText('');
        $section->addText(
            'BBVA Bancomer, S.A.<w:br/>Banca de Empresas<w:br/>Ejecutivo de Cuenta,<w:br/>Presente,',
            $bodyRightBoldStyle, $left
        );
  
        $section->addText('');
        $section2 = "<p>Por este conducto le solicito amablemente puedan otorgarle una tarjeta de nómina a <b>$name $lastname </b> que se identifica con su credencial de Elector <b>ID CREDENCIAL ELECTOR</b> y RFC <b><u>$rfc</u></b> , ya que está dado(a) de alta en la empresa $social_reason</p>";
        $htmlsection->addHtml($section, $section2);
        $section->addText('');
        
        $section2 = "<p>Sin más por el momento y en espera de vernos favorecidos con mi solicitud, quedo de usted.</p>";
        $htmlsection->addHtml($section, $section2);

        $section->addText('');

        $section->addText(
            'A T E N T A M E N T E',
            $bodyCenterBoldStyle, $center
        );

        $section->addText('');

        $section->addText(
            '_____________________
            <w:br/>LIC. DENISSE ADRIANA MURILLO MAYEN
            <w:br/>RECURSOS HUMANOS<w:br/>
            <w:br/>TEL (55)62690017',
            $bodyCenterBoldStyle, $center
        );

        $section->addText('');
        $section->addText('');
        $section->addText('');
        $section->addText('');
        $section->addText('');
        $section->addText('');

        $section->addText(
            $footerText, $footerStyleText
        );

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'CARTA DE BANCO ' . strtoupper($name) .' '. strtoupper($lastname) . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");

    }
}
