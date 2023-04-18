<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use PhpOffice\PhpWord\Style\Language;

class IndeterminateContract extends Controller
{
    public function indeterminateContract($postulant )
    {
        $company = "";
        $employer = "";
        $name = strtoupper($postulant->name);
        $lastname = strtoupper($postulant->lastname); 
        $nacionality = strtoupper($postulant->nacionality);  
        $civil_status = strtoupper($postulant->civil_status) ;
        $domicile = strtoupper($postulant->full_address) ;
        $age = $postulant->age;
        $curp = strtoupper($postulant->curp);
        $position = strtoupper($postulant->position);
        $position_objetive =  strtoupper($postulant->position_objetive);
        $next_sign_date = date('d,m,Y',strtotime('+3 months', strtotime($postulant->date_admission)));
        $daily_salary = strtoupper($postulant->daily_salary); 
        $daily_salary_letter = strtoupper($postulant->daily_salary_letter); 
     
       
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->getSettings()->setMirrorMargins(true);
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

        //Global styles
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(8);
        
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
            'bold' => true,
            'size' => 20,
        );
        
        $titleCenterBoldStyle2 = array(
            'lineHeight' => 1.0,
            'bold' => true,
            'size' => 26,
        ); 

        $bodyCenterBoldStyle = array(
            'lineHeight' => 1.0,
            'bold' => true,
        ); 

        $bodyBoldUnderlineStyle = array(
            'bold' => true,
        ); 
        //Paragraph Styles
        $centerTitle = array(
            'size' => 20,
            'align'=> 'center'
        );

        $center = array(
            'align'=> 'center'
        );

        $multilevelListStyleName = 'multilevel';

        $phpWord->addNumberingStyle(
            $multilevelListStyleName,
            [
                'type' => 'multilevel',
                'levels' => [
                    ['format' => 'upperRoman', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360],
                    ['format' => 'lowerLetter', 'text' => '%2)', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
                    ['format' => 'decimal', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
                    ['format' => 'lowerRoman', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],

                ],
            ]
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

        //Promolife
        if($postulant->company_id == 1){
            $company = "PROMO LIFE, S. DE R.L. DE C.V.";
            $employer = "C. RAÚL TORRES MÁRQUEZ";
            $section1 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO INDETERMINADO</b> QUE CELEBRAN POR UNA PARTE PROMO LIFE, S. DE R.L. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. RAUL TORRES MARQUEZ, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PISO 1 LOCAL B, COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            $sectionI = "<p>I.- LA EMPRESA DECLARA SER UNA SOCIEDAD MERCANTIL CONSTITUIDA ANTE LAS LEYES MEXICANAS, CON DOMICILIO FISCAL EN LA CALLE DE SAN ANDRES ATOTO No. 155 PISO 1 LOCAL B, COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, QUE SU OBJETIVO SOCIAL OTROS SERVICIOS DE PUBLICIDAD </p>";

            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        
        }

        //BH tardemarket
        if($postulant->company_id == 2){
            $company = "BH TRADE MARKET, S.A. DE C.V.";
            $employer = "C. DAVID LEVY HANO";
            $section1 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO INDETERMINADO</b> QUE CELEBRAN POR UNA PARTE BH TRADE MARKET, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. DAVID LEVY HANO, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PISO 1 LOCAL B COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            $sectionI = "<p>I.- LA EMPRESA DECLARA SER UNA SOCIEDAD MERCANTIL CONSTITUIDA ANTE LAS LEYES MEXICANAS, CON DOMICILIO FISCAL EN LA CALLE DE SAN ANDRES ATOTO No. 155 PISO 1 LOCAL A COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, QUE SU OBJETIVO SOCIAL OTROS SERVICIOS DE PUBLICIDAD.</p>";

            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        }

        //Promo zale
        if($postulant->company_id == 3){
            $company = "PROMO ZALE S.A. DE C.V."; 
            $employer = "C. DANIEL LEVY HANO";
            $section1 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO INDETERMINADO</b> QUE CELEBRAN POR UNA PARTE PROMO ZALE, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. DANIEL LEVY HANO, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PISO 1 LOCAL E COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            $sectionI = "<p>I.- LA EMPRESA DECLARA SER UNA SOCIEDAD MERCANTIL CONSTITUIDA ANTE LAS LEYES MEXICANAS, CON DOMICILIO FISCAL EN LA CALLE DE SAN ANDRES ATOTO No. 155 PISO 1 LOCAL E COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, QUE SU OBJETIVO SOCIAL OTROS SERVICIOS DE PUBLICIADAD.</p>";
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        }

        //Trademarket 57
        if($postulant->company_id== 4){
            $company = "TRADE MARKET 57, S.A. DE C.V."; 
            $employer = "C. MÓNICA REYES RESENDIZ";
            $section1 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO INDETERMINADO</b> QUE CELEBRAN POR UNA PARTE TRADE MARKET 57, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. MÓNICA REYES RESENDIZ, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PLANTA BAJA, COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            $sectionI = "<p>I.- LA EMPRESA DECLARA SER UNA SOCIEDAD MERCANTIL CONSTITUIDA ANTE LAS LEYES MEXICANAS, CON DOMICILIO FISCAL EN LA CALLE DE SAN ANDRES ATOTO  No. 155 PLANTA BAJA, COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, QUE SU OBJETIVO SOCIAL ES OTROS SERVICIOS DE PUBLICIDAD. </p>";
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        } 

        //Unipromtex
        if($postulant->company_id== 5){
            $company = "UNIPROMTEX S.A. DE C.V."; 
            $employer = "DAVID LEVY HANO";
            $section1 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO DETERMINADO</b> QUE CELEBRAN POR UNA PARTE UNIPROMTEX, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. DAVID LEVY HANO, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN C. CIELITO LINDO 18 B, PARQUE INDUSTRIAL IZCALLI, NEZAHUALCOYOTL ESTADO DE MÉXICO. C.P. 57810 A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            $sectionI = "<p>I.- LA EMPRESA DECLARA SER UNA SOCIEDAD MERCANTIL CONSTITUIDA ANTE LAS LEYES MEXICANAS, CON DOMICILIO FISCAL EN C. CIELITO LINDO 18 B, PARQUE INDUSTRIAL IZCALLI, NEZAHUALCOYOTL ESTADO DE MÉXICO. C.P. 57810.</p>";
            $section->addText(
                $company,
                $titleCenterBoldStyle2, $centerTitle
            );
        } 

        $htmlsection->addHtml($section, $section1);

        $cellRowSpan = array(
            'width' => 5000
        );

        $cellRowSpan1 = array(
            'width' => 5000,
            'borderBottomColor' =>'000000',
            'borderBottomSize' => 1,
            'marginBottom' =>0
        );
        
        $table = $section->addTable();
        $table->addRow();
        $table->addCell(4000, $cellRowSpan)->addText('<w:br/>'.'EL (SR.) LA (SRA.) (SRITA.):',null,['contextualSpacing'=> true]);
        $table->addCell(6000, $cellRowSpan1 )->addText('<w:br/>'.$name . ' ' .$lastname,$bodyBoldUnderlineStyle, ['contextualSpacing'=> true]);
       
        $table->addRow();
        $table->addCell(4000, $cellRowSpan)->addText('<w:br/>'.'DE NACIONALIDAD:',null,['contextualSpacing'=> true]);
        $table->addCell(6000, $cellRowSpan1 )->addText('<w:br/>'.$nacionality,$bodyBoldUnderlineStyle, ['contextualSpacing'=> true]);

        $table->addRow();
        $table->addCell(4000, $cellRowSpan)->addText('<w:br/>'.'ESTADO CIVIL:',null,['contextualSpacing'=> true]);
        $table->addCell(6000, $cellRowSpan1 )->addText('<w:br/>'.$civil_status,$bodyBoldUnderlineStyle, ['contextualSpacing'=> true]);

        $table->addRow();
        $table->addCell(4000, $cellRowSpan)->addText('<w:br/>'.'DOMICILIO:',null,['contextualSpacing'=> true]);
        $table->addCell(6000, $cellRowSpan1 )->addText('<w:br/>'.$domicile,$bodyBoldUnderlineStyle, ['contextualSpacing'=> true]);

        $table->addRow();
        $table->addCell(4000, $cellRowSpan)->addText('<w:br/>'.'AÑOS DE EDAD:',null,['contextualSpacing'=> true]);
        $table->addCell(6000, $cellRowSpan1 )->addText('<w:br/>'.$age,$bodyBoldUnderlineStyle, ['contextualSpacing'=> true]);

        $table->addRow();
        $table->addCell(4000, $cellRowSpan)->addText('<w:br/>'.'CURP:',null,['contextualSpacing'=> true]);
        $table->addCell(6000, $cellRowSpan1 )->addText('<w:br/>'.$curp,$bodyBoldUnderlineStyle, ['contextualSpacing'=> true]);
        
        $section->addText('');

        $section->addText(
            'A QUIEN EN LO SUCESIVO SE LE DENOMINARA EL (LA) EMPLEADO (A).',
            $titleStyle,
        );

        $section->addText(
            'EL PRESENTE CONTRATO SE CELEBRARÁ DE ACUERDO CON LAS DECLARACIONES Y CLAUSULAS SIGUIENTES:',
            $titleStyle,
        );

        $section->addText(
            'D E C L A R A C I O N E S',
            $bodyCenterBoldStyle, $center
        );

        $htmlsection->addHtml($section, $sectionI);

        $section2 = "<p>II.- EL EMPLEADO POR SU PARTE DECLARA QUE QUEDA DEBIDAMENTE ENTERADO DE LA CAUSA QUE ORIGINA SU CONTRATACIÓN Y ESTA CONFORME EN PRESTAR SUS SERVICIOS PERSONALES A “LA EMPRESA” EN LOS TERMINOS QUE MAS ADELANTE PACTAN, MANIFESTANDO TENER LOS CONOCIMIENTOS SUFICIENTES PARA REALIZAR TAL <b>SERVICIO DE $position QUE CONSISTE EN $position_objetive.</b></p>";
        $htmlsection->addHtml($section, $section2);

        $section->addText('');

        $section->addText(
            'EN VIRTUD DE LO ANTERIOR, LAS PARTES OTORGAN LAS SIGUIENTES:',
            $titleStyle,
        );

        $section->addText(
            'C L A U S U L A S',
            $bodyCenterBoldStyle, $center
        );

        $section2 = "<p>PRIMERA.- “LA EMPRESA” CONTRATARA AL EMPLEADO PARA QUE LE PRESTE SUS SERVICIOS PERSONALES BAJO SU DIRECCIÓN Y DEPENDENCIA, CON EL CARÁCTER DE EMPLEADO <b>$position</b> Y TENDRA UN PERIODO <b>DE TIEMPO INDEFINIDO</b>.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>SEGUNDA.- EL LUGAR DE LA PRESTACIÓN DE SERVICIOS SERA TANTO EN EL DOMICILIO DE “LA EMPRESA”, ASI COMO EN EL DE TODAS AQUELLAS PERSONAS FÍSICAS O MORALES QUE CONTRATEN SERVICIOS CON “LA EMPRESA” SEA CUAL FUERE SU UBICACIÓN DENTRO DE LA REPUBLICA MEXICANA.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>TERCERA.- CONVIENEN LAS PARTES EXPRESAMENTE EN QUE EL PRESENTE CONTRATO INDIVIDUAL DE TRABAJO QUE CELEBRAN POR <b>TIEMPO INDETERMINADO</b> CONSISTE EN EL DESARROLLO DE LAS LABORES DEL EMPLEADO DE ESTA EMPRESA EN EL DOMICILIO QUE CORRESPONDEN CONFORME LA CLAUSULA SEGUNDA DE ESTE CONTRATO</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>CUARTA.- CONVIENEN LAS PARTES EN QUE EL EMPLEADO RECIBIRA COMO RETRIBUCIÓN DE SUS SERVICIOS  LA CANTIDAD DE <b>$$daily_salary ($daily_salary_letter 00/100 M.N.)</b> DIARIOS, ADICIONALMENTE EL TRABAJADOR RECIBIRA ADEMAS DE LAS PRESTACIONES DE LEY, LAS SIGUIENTES PRESTACIONES SIEMPRE Y CUANDO CUMPLA CON LOS REQUISITOS ESTABLECIDOS PARA OBTENERLAS ESTAS SON: UN 10% DE PREMIO DE PUNTUALIDAD; 10% PREMIO DE ASISTENCIA Y DESPENSA EN EFECTIVO LOS CUALES   LE SERAN PAGADOS EN MONEDA NACIONAL VIA TRANSFERENCIA ELECTRONICA A LA TARJETA DE NOMINA BANCOMER, LA CUAL LE SERA ASIGNADA EN EL MOMENTO DE SU CONTRATACION, LOS DIAS 15 Y ULTIMO DE CADA MES.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>QUINTA.- CONVIENEN LAS PARTES EN QUE POR CADA SEIS DIAS DE TRABAJO EL EMPLEADO DISFRUTARA DE UN DIA DE DESCANSO CON GOCE DE SALARIO INTEGRO CUBRIENDO 48 HORAS DE TRABAJO SEMANALES, YA SEA EN EL DOMICILIO DE LA EMPRESA O DONDE SE LE ASIGNE,  IGUALMENTE TENDRA DERECHO A DISFRUTAR DE SALARIOS EN LOS DIAS DE DESCANSO OBLIGATORIO QUE SEÑALA LA LEY FEDERAL DEL TRABAJO, CUANDO ESTOS OCURRAN DENTRO DEL TERMINO DE SU CONTRATACIÓN.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>SEXTA.- CONVIENEN LAS PARTES EN QUE POR LO QUE HACE A RIESGOS PROFESIONALES O ENFERMEDADES NO PROFESIONALES, SE SUJETARAN A LAS DISPOSICIONES QUE SOBRE EL PARTICULAR, ESTABLECE LA LEY DEL INSTITUTO MEXICANO DEL SEGURO SOCIAL</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>SÉPTIMA.- CONVIENEN LAS PARTES EN QUE INDEPENDIENTEMENTE DE LAS OBLIGACIONES QUE IMPONE AL EMPLEADO LA LEY FEDERAL DEL TRABAJO, SE OBLIGA A LO SIGUIENTE:</p>";
        $htmlsection->addHtml($section, $section2);

        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('A PRESTAR SUS SERVICIOS CON EL MAYOR INTERES, EFICIENCIA, ESMERO Y LA DEBIDA PRESENTACIÓN PERSONAL.',[]);

        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('A OBSERVAR LAS DISPOSICIONES QUE SOBRE HORARIOS DE TRABAJO EXISTAN.',[]);

        //Unipromtex
        if($postulant->company_id== 5){
            $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
            $listItemRun->addText('LA JORNADA DE TRABAJO SERA DE LUNES A VIERNES DE 9:00 A.M. A 6 P.M. HRS., Y LOS DÍAS SABADOS DE 9:00 A.M. A 2 P.M. HRS. DEBIENDO CUBRIR LAS 48 HORAS A LA SEMANA.',[]);
        }else{
            $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
            $listItemRun->addText('LA JORNADA DE TRABAJO SERA DE LUNES A JUEVES DE  8:00 A.M. A 5 P.M. HRS., Y LOS DÍAS VIERNES DE  8:30 A.M. A  5 P.M. HRS. DEBIENDO CUBRIR LAS 48 HORAS A LA SEMANA',[]);
        }

        $section2 = "<p>OCTAVA.- CONVIENEN LAS PARTES EN QUE EL EMPLEADO (TRABAJADOR), SERA CAPACITADO PARA EL DESEMPEÑO DE SUS LABORES EN VIRTUD DE QUE YA CUENTAN DE LEY CON LOS TERMINOS DE LOS PLANES Y PROGRAMAS DE CAPACITACION ESTABLECIDOS POR SU CONDUCTO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>NOVENA.- EL EMPLEADO RECIBIRA LAS VACACIONES, PRIMA VACACIONAL Y AGUINALDO QUE ESTABLECEN LOS ARTICULOS 76, 80 Y 87 DE LA LEY FEDERAL DEL TRABAJO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>DECIMA.- LAS PARTES CONVIENEN EN QUE LOS DERECHOS Y OBLIGACIONES QUE MUTUAMENTE LES CORRESPONDEN Y QUE NO HAYA SIDO OBJETO DE MENCION ESPECIFICA, SE SUJETARAN A LAS DISPOSICIONES DE LA LEY FEDERAL DEL TRABAJO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>DECIMA PRIMERA.- DERIVADO DEL ARTÍCULO   25 (FRACC. X) DE LA LEY FEDERAL DE TRABAJO, EL EMPLEADO DESIGNA EN ESTE ACTO A BENEFICIARIOS  PARA EFECTOS DE PAGO DE PRESTACIONES Y REMUNERACIONES QUE SE GENEREN POR CAUSA DE FALLECIMIENTO O DESAPARICIÓN A CAUSA DE UN DELITO.</p>";
        $htmlsection->addHtml($section, $section2);

        $cellRowSpan2 = array(
            'width' => 20000,
            'borderColor' =>'000000',
            'borderSize' => 5,
        );
        
        $table = $section->addTable();
        $table->addRow();
        $table->addCell(7000, $cellRowSpan2)->addText('NOMBRE DE BENEFICIARIO',$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('PARENTESCO' ,$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('PORCENTAJE' ,$bodyCenterBoldStyle, $center);
        $table->addCell(7000, $cellRowSpan2 )->addText('DIRECCION Y TELÉFONO' ,$bodyCenterBoldStyle, $center);

        $table->addRow();
        $table->addCell(7000, $cellRowSpan2)->addText('<w:br/>',$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);
        $table->addCell(7000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);

        $table->addRow();
        $table->addCell(7000, $cellRowSpan2)->addText('<w:br/>',$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);
        $table->addCell(7000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);

        $table->addRow();
        $table->addCell(7000, $cellRowSpan2)->addText('<w:br/>',$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);
        $table->addCell(7000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);

        $table->addRow();
        $table->addCell(7000, $cellRowSpan2)->addText('<w:br/>',$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);
        $table->addCell(3000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);
        $table->addCell(7000, $cellRowSpan2 )->addText('<w:br/>' ,$bodyCenterBoldStyle, $center);

        $section->addText('');

        $section2 = "<p>EL PRESENTE CONTRATO SE FIRMA POR DUPLICADO EN EL ESTADO DE MÉXICO, A LOS <b>$next_sign_date</b></p>";
        $htmlsection->addHtml($section, $section2);
        
        $section->addText('');

        $table = $section->addTable();
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('POR LA EMPREASA<w:br/>'.$company,$bodyCenterBoldStyle,$center);
        $table->addCell(5000, $cellRowSpan )->addText('EL(LA) EMPLEADO (A)<w:br/>',$bodyCenterBoldStyle, $center); 

        $section->addText('');


        $table = $section->addTable();
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('____________________________________<w:br/>'.$employer,$bodyCenterBoldStyle,$center);
        $table->addCell(5000, $cellRowSpan )->addText('____________________________________<w:br/>'.'C. '.$name. ' '. $lastname,$bodyCenterBoldStyle, $center);
       
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'CONTRATO INDETERMINADO ' . strtoupper($name) .' '. strtoupper($lastname) . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");  
    }
}
