<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use PhpOffice\PhpWord\Style\Language;

class EmployeeDown extends Controller
{
    public function employeeDown($name,$lastname, $company)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

        //Font name
        $TimesNewRomanStyle = 'Times New Roman';

        //Font styles
        $phpWord->addFontStyle(
            $TimesNewRomanStyle,
            array(
                'name' => 'Times New Roman', 
                'size' => 14, 
                'bold' => false,
                )
        );

        $phpWord->setDefaultParagraphStyle(
            array(
                'align' => 'both',
                'lineHeight' => 2.0
            )
        );
        
        //create section component
        $section = $phpWord->addSection();

        $section->addText(
            $company,
            $TimesNewRomanStyle
        );
        
        $section->addText(
            'Presente:',
            $TimesNewRomanStyle
        );

        $section->addText(
            '',
            $TimesNewRomanStyle
        );

        $section->addText(
            'Por medio de la presente, hago constar que con esta fecha y por así convenir a mis intereses, '. 
            'doy por terminado voluntariamente el trabajo que tenía con ustedes, y se da por concluida la '.
            'relación laboral que mantenía con la empresa '. $company,
            $TimesNewRomanStyle,
        );
        $section->addText(
            'Les manifiesto que no me adeudan ninguna cantidad por ningún concepto ya que durante el tiempo '.
            'que mantuvimos relaciones, me fueron pagadas todas las prestaciones que pactamos y las cantidades '.
            'a que tuve derecho, sin quedar ningún compromiso de por medio, y aprovecho la oportunidad para '.
            'agradecerles las atenciones que tuvieron para conmigo.',
            $TimesNewRomanStyle
        );

        $section->addText(
            'Queda vigente el convenio de confidencialidad que firmé con la Empresa el primer día de trabajo, '.
            'por lo que me sujeto a las cláusulas establecidas en él.',
            $TimesNewRomanStyle
        );

        $section->addText(
            'ATENTAMENTE',
            $TimesNewRomanStyle
        );

        $section->addText(
            '_____________________________________',
            $TimesNewRomanStyle
        );

        $section->addText(
            'C. ' . strtoupper($name)  .' '. strtoupper($lastname),
            $TimesNewRomanStyle
        );


        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'RENUNCIA ' . strtoupper($name) .' '. strtoupper($lastname) . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");

    }

}
