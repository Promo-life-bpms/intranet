<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\Element\Footer;
use PhpOffice\PhpWord\Element\Header;
use PhpOffice\PhpWord\Style\Language;

class LetterForBank extends Controller
{
    public function letterForBank($postulant )
    {
        $social_reason = "";
        $name = strtoupper($postulant->name);
        $lastname = strtoupper($postulant->lastname); 
        $rfc = strtoupper($postulant->rfc);
        $id_credential = strtoupper($postulant->id_credential);
        $date_admission = date('d/m/Y', strtotime($postulant->date_admission));
        $header_img = '';
        $footer_img = '';
        
        //Sections nad config 
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));
      
        $section = $phpWord->addSection();
        $htmlsection= new \PhpOffice\PhpWord\Shared\Html();
        $header = $section->addHeader(Header::FIRST);
        $footer = $section->addFooter(Footer::FIRST);
        
        //Setting page margins
        $phpWord->getSettings()->setMirrorMargins(false);
        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginTop(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));
        $sectionStyle->setMarginBottom(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));

         //Promolife
         if($postulant->company_id == 1){
            $social_reason = "PROMO LIFE, S. DE R.L. DE C.V.";
            $footerText = "La empresa PROMO LIFE, S DE RL DE CV con domicilio ubicado en SAN ANDRES ATOTO #155, PISO 1 LOCAL B, CP.53550, COL.UNIDAD SAN ESTEBAN, NAUCALPAN DE JUAREZ, ESTADO DE MEXICO    ";

            $header_img = public_path('img\pl_header.png');

            $headerImageStyle = array(
                'positioning' => 'absolute',
                'marginLeft' => 0,
                'marginTop' => +60,
                'width' => 160,
                'wrappingStyle' => 'behind',
                'posHorizontal' => 'absolute',
                'posVertical' => 'absolute',
            );

            $header->addImage($header_img, $headerImageStyle);
        }

        //BH tardemarket
        if($postulant->company_id == 2){
            $social_reason = "BH TRADE MARKET, S.A. DE C.V.";
            $footerText = "LA EMPRESA BH TRADE MARKET S.A. DE C.V.  con domicilio ubicado en SAN ANDRES ATOTO #155, PISO 1 LOCAL A, CP.53550, COL.UNIDAD SAN ESTEBAN, NAUCALPAN DE JUAREZ, ESTADO DE MEXICO. 52909100";
            
            $header_img = public_path('img\bh_header.png');
            $footer_img = public_path('img\bh_footer.png');

            $headerImageStyle = array(
                'positioning' => 'absolute',
                'marginLeft' => -85,
                'marginTop' => -36,
                'width' => 596,
                'wrappingStyle' => 'behind',
                'posHorizontal' => 'absolute',
                'posVertical' => 'absolute',
            );
    
            $footerImageStyle = array(
                'positioning' => 'absolute',
                'marginLeft' => -85,
                'marginTop' => -214,
                'width' => 266,
                'wrappingStyle' => 'behind',
                'posHorizontal' => 'absolute',
                'posVertical' => 'absolute',
            );

            $header->addImage($header_img, $headerImageStyle);
            $footer->addImage($footer_img, $footerImageStyle ); 
        }

        //Promo zale
        if($postulant->company_id == 3){
            $social_reason = "PROMO ZALE S.A. DE C.V."; 
            $footerText = "La empresa PROMO ZALE SA DE CV con domicilio ubicado en SAN ANDRES ATOTO #155, PISO 1 LOCAL E, CP.53550, COL.UNIDAD SAN ESTEBAN, NAUCALPAN DE JUAREZ, ESTADO DE MEXICO    ";

            $header_img = public_path('img\pz_header.png');
            $footer_img = public_path('img\pz_footer.png');

            $headerImageStyle = array(
                'positioning' => 'absolute',
                'marginLeft' => -85,
                'marginTop' => -36,
                'width' => 596,
                'wrappingStyle' => 'behind',
                'posHorizontal' => 'absolute',
                'posVertical' => 'absolute',
            );
    
            $footerImageStyle = array(
                'positioning' => 'absolute',
                'marginLeft' => -85,
                'marginTop' => -119,
                'width' => 440,
                'wrappingStyle' => 'behind',
                'posHorizontal' => 'absolute',
                'posVertical' => 'absolute',
            );

            $header->addImage($header_img, $headerImageStyle);
            $footer->addImage($footer_img, $footerImageStyle );
        }

        //Trademarket 57
        if($postulant->company_id== 4){
            $social_reason = "TRADE MARKET 57, S.A. DE C.V."; 
            $footerText = "LA EMPRESA TRADE MARKET 57 S.A. DE C.V.  con domicilio ubicado en SAN ANDRES ATOTO #155, PLANTA BAJA, CP.53550, COL.UNIDAD SAN ESTEBAN, NAUCALPAN DE JUAREZ, ESTADO DE MEXICO";

            $header_img = public_path('img\tm57_header.png');
            $footer_img = public_path('img\tm57_footer.png');

            $headerImageStyle = array(
                'positioning' => 'absolute',
                'marginLeft' => -85,
                'marginTop' => -36,
                'width' => 596,
                'wrappingStyle' => 'behind',
                'posHorizontal' => 'absolute',
                'posVertical' => 'absolute',
            );
    
            $footerImageStyle = array(
                'positioning' => 'absolute',
                'marginLeft' => -85,
                'marginTop' => -70,
                'width' => 596,
                'wrappingStyle' => 'behind',
                'posHorizontal' => 'absolute',
                'posVertical' => 'absolute',
            );

            $header->addImage($header_img, $headerImageStyle);
            $footer->addImage($footer_img, $footerImageStyle );
        } 

        //Unipromtex
        if($postulant->company_id== 5){
            $social_reason = "UNIPROMTEX S.A. DE C.V."; 
            $footerText = "UNIPROMTEX, S.A. DE C.V. con domicilio ubicado en, CIELITO LINDO 18B, COL. PARQUE INDUSTRIAL IZCALLI, NEZAHUALCÓYOTL, ESTADO DE MÉXICO, C.P. 57810.";
        
            $header_img = public_path('img\up_header.png');

            $headerImageStyle = array(
                'positioning' => 'absolute',
                'marginLeft' => 0,
                'marginTop' => +20,
                'width' => 140,
                'wrappingStyle' => 'behind',
                'posHorizontal' => 'absolute',
                'posVertical' => 'absolute',
            );

            $header->addImage($header_img, $headerImageStyle);
        } 

        //Font styles
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);

        $phpWord->setDefaultParagraphStyle(
            array(
                'align' => 'both',
                'lineHeight' => 1.0
            )
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
      
        $center = array(
            'align'=> 'center'
        );
        $left = array(
            'align'=> 'left'
        );
        $right = array(
            'align'=> 'right',
        );
        
        $section->addText('');
        $section->addText('');
        $section->addText('');
        $section->addText('');
        $section->addText('');
            
        $section->addText(
            'Naucalpan de Juárez, Estado de México a ' .  $date_admission,
            $bodyNormalStyle, $right
        );        
   
        $section->addText('');
        $section->addText('');
        
        $section->addText(
            'BBVA Bancomer, S.A.<w:br/>Banca de Empresas<w:br/>Ejecutivo de Cuenta,<w:br/>Presente,',
            $bodyRightBoldStyle, $left
        );
  
        $section->addText('');
        $section2 = "<p>Por este conducto le solicito amablemente puedan otorgarle una tarjeta de nómina a <b>$name $lastname </b> que se identifica con su credencial de Elector <b>$id_credential</b> y RFC <b><u>$rfc</u></b> , ya que está dado(a) de alta en la empresa $social_reason</p>";
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
