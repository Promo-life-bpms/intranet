<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Style\Language;

class LetterForBank extends Controller
{
    public function letterForBank($postulant,$postulant_details, $company)
    {
        $company = "";
        $employer = "";
        $name = strtoupper($postulant->name);
        $lastname = strtoupper($postulant->lastname); 
        $nacionality = " ";  
        $civil_status = strtoupper($postulant_details->civil_status) ;
        $domicile = strtoupper($postulant_details->address) ;
        $age = $postulant_details->age;
        $curp = strtoupper($postulant_details->curp);
        $position = strtoupper($postulant_details->position);
        $date_admission = date('d/m/Y', strtotime($postulant_details->date_admission));

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

        $footerText =  array(
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
        $section2 = "<p>Por este conducto le solicito amablemente puedan otorgarle una tarjeta de nómina a <b>$name $lastname </b> que se identifica con su credencial de Elector <b>ID CREDENCIAL ELECTOR</b> y RFC ____________, ya que está dado(a) de alta en la empresa BH TRADE MARKET S.A. de C.V.</p>";
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
            'LA EMPRESA BH TRADE MARKET S.A. DE C.V.  con domicilio ubicado en SAN ANDRES ATOTO #155, PISO 1 LOCAL A, CP.53550, COL.UNIDAD SAN ESTEBAN, NAUCALPAN DE JUAREZ, ESTADO DE MEXICO. 52909100',
            $footerText,
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
