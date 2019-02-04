<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subsidio;
use App\Localidad;
use App\Departamento;
use App\Grupo;

class FonavisController extends Controller
{
    
    public function generateCodigo(){
        $secretkey=" ";
        for ($i = 0; $i<8; $i++) 
        {
            $secretkey .= mt_rand(0,9);   
        }
        return $secretkey;
    }
    
    public function generateDocx($id)
    {

        $postulante = Subsidio::where('CerNro', $id)->first();

        $CerNro = $postulante->CerNro;
        $CerNro = substr($CerNro, 0, strpos($CerNro, ' '));
        /*if ($postulante->CerMod == 'CH') {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/fonavis/template/chtemplate.docx'));
        }

        $favcolor = "red";*/

        switch ($postulante->CerMod) {
            case "CH":
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('/fonavis/template/chtemplate.docx'));
                break;
            case "blue":
                echo "Your favorite color is blue!";
                break;
            case "green":
                echo "Your favorite color is green!";
                break;
            default:
                //echo "Your favorite color is neither red, blue, nor green!";
                return "No existe platilla";
        }

        if ($postulante->CerPin == null) {
            $num=$this->generateCodigo();
            $postulante->CerPin = $num;
            $postulante->CerFecImp = date('Y-m-d');
            $postulante->CerFecSus = date('Y-m-d');
            $postulante->save();
        }else {
            $num=$postulante->CerPin;
        }
        

        $templateProcessor->setValue('CAMPO11', $postulante->CerposNom);

        $report = Grupo::where('NucCod', '=', $postulante->CerNucCod)
                ->where('GnuCod', '=', $postulante->CerGnuCod)
                ->first();
        $templateProcessor->setValue('CAMPO23', $report->GnuNom);

        if ($postulante->CerNucNom == 0) {
            $templateProcessor->setValue('CAMPO73', '');
            $templateProcessor->setValue('CAMPO74', '');
        } else {
            if ($postulante->CerEst == 6) {
                if ($postulante->CerRect2Nr == 0) {
                    $templateProcessor->setValue('CAMPO73', 'y rectificado por la Resolución Nº '.$postulante->CerRectNro.' de fecha '.date('d/m/Y', strtotime($postulante->CerRectFec)));
                    $templateProcessor->setValue('CAMPO74', '');
                } else {
                    $templateProcessor->setValue('CAMPO73', ', rectificados por Resolución Nº '.$postulante->CerRectNro.' de fecha '.
                    date('d/m/Y', strtotime($postulante->CerRectFec)).
                    ' y Resolución Nº '.
                    $postulante->CerRect2Nr.' de fecha '.date('d/m/Y', strtotime($postulante->CerRect2Fe)));
                    $templateProcessor->setValue('CAMPO74', '');
                }
                
            }  
        }

        switch ($postulante->CerPlzOrig) {
            case 6:
                $templateProcessor->setValue('CAMPO31', '6 meses (seis)');
                break;
            case 9:
                $templateProcessor->setValue('CAMPO31', '9 meses (nueve)');
                break;
            case 18:
                $templateProcessor->setValue('CAMPO31', '18 meses (diez y ocho)');
                break;
        }
        $templateProcessor->setValue('CAMPO32', date('d/m/Y', strtotime($postulante->CerVig)));
        $templateProcessor->setValue('CAMPO20', $postulante->CerNivCod);
        $templateProcessor->setValue('CAMPO21', $postulante->CerMonUSM);
        setlocale(LC_ALL,"es_ES");
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $templateProcessor->setValue('CAMPO27', 'Asunción, '.date('d', strtotime($postulante->CerFeRe)).' de '.$meses[date('m', strtotime($postulante->CerFeRe))-1].
        ' de '.date('Y', strtotime($postulante->CerFeRe)));
        if (empty($postulante->CerObsSub)) {
            $templateProcessor->setValue('CAMPO57', '');
        } else {
            $templateProcessor->setValue('CAMPO57', 'Observación: '.$postulante->CerObsSub);
        }
        if ($postulante->CerEst == 6) {
            $templateProcessor->setValue('CAMPO35', 'Certificado Rectificado, impreso en fecha '.date('d/m/Y'));
        } else {
            $templateProcessor->setValue('CAMPO35', '');
        }
        $templateProcessor->setValue('CAMPO17', $postulante->CerLla.'/'.$postulante->CerAno);
        $templateProcessor->setValue('CAMPO18', $postulante->CerLla);
        $templateProcessor->setValue('CAMPO30', date('d/m/Y', strtotime($postulante->CerReLFe)));

        $templateProcessor->setValue('CAMPO25', $postulante->CerNucNom);
        $templateProcessor->setValue('CAMPO26', $CerNro);
        if ($postulante->CerPosCod <= 150000 ) {
            $templateProcessor->setValue('CAMPO12', 'C.I./CARNET Nº '.$postulante->CerPosCod);
        } else {
            $templateProcessor->setValue('CAMPO12', 'C.I. Nº '.$postulante->CerPosCod);
        }

        if ($postulante->CerCoCI == 0 || strlen($postulante->CerCoNo) == 0 ) {
            
            $templateProcessor->setValue('CAMPO33', '');
            $templateProcessor->setValue('CAMPO33b', '');

        } else {

            if ($postulante->CerCoCI <= 150000 ) {
                //$templateProcessor->setValue('CAMPO33', 'y su cónyuge (pareja) '.$postulante->CerCoNo.', con C.I./CARNET Nº '.$postulante->CerCoCI);
            } else {
            $templateProcessor->setValue('CAMPO33', "y su cónyuge (pareja) ".$postulante->CerCoNo/*.', con C.I. Nº '.$postulante->CerCoCI*/);
            $templateProcessor->setValue('CAMPO33b', ", con C.I. Nº ".$postulante->CerCoCI);
                //$campo33=print_r('y su cónyuge (pareja) '.$postulante->CerCoNo.', con C.I. Nº '.$postulante->CerCoCI,true); 
            }
        }
        //$templateProcessor->setValue('CAMPO33', $campo33);
        $templateProcessor->setValue('CAMPO14', $postulante->CerResNro);
        $templateProcessor->setValue('CAMPO22', $postulante->CerUsm);
        if ($postulante->CerTipViv == '') {
            $templateProcessor->setValue('CAMPO53', 'VR-2D');
        } else {
            $templateProcessor->setValue('CAMPO53', $postulante->CerTipViv);
        }

        if ($postulante->CerSupViv <= 0) {
            $templateProcessor->setValue('CAMPO54', '43.50');
        } else {
            $templateProcessor->setValue('CAMPO54', $postulante->CerSupViv);
        }
        $ciudad = Localidad::find($postulante->CerCiuId);
        $templateProcessor->setValue('CAMPO42', $ciudad->CiuNom);

        $depto = Departamento::find($postulante->CerDptoId);
        $templateProcessor->setValue('CAMPO43', $depto->DptoNom);
        
        if ($postulante->CerIndert == '') {
            $templateProcessor->setValue('CAMPO55', '1061/15');
        } else {
            $templateProcessor->setValue('CAMPO55', $postulante->CerIdent);
        }
        

        $templateProcessor->setValue('CAMPO50', $postulante->CerIdent);
        $templateProcessor->setValue('CAMPO10', date('d/m/Y', strtotime($postulante->CerFeRe)));
        $templateProcessor->setValue('CAMPO56', date('d/m/Y'));
        //$templateProcessor->setValue('CAMPO12', $postulante->CerPosCod);
        \QrCode::format('png')->size(110)->margin(0)->generate($num,storage_path("/fonavis/impresion/".$CerNro."png"));
        $templateProcessor->setImg('macroNameImage', array(
            'src'  => storage_path("/fonavis/impresion/".$CerNro."png")//,
            //'size' => array( 130, 120 ) //px
        ));
        $templateProcessor->saveAs(storage_path("/fonavis/impresion/".$CerNro.".docx"));
        $word = new \COM("Word.Application") or die ("Could not initialise Object.");
        // set it to 1 to see the MS Word window (the actual opening of the document)
        $word->Visible = 0;
        // recommend to set to 0, disables alerts like "Do you want MS Word to be the default .. etc"
        $word->DisplayAlerts = 0;
        // open the word 2007-2013 document 
        $word->Documents->Open(storage_path("/fonavis/impresion/".$CerNro.".docx"));
        // save it as word 2003
        //$word->ActiveDocument->SaveAs('newdocument.doc');
        // convert word 2007-2013 to PDF
        $word->ActiveDocument->ExportAsFixedFormat(storage_path("/fonavis/impresion/".$CerNro.".pdf"), 17, false, 0, 0, 0, 0, 7, true, true, 2, true, true, false);
        // quit the Word process
        $word->Quit(false);
        // clean up
        unset($word);
        
        return response()->download(storage_path("/fonavis/impresion/".$CerNro.".pdf"));
        
    }
}
