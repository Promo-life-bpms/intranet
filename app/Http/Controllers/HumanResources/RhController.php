<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\Postulant;
use App\Models\PostulantBeneficiary;
use App\Models\PostulantDetails;
use App\Models\User;
use App\Models\UserDownMotive;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;


class RhController extends Controller
{
    public function stadistics()
    {
        return view('rh.stadistics');
    }

    public function postulants()
    {  
        $postulants_data = [];
        $postulants = Postulant::all();
        
        foreach($postulants as $postulant){
            $company = Company::all()->where('id', $postulant->company_id)->last();
            $department = Department::all()->where('id', $postulant->department_id)->last();

            array_push($postulants_data, (object)[
                'id' => $postulant->id,
                'fullname' => $postulant->name. " ". $postulant->lastname,
                'mail' =>  $postulant->mail,
                'phone' => $postulant->phone,
                'cv' => $postulant->cv,
                'status' => $postulant->status,
                'company'=>$company->name_company,
                'department' => $department->name,
                'interview_date' => $postulant->interview_date,
            ]);

        } 
        return view('rh.postulants', compact('postulants_data'));  
    }

    public function dropUser()
    {
        $users = User::all()->where('status',1);
        return view('rh.drop-user', compact('users'));
    }

    public function dropDocumentation($user)
    {
        $user = User::all()->where('id',$user)->first();
        $companies = Company::all()->pluck('name_company', 'id' );
        $departments = Department::all()->pluck('name','id');
        $user_down_motive = UserDownMotive::all()->where('user_id',$user->id);

        return view('rh.drop-documentation', compact('user', 'companies', 'departments', 'user_down_motive'));
    }
    
    public function dropDeleteUser(Request $request)
    {
        DB::table('users')->where('id', intval($request->user) )->update(['status' => 2]); 

        return redirect()->action([RhController::class, 'dropUser'])->with('message', 'El usuario se ha dado de baja correctamente');
    }

    public function buildDownDocumentation(Request $request)
    {

        //Promolife
        if($request->company_id == 1){
            $company = "PROMO LIFE, S. DE R.L. DE C.V.";
            $this->employeeDown($request->name, $request->lastname,$company);
        }

        //BH tardemarket
        if($request->company_id == 2){
            $company = "BH TRADE MARKET, S.A. DE C.V.";
            $this->employeeDown($request->name, $request->lastname,$company);
        }

        //Promo zale
        if($request->company_id == 3){
            $company = "PROMO ZALE S.A. DE C.V."; 
            $this->employeeDown($request->name, $request->lastname,$company);
        }

        //Trademarket 57
        if($request->company_id == 4){
            $company = "TRADE MARKET 57, S.A. DE C.V."; 
            $this->employeeDown($request->name, $request->lastname,$company);
        } 

        //Unipromtex
        if($request->company_id == 5){
            $company = "UNIPROMTEX S.A. DE C.V."; 
            $this->employeeDown($request->name, $request->lastname,$company);
        } 

    }

    public function createMotiveDown(Request $request)
    {
        DB::table('users_down_motive')->where('user_id', intval($request->user_id))->delete();
        
        $create_user_motive = new UserDownMotive();
        $create_user_motive->user_id  = $request->user_id;
        $create_user_motive->growth_salary  = $request->growth_salary;
        $create_user_motive->growth_promotion  = $request->growth_promotion;
        $create_user_motive->growth_activity  = $request->growth_activity;
        $create_user_motive->climate_partnet  = $request->climate_partnet;
        $create_user_motive->climate_manager  = $request->climate_manager;
        $create_user_motive->climate_boss  = $request->climate_boss;
        $create_user_motive->psicosocial_workloads  = $request->psicosocial_workloads;
        $create_user_motive->psicosocial_appreciation	  = $request->psicosocial_appreciation	;
        $create_user_motive->psicosocial_violence  = $request->psicosocial_violence;
        $create_user_motive->psicosocial_workday  = $request->psicosocial_workday;
        $create_user_motive->demographics_distance  = $request->demographics_distance;
        $create_user_motive->demographics_physical  = $request->demographics_physical;
        $create_user_motive->demographics_personal  = $request->demographics_personal;
        $create_user_motive->demographics_school  = $request->demographics_school;
        $create_user_motive->health_personal  = $request->health_personal;
        $create_user_motive->health_familiar  = $request->health_familiar;
            
        $create_user_motive->save();

        return redirect()->back()->with('message', 'Motivo de baja guardado satisfactoriamente');
    }

    public function createPostulant()
    {
        $companies = Company::all()->pluck('name_company', 'id');
        $departments = Department::all()->pluck('name','id');
        return view('rh.create-postulant', compact('companies','departments'));
    }

    public function storePostulant(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'status' => 'required',
            'mail' => 'required',
            'phone' => 'required',
        ]);

        $cv = null;

        if ($request->hasFile('cv')) {
            $filenameWithExt = $request->file('cv')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cv')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
            $cv = $request->file('cv')->move('storage/postulant/', $fileNameToStore);
        }

        $create_postulant = new Postulant();
        $create_postulant->name  = $request->name;
        $create_postulant->lastname  = $request->lastname;
        $create_postulant->mail  = $request->mail;
        $create_postulant->phone  = $request->phone;
        $create_postulant->cv  = $cv;
        $create_postulant->status  = $request->status;
        $create_postulant->company_id  = $request->company_id;
        $create_postulant->department_id  = $request->department_id;
        $create_postulant->interview_date = $request->interview_date;
        $create_postulant->save();  

        return redirect()->action([RhController::class, 'postulants']);

    }

    public function editPostulant($post)
    {
        $postulant = Postulant::all()->where('id',$post)->last();
        $companies = Company::all()->pluck('name_company', 'id');
        $departments = Department::all()->pluck('name','id');
        $postulant_details = PostulantDetails::all()->where('postulant_id',$postulant->id)->last();
        if($postulant_details == null){
            $postulant_beneficiaries  = [];
        }else{
            $postulant_beneficiaries_data = PostulantBeneficiary::all()->where('postulant_details_id',$postulant_details->id);
            $postulant_beneficiaries  = [];
            foreach($postulant_beneficiaries_data as $beneficiary){
                array_push($postulant_beneficiaries, (object)[
                    'id' => $beneficiary->id,
                    'name' => $beneficiary->name,
                    'phone' =>  $beneficiary->phone,
                    'porcentage' => $beneficiary->porcentage,
                    'postulant_details_id' => $beneficiary->postulant_details_id,
                ]);
            }
        }

        return view('rh.edit-postulant', compact('postulant', 'companies', 'departments', 'postulant_details', 'postulant_beneficiaries'));
    }
    

    public function updatePostulant(Request $request)
    {
        $cv = null;

        if ($request->hasFile('cv')) {
            $find_postulant = Postulant::all()->where('id', $request->postulant_id)->last();
            File::delete($find_postulant->cv);
            $filenameWithExt = $request->file('cv')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cv')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
            $cv = $request->file('cv')->move('storage/postulant/', $fileNameToStore);
        }

        DB::table('postulant')->where('id', intval($request->postulant_id))->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'mail' => $request->mail,
            'phone' => $request->phone,
            'cv' => $cv,
            'status' => $request->status,
            'company_id' => $request->company_id,
            'department_id' => $request->department_id,
            'interview_date' => $request->interview_date,
        ]); 

        $find_postulant_details = PostulantDetails::all()->where('postulant_id', $request->postulant_id)->last();
        
        if($find_postulant_details==null){
            $create_postulant_details = new PostulantDetails;
            $create_postulant_details->postulant_id  = $request->postulant_id;
            $create_postulant_details->place_of_birth  = $request->place_of_birth;
            $create_postulant_details->birthdate  = $request->birthdate;
            $create_postulant_details->fathers_name  = $request->fathers_name;
            $create_postulant_details->mothers_name  = $request->mothers_name;
            $create_postulant_details->civil_status  = $request->civil_status;
            $create_postulant_details->age	  = $request->age;
            $create_postulant_details->address  = $request->address;
            $create_postulant_details->street  = $request->street;
            $create_postulant_details->colony  = $request->colony;
            $create_postulant_details->delegation  = $request->delegation;
            $create_postulant_details->postal_code  = $request->postal_code;
            $create_postulant_details->cell_phone  = $request->cell_phone;
            $create_postulant_details->home_phone  = $request->home_phone;
            $create_postulant_details->curp  = $request->curp;
            $create_postulant_details->rfc  = $request->rfc;
            $create_postulant_details->imss_number  = $request->imss_number;
            $create_postulant_details->fiscal_postal_code  = $request->fiscal_postal_code;
            $create_postulant_details->position  = $request->position;
            $create_postulant_details->area  = $request->area;
            $create_postulant_details->salary_sd  = $request->salary_sd;
            $create_postulant_details->salary_sbc  = $request->salary_sbc;
            $create_postulant_details->horary  = $request->horary;
            $create_postulant_details->date_admission  = $request->date_admission;
            $create_postulant_details->card_number  = $request->card_number;
            $create_postulant_details->bank_name  = $request->bank_name;
            $create_postulant_details->infonavit_credit  = $request->infonavit_credit;
            $create_postulant_details->factor_credit_number  = $request->factor_credit_number;
            $create_postulant_details->fonacot_credit  = $request->fonacot_credit;
            $create_postulant_details->discount_credit_number  = $request->discount_credit_number;
            $create_postulant_details->home_references  = $request->home_references;
            $create_postulant_details->house_characteristics  = $request->house_characteristics;
            $create_postulant_details->save();
            
            $find_postulant_details = PostulantDetails::all()->where('postulant_id', $request->postulant_id)->last();

            if($request->beneficiary1<>null){
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary1;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage1;
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
            }

            if($request->beneficiary2<>null){
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary2;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage2;
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
            }

            if($request->beneficiary3<>null){
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary3;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage3;
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
            }

        }else{
            DB::table('postulant_details')->where('postulant_id', intval($request->postulant_id))->update([
                'place_of_birth' => $request->place_of_birth,
                'birthdate' => $request->birthdate,
                'fathers_name' => $request->fathers_name,
                'mothers_name' => $request->mothers_name,
                'civil_status' => $request->civil_status,
                'age' => $request->age,
                'address' => $request->address,
                'street' => $request->street,
                'colony' => $request->colony,
                'delegation' => $request->delegation,
                'postal_code' => $request->postal_code,
                'cell_phone' => $request->cell_phone,
                'home_phone' => $request->home_phone,
                'curp' => $request->curp,
                'rfc' => $request->rfc,
                'imss_number' => $request->imss_number,
                'fiscal_postal_code' => $request->fiscal_postal_code,
                'position' => $request->position,
                'area' => $request->area,
                'salary_sd' => $request->salary_sd,
                'salary_sbc' => $request->salary_sbc,
                'horary' => $request->horary,
                'date_admission' => $request->date_admission,
                'card_number' => $request->card_number,
                'bank_name' => $request->bank_name,
                'infonavit_credit' => $request->infonavit_credit,
                'factor_credit_number' => $request->factor_credit_number,
                'fonacot_credit' => $request->fonacot_credit,
                'discount_credit_number' => $request->discount_credit_number,
                'home_references' => $request->home_references,
                'house_characteristics' => $request->house_characteristics,
            ]); 

            $find_postulant_details_to_beneficiary = PostulantDetails::all()->where('postulant_id', $request->postulant_id)->last();
            DB::table('postulant_beneficiary')->where('postulant_details_id', intval($find_postulant_details_to_beneficiary->id))->delete();
            $find_postulant_details = PostulantDetails::all()->where('postulant_id', $request->postulant_id)->last();

            if($request->beneficiary1 <> null){
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary1;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage1;
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
            }

            if($request->beneficiary2<>null){
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary2;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage2;
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
            }

            if($request->beneficiary3<>null){
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary3;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage3;
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
            }
        }
        return redirect()->back()->with('message', 'Información guardada correctamente');
    }

    public function createPostulantDocumentation($postulant_id)
    {
        $postulant = Postulant::all()->where('id',$postulant_id)->last();
        return View('rh.create-postulant-documentation', compact('postulant'));
    }

    public function buildPostulantDocumentation(Request $request)
    {
        
        $postulant = Postulant::all()->where('id',$request->postulant)->last();
        $postulant_details = PostulantDetails::all()->where('postulant_id',$request->postulant)->last();
        $postulant_beneficiaries = PostulantBeneficiary::all()->where('postulant_details_id',$postulant_details->id)->values('name','porcentage');

        if($request->has('up_personal')){ 
            $this->upDocument($postulant, $postulant_details, $postulant_beneficiaries, $request );
        }

        if($request->has('determined_contract')){
            $this->determinateContract($postulant, $postulant_details,$request->company, $request->determined_contract_duration ); 

        }

        if($request->has('indetermined_contract')){
            $this->indeterminateContract($postulant, $postulant_details,$request->company, $request->determined_contract_duration );  
        }

        if($request->has('confidentiality_agreement')){
            $this->confidentialityAgreement(strtoupper($postulant->name), strtoupper($postulant->lastname),intval($request->company) , date('d/m/Y', strtotime( $postulant_details->date_admission))); 
        }

        if($request->has('work_condition_update')){
            $this->workConditionUpdate(strtoupper($postulant->name), strtoupper($postulant->lastname),strtoupper($postulant_details->position)); 
        }

        if($request->has('no_compete_agreement')){
            
            //Promo zale
            if(intval($request->company) == 3){
                return redirect()->back()->with('error', 'Archivo no disponible para la empresa Promo Zale');          
            }
            //Unipromtex
            if(intval($request->company)== 5){
                return redirect()->back()->with('error', 'Archivo no disponible para la empresa Unipromtex');          
            }
            $this->noCompeteAgreement($postulant, $postulant_details,intval($request->company) , $request->determined_contract_duration ); 
        } 

                 



        
       

       
    }




    /* ---------------------------- DOCUMENTOS GENERADOS ------------------------------*/

    public function upDocument($postulant, $postulant_details, $postulant_beneficiaries, $request )
    {
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
        $sheet->setCellValue('B4', strtoupper($request->company));
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
        $sheet->setCellValue('D23', strtoupper($postulant_details->cp));
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
        $sheet->setCellValue('D31', strtoupper($postulant_details->area));
        $sheet->setCellValue('A33', 'SUELDO:');
        $sheet->setCellValue('B33', strtoupper($postulant_details->salary_sd));
        $sheet->setCellValue('C33', 'HORARIO:');
        $sheet->setCellValue('D33', strtoupper($postulant_details->horary));
        $sheet->setCellValue('A35', 'FECHA DE INGRESO:');
        $sheet->setCellValue('B35', date('d-m-Y', strtotime($postulant_details->date_admission)));
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
        header('Content-Disposition: attachment;filename="ALTA_PERSONAL.xls"');
        header('Cache-Control: max-age=0');
          
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }

    public function determinateContract($postulant, $postulant_details, $company_id, $duration )
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
        $duration_months = $duration;
        $month_string = "MESES";
        $date_admission = date('d,m,Y', strtotime($postulant_details->date_admission));

        if($duration == null || $duration = ""){
            $duration_months = "3";
        }

        if($duration_months == "1" ){
            $month_string = "MES";
        }
       
        
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

        $bodyBoldStyle = array(
            'align' => 'both',
            'lineHeight' => 1.0,
            'bold' => true
        ); 


        $bodyBoldUnderlineStyle = array(
            'bold' => true,
        ); 
        //Paragraph Styles
        $centerTitle = array(
            'size' => 20,
            'align'=> 'center'
        );

        $centerBody = array(
            'size' => 8,
            'align'=> 'center'
        );

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

        //Setting page margins
        $phpWord->getSettings()->setMirrorMargins(false);
        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginTop(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));
        $sectionStyle->setMarginBottom(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));

       

        //Promolife
        if($company_id == 1){
            $company = "PROMO LIFE, S. DE R.L. DE C.V.";
            $employer = "C. RAÚL TORRES MÁRQUEZ";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO DETERMINADO</b> QUE CELEBRAN POR UNA PARTE PROMO LIFE, S. DE R.L. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. RAUL TORRES MARQUEZ, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PISO 1 LOCAL B, COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        
        }

        //BH tardemarket
        if($company_id == 2){
            $company = "BH TRADE MARKET, S.A. DE C.V.";
            $employer = "C. DAVID LEVY HANO";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO DETERMINADO</b> QUE CELEBRAN POR UNA PARTE BH TRADE MARKET, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. DAVID LEVY HANO, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PISO 1 LOCAL B COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        }

        //Promo zale
        if($company_id == 3){
            $company = "PROMO ZALE S.A. DE C.V."; 
            $employer = "C. DANIEL LEVY HANO";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO DETERMINADO</b> QUE CELEBRAN POR UNA PARTE PROMO ZALE, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. DANIEL LEVY HANO, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PISO 1 LOCAL E COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        }

        //Trademarket 57
        if($company_id== 4){
            $company = "TRADE MARKET 57, S.A. DE C.V."; 
            $employer = "C. MÓNICA REYES RESENDIZ";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO DETERMINADO</b> QUE CELEBRAN POR UNA PARTE TRADE MARKET 57, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. MÓNICA REYES RESENDIZ, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PLANTA BAJA, COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        } 

        //Unipromtex
        if($company_id== 5){
            $company = "UNIPROMTEX S.A. DE C.V."; 
            $employer = "DAVID LEVY HANO";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO DETERMINADO</b> QUE CELEBRAN POR UNA PARTE UNIPROMTEX, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. DAVID LEVY HANO, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN C. CIELITO LINDO 18 B, PARQUE INDUSTRIAL IZCALLI, NEZAHUALCOYOTL ESTADO DE MÉXICO. C.P. 57810 A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle2, $centerTitle
            );
        } 

        $htmlsection->addHtml($section, $section2);


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

        $section->addText(
            'I.- LA EMPRESA DECLARA SER UNA SOCIEDAD MERCANTIL CONSTITUIDA ANTE LAS LEYES MEXICANAS, CON DOMICILIO FISCAL EN LA CALLE DE SAN ANDRES ATOTO No. 155 PISO 1 LOCAL A COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, QUE SU OBJETIVO SOCIAL OTROS SERVICIOS DE PUBLICIDAD.',
            $titleStyle,
        );

        $section2 = "<p>II.- EL EMPLEADO POR SU PARTE DECLARA QUE QUEDA DEBIDAMENTE ENTERADO DE LA CAUSA QUE ORIGINA SU CONTRATACIÓN Y ESTA CONFORME EN PRESTAR SUS SERVICIOS PERSONALES A “LA EMPRESA” EN LOS TERMINOS QUE MAS ADELANTE PACTAN, MANIFESTANDO TENER LOS CONOCIMIENTOS SUFICIENTES PARA REALIZAR TAL <b>SERVICIO DE $position QUE CONSISTE EN (OBJETIVO DEL PUESTO).</b></p>";
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

        $section2 = "<p>PRIMERA.- “LA EMPRESA” CONTRATARA AL EMPLEADO PARA QUE LE PRESTE SUS SERVICIOS PERSONALES BAJO SU DIRECCIÓN Y DEPENDENCIA, CON EL CARÁCTER DE EMPLEADO <b>$position</b> Y TENDRA UN PERIODO <b>DE $duration_months $month_string</b>.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>SEGUNDA.- EL LUGAR DE LA PRESTACIÓN DE SERVICIOS SERA TANTO EN EL DOMICILIO DE “LA EMPRESA”, ASI COMO EN EL DE TODAS AQUELLAS PERSONAS FÍSICAS O MORALES QUE CONTRATEN SERVICIOS CON “LA EMPRESA” SEA CUAL FUERE SU UBICACIÓN DENTRO DE LA REPUBLICA MEXICANA.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>TERCERA.- CONVIENEN LAS PARTES EXPRESAMENTE EN QUE EL PRESENTE CONTRATO INDIVIDUAL DE TRABAJO QUE CELEBRAN POR <b>TIEMPO DETERMINADO</b> CONSISTE EN EL DESARROLLO DE LAS LABORES DEL EMPLEADO DE ESTA EMPRESA EN EL DOMICILIO QUE CORRESPONDEN CONFORME LA CLAUSULA SEGUNDA DE ESTE CONTRATO</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>CUARTA.- CONVIENEN LAS PARTES EN QUE EL EMPLEADO RECIBIRA COMO RETRIBUCIÓN DE SUS SERVICIOS  LA CANTIDAD DE <b>$  (NÚMERO Y DECIMALES) (CANTIDAD CON LETRA 00/100 M.N.)</b> DIARIOS, ADICIONALMENTE EL TRABAJADOR RECIBIRA ADEMAS DE LAS PRESTACIONES DE LEY, LAS SIGUIENTES PRESTACIONES SIEMPRE Y CUANDO CUMPLA CON LOS REQUISITOS ESTABLECIDOS PARA OBTENERLAS ESTAS SON: UN 10% DE PREMIO DE PUNTUALIDAD; 10% PREMIO DE ASISTENCIA Y DESPENSA EN EFECTIVO LOS CUALES   LE SERAN PAGADOS EN MONEDA NACIONAL VIA TRANSFERENCIA ELECTRONICA A LA TARJETA DE NOMINA BANCOMER, LA CUAL LE SERA ASIGNADA EN EL MOMENTO DE SU CONTRATACION, LOS DIAS 15 Y ULTIMO DE CADA MES.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>QUINTA.- CONVIENEN LAS PARTES EN QUE POR CADA SEIS DIAS DE TRABAJO EL EMPLEADO DISFRUTARA DE UN DIA DE DESCANSO CON GOCE DE SALARIO INTEGRO CUBRIENDO 48 HORAS DE TRABAJO SEMANALES, YA SEA EN EL DOMICILIO DE LA EMPRESA O DONDE SE LE ASIGNE,  IGUALMENTE TENDRA DERECHO A DISFRUTAR DE SALARIOS EN LOS DIAS DE DESCANSO OBLIGATORIO QUE SEÑALA LA LEY FEDERAL DEL TRABAJO, CUANDO ESTOS OCURRAN DENTRO DEL TERMINO DE SU CONTRATACIÓN.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>SEXTA.- CONVIENEN LAS PARTES EN QUE POR LO QUE HACE A RIESGOS PROFESIONALES O ENFERMEDADES NO PROFESIONALES, SE SUJETARAN A LAS DISPOSICIONES QUE SOBRE EL PARTICULAR, ESTABLECE LA LEY DEL INSTITUTO MEXICANO DEL SEGURO SOCIAL</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>SÉPTIMA.- CONVIENEN LAS PARTES EN QUE INDEPENDIENTEMENTE DE LAS OBLIGACIONES QUE IMPONE AL EMPLEADO LA LEY FEDERAL DEL TRABAJO, SE OBLIGA A LO SIGUIENTE:</p>";
        $htmlsection->addHtml($section, $section2);

        $section->addListItem('a) A PRESTAR SUS SERVICIOS CON EL MAYOR INTERES, EFICIENCIA, ESMERO Y LA DEBIDA PRESENTACIÓN PERSONAL.', 0, null, 'multilevel');
        $section->addListItem('b) A OBSERVAR LAS DISPOSICIONES QUE SOBRE HORARIOS DE TRABAJO EXISTAN.', 1, null, 'multilevel');
        
        //Unipromtex
        if($company_id== 5){
            $section->addListItem('c) LA JORNADA DE TRABAJO SERA DE LUNES A VIERNES DE _______________ A _______________ HRS., Y LOS DÍAS SABADOS DE _______________ A  _________________ HRS.  DEBIENDO CUBRIR LAS 48 HORAS A LA SEMANA. ', 1, null, 'multilevel');
        }else{
            $section->addListItem('c) LA JORNADA DE TRABAJO SERA DE LUNES A JUEVES DE  ______ A  ________ HRS., Y LOS DÍAS VIERNES DE  _______ A  ________ HRS.  DEBIENDO CUBRIR LAS 48 HORAS A LA SEMANA ', 1, null, 'multilevel');
        }

        $section2 = "<p>OCTAVA.- CONVIENEN LAS PARTES EN QUE EL EMPLEADO (TRABAJADOR), SERA CAPACITADO PARA EL DESEMPEÑO DE SUS LABORES EN VIRTUD DE QUE YA CUENTAN DE LEY CON LOS TERMINOS DE LOS PLANES Y PROGRAMAS DE CAPACITACION ESTABLECIDOS POR SU CONDUCTO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>NOVENA.- EL EMPLEADO RECIBIRA LAS VACACIONES, PRIMA VACACIONAL Y AGUINALDO QUE ESTABLECEN LOS ARTICULOS 76, 80 Y 87 DE LA LEY FEDERAL DEL TRABAJO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>DECIMA.- LAS PARTES CONVIENEN EN QUE LOS DERECHOS Y OBLIGACIONES QUE MUTUAMENTE LES CORRESPONDEN Y QUE NO HAYA SIDO OBJETO DE MENCION ESPECIFICA, SE SUJETARAN A LAS DISPOSICIONES DE LA LEY FEDERAL DEL TRABAJO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>DECIMA PRIMERA.- DERIVADO DEL ARTICULO 25 (FRACC. X) DE LA LEY FEDERAL DE TRABAJO, EL EMPLEADO DESIGNA EN ESTE ACTO A BENEFICIARIOS  PARA EFECTOS DE PAGO DE PRESTACIONES Y REMUNERACIONES QUE SE GENEREN POR CAUSA DE FALLECIMIENTO O DESAPARICIÓN A CAUSA DE UN DELITO.</p>";
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

        $section2 = "<p>EL PRESENTE CONTRATO SE FIRMA POR DUPLICADO EN EL ESTADO DE MÉXICO, A LOS <b>$date_admission</b></p>";
        $htmlsection->addHtml($section, $section2);
        
        $section->addText('');

        $table = $section->addTable();
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('POR LA EMPREASA<w:br/>'.$company,$bodyCenterBoldStyle,$center);
        $table->addCell(5000, $cellRowSpan )->addText('EL(LA) EMPLEADO (A)<w:br/>',$bodyCenterBoldStyle, $center); 

        $section->addText('');


        $table = $section->addTable();
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('____________________________________<w:br/>'.$employer.$company,$bodyCenterBoldStyle,$center);
        $table->addCell(5000, $cellRowSpan )->addText('____________________________________<w:br/>'.'C. '.$name. ' '. $lastname,$bodyCenterBoldStyle, $center);
       
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'CONTRATO DETERMINADO ' . strtoupper($name) .' '. strtoupper($lastname) . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");   
    }

    public function indeterminateContract($postulant, $postulant_details, $company_id, $duration )
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
        $duration_months = $duration;
        $month_string = "MESES";
        $date_admission = date('d,m,Y', strtotime($postulant_details->date_admission));

        if($duration == null || $duration = ""){
            $duration_months = "3";
        }

        if($duration_months == "1" ){
            $month_string = "MES";
        }
       
        
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

        $bodyBoldStyle = array(
            'align' => 'both',
            'lineHeight' => 1.0,
            'bold' => true
        ); 


        $bodyBoldUnderlineStyle = array(
            'bold' => true,
        ); 
        //Paragraph Styles
        $centerTitle = array(
            'size' => 20,
            'align'=> 'center'
        );

        $centerBody = array(
            'size' => 8,
            'align'=> 'center'
        );

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

        //Setting page margins
        $phpWord->getSettings()->setMirrorMargins(false);
        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(3));
        $sectionStyle->setMarginTop(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));
        $sectionStyle->setMarginBottom(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5));

       

        //Promolife
        if($company_id == 1){
            $company = "PROMO LIFE, S. DE R.L. DE C.V.";
            $employer = "C. RAÚL TORRES MÁRQUEZ";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO INDETERMINADO</b> QUE CELEBRAN POR UNA PARTE PROMO LIFE, S. DE R.L. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. RAUL TORRES MARQUEZ, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PISO 1 LOCAL B, COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        
        }

        //BH tardemarket
        if($company_id == 2){
            $company = "BH TRADE MARKET, S.A. DE C.V.";
            $employer = "C. DAVID LEVY HANO";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO INDETERMINADO</b> QUE CELEBRAN POR UNA PARTE BH TRADE MARKET, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. DAVID LEVY HANO, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PISO 1 LOCAL B COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        }

        //Promo zale
        if($company_id == 3){
            $company = "PROMO ZALE S.A. DE C.V."; 
            $employer = "C. DANIEL LEVY HANO";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO INDETERMINADO</b> QUE CELEBRAN POR UNA PARTE PROMO ZALE, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. DANIEL LEVY HANO, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PISO 1 LOCAL E COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        }

        //Trademarket 57
        if($company_id== 4){
            $company = "TRADE MARKET 57, S.A. DE C.V."; 
            $employer = "C. MÓNICA REYES RESENDIZ";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO INDETERMINADO</b> QUE CELEBRAN POR UNA PARTE TRADE MARKET 57, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. MÓNICA REYES RESENDIZ, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN SAN ANDRES ATOTO No. 155 PLANTA BAJA, COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle, $centerTitle
            );
        } 

        //Unipromtex
        if($company_id== 5){
            $company = "UNIPROMTEX S.A. DE C.V."; 
            $employer = "DAVID LEVY HANO";
            $section2 = "<p>CONTRATO INDIVIDUAL DE TRABAJO POR <b>TIEMPO INDETERMINADO</b> QUE CELEBRAN POR UNA PARTE UNIPROMTEX, S.A. DE C.V., REPRESENTADA EN ESTE ACTO POR EL C. DAVID LEVY HANO, EN SU CARÁCTER DE REPRESENTANTE LEGAL Y CON DOMICILIO EN C. CIELITO LINDO 18 B, PARQUE INDUSTRIAL IZCALLI, NEZAHUALCOYOTL ESTADO DE MÉXICO. C.P. 57810 A QUIEN EN EL CURSO DEL PRESENTE CONTRATO SE LE DENOMINA “LA EMPRESA” Y POR LA OTRA:</p>";
            
            $section->addText(
                $company,
                $titleCenterBoldStyle2, $centerTitle
            );
        } 

        $htmlsection->addHtml($section, $section2);


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

        $section->addText(
            'I.- LA EMPRESA DECLARA SER UNA SOCIEDAD MERCANTIL CONSTITUIDA ANTE LAS LEYES MEXICANAS, CON DOMICILIO FISCAL EN LA CALLE DE SAN ANDRES ATOTO No. 155 PISO 1 LOCAL A COL. UNIDAD SAN ESTEBAN NAUCALPAN DE JUAREZ ESTADO DE MEXICO, C.P. 53550, QUE SU OBJETIVO SOCIAL OTROS SERVICIOS DE PUBLICIDAD.',
            $titleStyle,
        );

        $section2 = "<p>II.- EL EMPLEADO POR SU PARTE DECLARA QUE QUEDA DEBIDAMENTE ENTERADO DE LA CAUSA QUE ORIGINA SU CONTRATACIÓN Y ESTA CONFORME EN PRESTAR SUS SERVICIOS PERSONALES A “LA EMPRESA” EN LOS TERMINOS QUE MAS ADELANTE PACTAN, MANIFESTANDO TENER LOS CONOCIMIENTOS SUFICIENTES PARA REALIZAR TAL <b>SERVICIO DE $position QUE CONSISTE EN (OBJETIVO DEL PUESTO).</b></p>";
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

        $section2 = "<p>PRIMERA.- “LA EMPRESA” CONTRATARA AL EMPLEADO PARA QUE LE PRESTE SUS SERVICIOS PERSONALES BAJO SU DIRECCIÓN Y DEPENDENCIA, CON EL CARÁCTER DE EMPLEADO <b>$position</b> Y TENDRA UN PERIODO <b>DE $duration_months $month_string</b>.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>SEGUNDA.- EL LUGAR DE LA PRESTACIÓN DE SERVICIOS SERA TANTO EN EL DOMICILIO DE “LA EMPRESA”, ASI COMO EN EL DE TODAS AQUELLAS PERSONAS FÍSICAS O MORALES QUE CONTRATEN SERVICIOS CON “LA EMPRESA” SEA CUAL FUERE SU UBICACIÓN DENTRO DE LA REPUBLICA MEXICANA.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>TERCERA.- CONVIENEN LAS PARTES EXPRESAMENTE EN QUE EL PRESENTE CONTRATO INDIVIDUAL DE TRABAJO QUE CELEBRAN POR <b>TIEMPO INDETERMINADO</b> CONSISTE EN EL DESARROLLO DE LAS LABORES DEL EMPLEADO DE ESTA EMPRESA EN EL DOMICILIO QUE CORRESPONDEN CONFORME LA CLAUSULA SEGUNDA DE ESTE CONTRATO</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>CUARTA.- CONVIENEN LAS PARTES EN QUE EL EMPLEADO RECIBIRA COMO RETRIBUCIÓN DE SUS SERVICIOS  LA CANTIDAD DE <b>$  (NÚMERO Y DECIMALES) (CANTIDAD CON LETRA 00/100 M.N.)</b> DIARIOS, ADICIONALMENTE EL TRABAJADOR RECIBIRA ADEMAS DE LAS PRESTACIONES DE LEY, LAS SIGUIENTES PRESTACIONES SIEMPRE Y CUANDO CUMPLA CON LOS REQUISITOS ESTABLECIDOS PARA OBTENERLAS ESTAS SON: UN 10% DE PREMIO DE PUNTUALIDAD; 10% PREMIO DE ASISTENCIA Y DESPENSA EN EFECTIVO LOS CUALES   LE SERAN PAGADOS EN MONEDA NACIONAL VIA TRANSFERENCIA ELECTRONICA A LA TARJETA DE NOMINA BANCOMER, LA CUAL LE SERA ASIGNADA EN EL MOMENTO DE SU CONTRATACION, LOS DIAS 15 Y ULTIMO DE CADA MES.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>QUINTA.- CONVIENEN LAS PARTES EN QUE POR CADA SEIS DIAS DE TRABAJO EL EMPLEADO DISFRUTARA DE UN DIA DE DESCANSO CON GOCE DE SALARIO INTEGRO CUBRIENDO 48 HORAS DE TRABAJO SEMANALES, YA SEA EN EL DOMICILIO DE LA EMPRESA O DONDE SE LE ASIGNE,  IGUALMENTE TENDRA DERECHO A DISFRUTAR DE SALARIOS EN LOS DIAS DE DESCANSO OBLIGATORIO QUE SEÑALA LA LEY FEDERAL DEL TRABAJO, CUANDO ESTOS OCURRAN DENTRO DEL TERMINO DE SU CONTRATACIÓN.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>SEXTA.- CONVIENEN LAS PARTES EN QUE POR LO QUE HACE A RIESGOS PROFESIONALES O ENFERMEDADES NO PROFESIONALES, SE SUJETARAN A LAS DISPOSICIONES QUE SOBRE EL PARTICULAR, ESTABLECE LA LEY DEL INSTITUTO MEXICANO DEL SEGURO SOCIAL</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>SÉPTIMA.- CONVIENEN LAS PARTES EN QUE INDEPENDIENTEMENTE DE LAS OBLIGACIONES QUE IMPONE AL EMPLEADO LA LEY FEDERAL DEL TRABAJO, SE OBLIGA A LO SIGUIENTE:</p>";
        $htmlsection->addHtml($section, $section2);

        $section->addListItem('a) A PRESTAR SUS SERVICIOS CON EL MAYOR INTERES, EFICIENCIA, ESMERO Y LA DEBIDA PRESENTACIÓN PERSONAL.', 0, null, 'multilevel');
        $section->addListItem('b) A OBSERVAR LAS DISPOSICIONES QUE SOBRE HORARIOS DE TRABAJO EXISTAN.', 1, null, 'multilevel');

        //Unipromtex
        if($company_id== 5){
            $section->addListItem('c) LA JORNADA DE TRABAJO SERA DE LUNES A VIERNES DE _______________ A _______________ HRS., Y LOS DÍAS SABADOS DE _______________ A  _________________ HRS.  DEBIENDO CUBRIR LAS 48 HORAS A LA SEMANA. ', 1, null, 'multilevel');
        }else{
            $section->addListItem('c) LA JORNADA DE TRABAJO SERA DE LUNES A JUEVES DE  ______ A  ________ HRS., Y LOS DÍAS VIERNES DE  _______ A  ________ HRS.  DEBIENDO CUBRIR LAS 48 HORAS A LA SEMANA ', 1, null, 'multilevel');
        }

        $section2 = "<p>OCTAVA.- CONVIENEN LAS PARTES EN QUE EL EMPLEADO (TRABAJADOR), SERA CAPACITADO PARA EL DESEMPEÑO DE SUS LABORES EN VIRTUD DE QUE YA CUENTAN DE LEY CON LOS TERMINOS DE LOS PLANES Y PROGRAMAS DE CAPACITACION ESTABLECIDOS POR SU CONDUCTO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>NOVENA.- EL EMPLEADO RECIBIRA LAS VACACIONES, PRIMA VACACIONAL Y AGUINALDO QUE ESTABLECEN LOS ARTICULOS 76, 80 Y 87 DE LA LEY FEDERAL DEL TRABAJO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>DECIMA.- LAS PARTES CONVIENEN EN QUE LOS DERECHOS Y OBLIGACIONES QUE MUTUAMENTE LES CORRESPONDEN Y QUE NO HAYA SIDO OBJETO DE MENCION ESPECIFICA, SE SUJETARAN A LAS DISPOSICIONES DE LA LEY FEDERAL DEL TRABAJO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>DECIMA PRIMERA.- DERIVADO DEL ARTICULO 25 (FRACC. X) DE LA LEY FEDERAL DE TRABAJO, EL EMPLEADO DESIGNA EN ESTE ACTO A BENEFICIARIOS  PARA EFECTOS DE PAGO DE PRESTACIONES Y REMUNERACIONES QUE SE GENEREN POR CAUSA DE FALLECIMIENTO O DESAPARICIÓN A CAUSA DE UN DELITO.</p>";
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

        $section2 = "<p>EL PRESENTE CONTRATO SE FIRMA POR DUPLICADO EN EL ESTADO DE MÉXICO, A LOS <b>$date_admission</b></p>";
        $htmlsection->addHtml($section, $section2);
        
        $section->addText('');

        $table = $section->addTable();
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('POR LA EMPREASA<w:br/>'.$company,$bodyCenterBoldStyle,$center);
        $table->addCell(5000, $cellRowSpan )->addText('EL(LA) EMPLEADO (A)<w:br/>',$bodyCenterBoldStyle, $center); 

        $section->addText('');


        $table = $section->addTable();
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('____________________________________<w:br/>'.$employer.$company,$bodyCenterBoldStyle,$center);
        $table->addCell(5000, $cellRowSpan )->addText('____________________________________<w:br/>'.'C. '.$name. ' '. $lastname,$bodyCenterBoldStyle, $center);
       
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'CONTRATO DETERMINADO ' . strtoupper($name) .' '. strtoupper($lastname) . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");    
    }

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

    public function confidentialityAgreement($name, $lastname, $company_id, $date_admission)
    {
        $company = "";
        $employer = "";
         //Promolife
        if($company_id == 1){
            $company = "PROMO LIFE, S. DE R.L. DE C.V.";
            $employer = "C. RAÚL TORRES MÁRQUEZ";
        }

        //BH tardemarket
        if($company_id == 2){
            $company = "BH TRADE MARKET, S.A. DE C.V.";
            $employer = "C. DAVID LEVY HANO";
        }

        //Promo zale
        if($company_id == 3){
            $company = "PROMO ZALE S.A. DE C.V."; 
            $employer = "C. DANIEL LEVY HANO";
        }

        //Trademarket 57
        if($company_id== 4){
            $company = "TRADE MARKET 57, S.A. DE C.V."; 
            $employer = "C. MÓNICA REYES RESENDIZ";
        } 

        //Unipromtex
        if($company_id== 5){
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
        header('Content-Disposition: attachment; filename="' . 'CONVENIO DE CONFIDENCIALIDAD ' . strtoupper($company) . ' ' . strtoupper($name) .' '. strtoupper($lastname) . '.doc');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
    }

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

    public function noCompeteAgreement($postulant, $postulant_details, $company_id, $duration)
    {
        $social_reason = "";
        $company_name = "";
        $company_address = "";
        $employer = "";
        $senior = "EL SEÑOR";
        $name = strtoupper($postulant->name);
        $lastname = strtoupper($postulant->lastname); 
        $rfc = strtoupper($postulant_details->rfc);
        $position = strtoupper($postulant_details->position);
        $date_admission = date('d/m/Y', strtotime($postulant_details->date_admission));
        $address =strtoupper($postulant_details->address);

        //Promolife
        if($company_id == 1){
            $social_reason = "PROMO LIFE, S. DE R.L. DE C.V.";
            $employer = "C. RAÚL TORRES MÁRQUEZ";
            $company_name = "PROMO LIFE";

            $uno_a = 'ES UNA SOCIEDAD CONSTITUIDA DE CONFORMIDAD CON LAS LEYES DE LOS ESTADOS UNIDOS MEXICANOS, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 108810, DE FECHA 23 DE SEPTIEMBRE DE 2011, OTORGADA ANTE EL LICENCIADO F. JAVIER ARCE GARGOLLO, TITULAR DE LA NOTARÍA NÚMERO 74 DE LA CIUDAD DE MÉXICO.';    
            $uno_b = 'SU REPRESENTANTE CUENTA CON LAS FACULTADES SUFICIENTES PARA OBLIGAR A SU REPRESENTADA EN LOS TÉRMINOS DE ESTE CONVENIO, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 108810, DE FECHA 23 DE SEPTIEMBRE DE 2011, OTORGADA ANTE EL LICENCIADO F. JAVIER ARCE GARGOLLO, TITULAR DE LA NOTARÍA NÚMERO 74 DE LA CIUDAD DE MÉXICO, MISMAS QUE A LA FECHA NO LE HAN SIDO REVOCADAS, LIMITADAS O MODIFICADAS DE FORMA ALGUNA.';    
            $uno_c = 'ES SU DESEO CELEBRAR EL PRESENTE CONVENIO, SUJETO A LOS TÉRMINOS Y CONDICIONES QUE MÁS ADELANTE SE INDICAN.';    
            
            $company_address = 'SAN ANDRES ATOTO 155 PISO 1 LOC. B, UNIDAD SAN ESTEBAN. NAUCALPAN DE JUÁREZ ESTADO DE MÉXICO, C.P. 53550.';
        }
        
        //BH tardemarket
        if($company_id == 2){
            $social_reason = "BH TRADE MARKET, S.A. DE C.V.";
            $employer = "C. DAVID LEVY HANO";
            $company_name = "BH TRADE MARKET";

            $uno_a = 'ES UNA SOCIEDAD CONSTITUIDA DE CONFORMIDAD CON LAS LEYES DE LOS ESTADOS UNIDOS MEXICANOS, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 1881, DE FECHA 25 DE NOVIEMBRE DE 2014, OTORGADA ANTE EL LICENCIADO CLAUDIA GABRIELA FRANCÓZ GÁRATE, TITULAR DE LA NOTARÍA NÚMERO 153 DEL ESTADO DE MÉXICO.';   
            $uno_b = 'SU REPRESENTANTE CUENTA CON LAS FACULTADES SUFICIENTES PARA OBLIGAR A SU REPRESENTADA EN LOS TÉRMINOS DE ESTE CONVENIO, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 1881, DE FECHA 28 DE NOVIEMBRE DE 2014, OTORGADA ANTE EL LICENCIADO CLAUDIA GABRIELA FRANCÓZ GÁRATE, TITULAR DE LA NOTARÍA NÚMERO 153 DEL ESTADO DE MÉXICO, MISMAS QUE A LA FECHA NO LE HAN SIDO REVOCADAS, LIMITADAS O MODIFICADAS DE FORMA ALGUNA. ';    
            $uno_c = 'ES SU DESEO CELEBRAR EL PRESENTE CONVENIO, SUJETO A LOS TÉRMINOS Y CONDICIONES QUE MÁS ADELANTE SE INDICAN.';    
            
            $company_address = 'SAN ANDRES ATOTO 155 PISO 1 LOC. B, UNIDAD SAN ESTEBAN. NAUCALPAN DE JUÁREZ ESTADO DE MÉXICO, C.P. 53550.'; 
        }
        
           
        
        //Trademarket 57
        if($company_id== 4){
            $social_reason = "TRADE MARKET 57, S.A. DE C.V."; 
            $employer = "C. MÓNICA REYES RESENDIZ";
            $company_name = "TRADE MARKET 57";
            $senior = "LA SEÑORITA";

            $uno_a = 'ES UNA SOCIEDAD CONSTITUIDA DE CONFORMIDAD CON LAS LEYES DE LOS ESTADOS UNIDOS MEXICANOS, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 2062, DE FECHA 08 DE JULIO DE 2015, OTORGADA ANTE EL LICENCIADO CLAUDIA GABRIELA FRANCÓZ GÁRATE, TITULAR DE LA NOTARÍA NÚMERO 153 DEL ESTADO DE MÉXICO.';    
            $uno_b = 'SU REPRESENTANTE CUENTA CON LAS FACULTADES SUFICIENTES PARA OBLIGAR A SU REPRESENTADA EN LOS TÉRMINOS DE ESTE CONVENIO, SEGÚN CONSTA EN LA ESCRITURA NÚMERO 2062, DE FECHA 08 DE JULIO DE 2015, OTORGADA ANTE EL LICENCIADO CLAUDIA GABRIELA FRANCÓZ GÁRATE, TITULAR DE LA NOTARÍA NÚMERO 153 DEL ESTADO DE MÉXICO, MISMAS QUE A LA FECHA NO LE HAN SIDO REVOCADAS, LIMITADAS O MODIFICADAS DE FORMA ALGUNA. ';    
            $uno_c = 'ES SU DESEO CELEBRAR EL PRESENTE CONVENIO, SUJETO A LOS TÉRMINOS Y CONDICIONES QUE MÁS ADELANTE SE INDICAN.';    

            $company_address = 'SAN ANDRES ATOTO 155 PLANTA BAJA, UNIDAD SAN ESTEBAN. NAUCALPAN DDE JUÁREZ. ESTADO DE MÉXICO 53550';
        } 
        
        
       
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->getSettings()->setMirrorMargins(true);
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::ES_ES));

        //Global styles
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(9);
        
        //Font Styles
        $phpWord->setDefaultParagraphStyle(
            array(
                'align' => 'both',
                'lineHeight' => 1.15
            )
        );

        $textLineBoldCenter = array(
            'underline' => 'single',
            'bold' => true,
            'align'=> 'center'
        );
       

        $bodyBoldStyle = array(
            'align' => 'both',
            'lineHeight' => 1.15,
            'bold' => true
        ); 

        $bodyNormalStyle = array(
            'align' => 'both',
            'lineHeight' => 1.15,
            'bold' => false
        ); 

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

        $section2 = "<p><b>CONVENIO DE NO COMPETENCIA Y CONFIDENCIALIDAD (EL “<u>CONVENIO</u>”) QUE CELEBRAN POR UNA PARTE $social_reason, REPRESENTADA EN ESTE ACTO POR $senior $employer, A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ COMO “ $company_name ”, Y POR OTRA PARTE EL/LA C. $name $lastname POR SU PROPIO DERECHO, A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ COMO “EMPLEADO” Y CONJUNTAMENTE CON $company_name COMO LAS “PARTES”, AL TENOR DE LAS SIGUIENTES DECLARACIONES Y CLÁUSULAS.</b></p>";
        $htmlsection->addHtml($section, $section2);

        $section->addText(
            'DECLARACIONES',
            $textLineBoldCenter,$center
        );

        $multilevelListStyleName = 'multilevel';

        $phpWord->addNumberingStyle(
            $multilevelListStyleName,
            [
                'type' => 'multilevel',
                'levels' => [
                    ['format' => 'upperRoman', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360],
                    ['format' => 'lowerLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
                    ['format' => 'decimal', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
                    ['format' => 'lowerRoman', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],

                ],
            ]
        );

        $listItemRun = $section->addListItemRun(0, $multilevelListStyleName,[]);
        $listItemRun->addText('DECLARA '. $company_name . ', A TRAVÉS DE SU REPRESENTANTE LEGAL Y BAJO PROTESTA DE DECIR VERDAD QUE:',$bodyBoldStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText($uno_a,$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText($uno_b,$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText($uno_c,$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(0, $multilevelListStyleName,[]);
        $listItemRun->addText('DECLARA EL EMPLEADO, POR SU PROPIO DERECHO Y BAJO PROTESTA DE DECIR VERDAD QUE:  ',$bodyBoldStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('ES UNA PERSONA FÍSICA CON PLENA CAPACIDAD JURÍDICA Y PLENO USO DE SUS FACULTADES LEGALES PARA SUSCRIBIR EL PRESENTE CONVENIO.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('CUENTA CON REGISTRO FEDERAL DE CONTRIBUYENTES '. $rfc .' SEGÚN CONSTA EN LA CEDULA DE IDENTIFICACIÓN FISCAL, EXPEDIDA POR LA SECRETARIA DE HACIENDA Y CRÉDITO PÚBLICO.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('DERIVADO DE SU RELACIÓN COMERCIAL Y/O PROFESIONAL Y/O PERSONAL CON '. $company_name . ', CON SUS ACCIONISTAS Y/O CON SUS DIRECTORES, QUE HA TENIDO DESDE HACE VARIOS AÑOS.  HA TENIDO Y SEGUIRÁ TENIENDO ACCESO A INFORMACIÓN CONFIDENCIAL DE '.$company_name.' Y/O DE SUS CLIENTES, ASÍ COMO A INFORMACIÓN CONFIDENCIAL DE GIRO COMERCIAL Y “KNOW HOW” DEL NEGOCIO DE '. $company_name.', RECONOCIENDO QUE DICHA INFORMACIÓN CONFIDENCIAL REPRESENTA UN ACTIVO Y VENTAJA COMPETITIVA DE '. $company_name. ' EN EL MERCADO, Y FRENTE A SUS COMPETIDORES.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('RECONOCE QUE LA REVELACIÓN O USO INDEBIDO DE LA INFORMACIÓN CONFIDENCIAL DE '. $company_name . ', ASÍ COMO A INFORMACIÓN CONFIDENCIAL DE GIRO COMERCIAL Y “KNOW HOW” DEL NEGOCIO DE ' . $company_name . ', CAUSARÍA A ESTA UN DAÑO SERIO E IRREPARABLE, ASÍ COMO AL DESARROLLO DE SUS OPERACIONES Y ACTIVIDADES COMERCIALES.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('CUENTA CON LA CAPACIDAD Y RECURSOS SUFICIENTES PARA CELEBRAR Y EJECUTAR LAS OBLIGACIONES QUE ASUME CONFORME AL PRESENTE CONVENIO.',$bodyNormalStyle);
        $listItemRun = $section->addListItemRun(1, $multilevelListStyleName,[]);
        $listItemRun->addText('QUE LAS OBLIGACIONES CONTRAÍDAS POR EL EMPLEADO EN VIRTUD DE ESTE CONVENIO SON VÁLIDAS, LEGALMENTE VINCULANTES Y EXIGIBLES EN SU CONTRA, DE ACUERDO CON LOS TÉRMINOS INDICADOS EN EL MISMO.',$bodyNormalStyle);


        $section->addText(
            'EN VIRTUD DE LO ANTERIOR Y NO EXISTIENDO DOLO, VIOLENCIA, LESIÓN, O ALGÚN OTRO TIPO DE VICIO EN EL CONSENTIMIENTO, LAS PARTES SE SOMETEN A LAS SIGUIENTES:',
            $bodyBoldStyle,
        );

        $section->addText(
            'CLÁUSULAS',
            $bodyNormalStyle,$center
        );       

        $section2 = "<p><b>PRIMERA.- OBJETO.</b> EL EMPLEADO SE OBLIGA CON $company_name A LA ABSTENCIÓN DE LA REALIZACIÓN DE CUALQUIER ACTO QUE REPRESENTE DE CUALQUIER MANERA DIRECTA O INDIRECTA COMPETENCIA COMERCIAL O DE CUALQUIER OTRA ÍNDOLE A $company_name, SUS ACCIONISTAS, FILIALES, SUBSIDIARIAS, SUS EMPLEADOS, REPRESENTANTES, AGENTES, CONSULTORES Y/O APODERADOS DE CONFORMIDAD CON LO ESTABLECIDO EN EL PRESENTE CONVENIO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>ASIMISMO, <b>EL EMPLEADO</b> SE OBLIGA A GUARDAR ESTRICTA CONFIDENCIALIDAD SOBRE TODA LA INFORMACIÓN CONFIDENCIAL (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE) A LA QUE TUVO ACCESO CON ANTERIORIDAD A ESTE ACTO, A LA QUE PUDIERA TENER ACCESO Y/O QUE LE SEA SUMINISTRADA O PROPORCIONADA, YA SEA DE FORMA VERBAL, ESCRITA O POR CUALQUIER OTRO MEDIO, POR $company_name, SUS EMPLEADOS, FUNCIONARIOS, ACCIONISTAS, REPRESENTANTES, AGENTES, CONSULTORES Y/O APODERADOS O CUALQUIER OTRA PERSONA RELACIONADA CON $company_name.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>SEGUNDA.- NO COMPETENCIA (NON COMPETE). EL EMPLEADO</b> SE OBLIGA A NO PARTICIPAR, DIRECTA O INDIRECTAMENTE O A TRAVÉS DE TERCEROS, COMO EMPRESARIO, EMPLEADO, FUNCIONARIO, GERENTE, GERENTE DE VENTAS, AYUDANTE GENERAL, SOCIO, DIRECTOR, AGENTE, PROPIETARIO, ACCIONISTA O EN CUALQUIER OTRA CAPACIDAD, EN ALGUNA ACTIVIDAD QUE TENGA IDENTIDAD O SE RELACIONE CON EL GIRO COMERCIAL DE $company_name O DE CUALQUIERA DE SUS SUBSIDIARIAS Y/O CUALQUIER PERSONA FÍSICA O MORAL QUE APAREZCA EN LA LISTA DE CLIENTES (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE), INCLUYENDO CUALQUIER SOCIEDAD CUYO OBJETO SOCIAL SEA LA VENTA, COMPRA, IMPRESIÓN, FABRICACIÓN, COMERCIALIZACIÓN, IMPORTACIÓN, EXPORTACIÓN Y/O DISTRIBUCIÓN DE PRODUCTOS Y/O ARTÍCULOS PROMOCIONALES Y DE PUBLICIDAD, PRODUCTOS MÉDICOS, DE SALUD, TECNOLÓGICOS Y/O CUALQUIER OTRO PRODUCTO QUE PUEDA SER SUMINISTRADO POR $company_name ASÍ COMO EL DESARROLLO DE PROYECTOS DE COMERCIALIZACIÓN Y PUBLICIDAD DE PRODUCTOS EN MÉXICO Y/O EN EL EXTRANJERO.</p>";
        $htmlsection->addHtml($section, $section2);
        
        $section2 = "<p>A SU VEZ, <b>EL EMPLEADO</b> SE OBLIGA A ABSTENERSE DE INCURRIR EN CUALQUIER NEGOCIACIÓN, PLÁTICAS, ACERCAMIENTOS, OFERTAS, ASÍ COMO INDUCIR, SOLICITAR, CONTRATAR, INTERFERIR EN LA RELACIÓN COMERCIAL, DE FORMA DIRECTA O INDIRECTA CON CUALQUIERA DE LOS CLIENTES, PROVEEDORES, PROSPECTOS, EMPLEADOS, CONTRATISTAS Y/O VENDEDORES O CUALQUIER PERSONA FÍSICA O MORAL QUE APAREZCA EN LA LISTA DE CLIENTES DE $company_name O CUALQUIERA DE SUS SUBSIDIARIAS RESPECTO DE LOS SERVICIOS Y/O PRODUCTOS QUE $company_name, PRESTA, OFRECE Y/O VENDE AL PÚBLICO GENERAL (CONJUNTAMENTE CON LO ESTABLECIDO EN EL PÁRRAFO INMEDIATO ANTERIOR, LA “<b>NO COMPETENCIA</b>”).</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LA OBLIGACIÓN DE NO COMPETENCIA ESTABLECIDA EN LA PRESENTE CLÁUSULA SUBSISTIRÁ DURANTE UN PLAZO DE 5 AÑOS (60 MESES) POSTERIORES A LA TERMINACIÓN DE LA VIGENCIA (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE) DEL PRESENTE CONVENIO. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>TERCERA.-  CONFIDENCIALIDAD.</b>  EL TÉRMINO <i>INFORMACIÓN CONFIDENCIAL</i>, SEGÚN SE UTILICE EN EL PRESENTE CONVENIO, Y EN TÉRMINOS DE LOS ARTÍCULOS 82, 83 Y 85 DE LA LEY DE LA PROPIEDAD INDUSTRIAL, SIGNIFICA E INCLUYE TODA INFORMACIÓN PROPIEDAD DE $company_name, QUE SE DETALLA MÁS ADELANTE, Y QUE SEA REVELADA A <b>EL EMPLEADO</b> CON MOTIVO DE SU RELACIÓN COMERCIAL Y/O PROFESIONAL Y/O PERSONAL Y/O CONTRACTUAL. ESTA INFORMACIÓN ESTARÁ REGULADA POR LAS DISPOSICIONES APLICABLES DE LA LEY DE LA PROPIEDAD INDUSTRIAL, Y EN EL CÓDIGO PENAL.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>EL EMPLEADO</b> SE OBLIGA EXPRESAMENTE EN ESTE ACTO A NO DIVULGAR, DIRECTA O INDIRECTAMENTE, NI BAJO NINGUNA CIRCUNSTANCIA Y GUARDAR ABSOLUTA CONFIDENCIALIDAD EN TODO MOMENTO, RESPECTO A TODA LA TODA AQUELLA INFORMACIÓN TRANSMITIDA, YA SEA POR VÍA ORAL, POR CUALQUIER TIPO DE DOCUMENTOS, POR MEDIOS ELECTRÓNICOS O CUALQUIER OTRO MEDIO, QUE GUARDE UNA RELACIÓN DIRECTA O INDIRECTA CON LA INFORMACIÓN A LA CUAL TENGA O PUEDA TENER ACCESO <b>EL EMPLEADO</b> DE $company_name Y/O DE CUALQUIER SUBSIDIARIA Y/O DE SUS CLIENTES, SIENDO CONSIDERADOS ENTRE ESTA INFORMACIÓN, DE MANERA ENUNCIATIVA MÁS NO LIMITATIVA, LA QUE PUEDA INCLUIR INFORMACIÓN DE MERCADOTECNIA, DATOS PERSONALES, SISTEMAS, ASUNTOS JURÍDICOS, RECURSOS HUMANOS, PLANES DE NEGOCIOS, BASES DE DATOS, PROCEDIMIENTOS INTERNOS, LOGÍSTICA, IMPORTACIONES Y/O EXPORTACIONES, INFORMACIÓN FISCAL, INFORMACIÓN FINANCIERA, INFORMACIÓN RELACIONADA CON ACCIONISTAS Y SOCIOS, PRECIOS, PROVEEDORES, CLIENTES, SECRETOS INDUSTRIALES, ASIGNACIÓN DE CUENTAS, DATOS PERSONALES (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE), LA LISTA DE CLIENTES, PLANES DE NEGOCIOS, SOCIOS ESTRATÉGICOS, MEDIOS Y FORMAS DE DISTRIBUCIÓN DE PRODUCTOS Y/O DE PRESTACIÓN DE SERVICIOS, “KNOW HOW”, CUALQUIER INFORMACIÓN GENERADA DURANTE SU GESTIÓN EN $company_name EN VIRTUD DE CUALQUIER RELACIÓN COMERCIAL, CONTRACTUAL Y/O DE CUALQUIER OTRA ÍNDOLE Y/O TODA AQUELLA INFORMACIÓN QUE DE MANERA DIRECTA O INDIRECTA SE RELACIONE CON LOS PUNTOS ENUNCIADOS ANTERIORMENTE (LA “<b>INFORMACIÓN CONFIDENCIAL</b>”), DEBIENDO <b>EL EMPLEADO</b> EN TODO MOMENTO MANTENERLA FUERA DEL ALCANCE DEL PÚBLICO EN GENERAL, ASÍ COMO TRATARLA COMO PRIVADA Y NO AUTORIZAR SU PUBLICACIÓN O DIVULGACIÓN DE NINGUNA MANERA.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>EL EMPLEADO</b> CONVIENE EN CONCEDER A PARTIR DE ESTA FECHA, TRATO CONFIDENCIAL Y DE ACCESO RESTRINGIDO A LA INFORMACIÓN CONFIDENCIAL A LA QUE PUDIERA TENER ACCESO POR CUALQUIER MOTIVO, COMPROMETIÉNDOSE A MANTENER EN SU PODER ÚNICAMENTE LA INFORMACIÓN CONFIDENCIAL ESTRICTAMENTE NECESARIA PARA EL CUMPLIMIENTO DE SUS OBLIGACIONES COMERCIALES, CONTRACTUALES O DE CUALQUIER OTRA ÍNDOLE FRENTE A $company_name, ASÍ COMO A CONSERVARLA EN SU PODER EL TIEMPO QUE SEA ESTRICTAMENTE NECESARIO (LA “<b>CONFIDENCIALIDAD</b>”).</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LAS PARTES ACUERDAN QUE LA OBLIGACIÓN DE CONFIDENCIALIDAD ESTABLECIDA EN LA PRESENTE CLÁUSULA SERÁ APLICABLE A TODA LA INFORMACIÓN CONFIDENCIAL QUE $company_name LE HAYA TRANSMITIDO EL EMPLEADO POR CUALQUIER MEDIO PREVIO A LA CELEBRACIÓN DEL PRESENTE CONVENIO Y A CUALQUIER INFORMACIÓN CONFIDENCIAL A LA QUE HAYA TENIDO ACCESO O TENGA ACCESO COMO CONSECUENCIA DE CUALQUIER ACTO O HECHO DERIVADO DE SU RELACIÓN COMERCIAL Y/O PROFESIONAL Y/O PERSONAL Y/O CONTRACTUAL CON $company_name DERIVADO DE RELACIÓN COMERCIAL Y/O PROFESIONAL Y/O PERSONAL Y/O CONTRACTUAL CON $company_name O SUS SUBSIDIARIA</p>";
        $htmlsection->addHtml($section, $section2);
     
        $section2 = "<p>A SU VEZ, LAS PARTES ACUERDAN QUE TODOS LOS TÉRMINOS, CONDICIONES Y ACUERDOS CONTENIDOS EN EL PRESENTE CONVENIO SERÁN CONSIDERADOS COMO CONFIDENCIALES Y ESTARÁN SUJETOS A TODAS LAS OBLIGACIONES DE CONFIDENCIALIDAD CONTENIDAS EN EL PRESENTE CONVENIO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>PARA LOS EFECTOS DE ESTE CONVENIO, NO SE CONSIDERARÁ INFORMACIÓN CONFIDENCIAL AQUELLA INFORMACIÓN:</p>";
        $htmlsection->addHtml($section, $section2); 

        $listItemRun = $section->addListItemRun(3, $multilevelListStyleName,[]);
        $listItemRun->addText('QUE SEA O LLEGUE A SER DEL DOMINIO PÚBLICO, SIN MEDIAR INCUMPLIMIENTO DE ESTE CONVENIO POR EL EMPLEADO.',$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(3, $multilevelListStyleName,[]);
        $listItemRun->addText('QUE '. $company_name . ' AUTORICE EL EMPLEADO, PREVIA NOTIFICACIÓN Y POR ESCRITO, PARA SU DIVULGACIÓN O ENTREGA DE INFORMACIÓN CONFIDENCIAL A UN TERCERO; Y ',$bodyNormalStyle);
        
        $listItemRun = $section->addListItemRun(3, $multilevelListStyleName,[]);
        $listItemRun->addText('QUE EL EMPLEADO SEA INSTRUIDO MEDIANTE REQUERIMIENTO JUDICIAL DE ENTREGAR PARCIAL O TOTALMENTE LA INFORMACIÓN CONFIDENCIAL, EN CUYO CASO EL EMPLEADO, PREVIO A CUALQUIER ENTREGA DE LA INFORMACIÓN CONFIDENCIAL, SE OBLIGA A INFORMAR INMEDIATAMENTE A '.$company_name.' RESPECTO DE DICHO REQUERIMIENTO Y DEBERÁ REALIZAR LOS ACTOS QUE ESTÉN A SU ALCANCE PARA PREVENIR LA ENTREGA DE LA INFORMACIÓN CONFIDENCIAL PREVIO A INFORMAR A '. $company_name .'.',$bodyNormalStyle);

        $section2 = "<p><b>CUARTA.- SECRETO INDUSTRIAL.</b> PARA TODOS LOS EFECTOS A QUE HAYA LUGAR, LAS PARTES CONSIDERARÁN QUE LA INFORMACIÓN CONFIDENCIAL SE EQUIPARA O ES EQUIVALENTE AL SECRETO INDUSTRIAL CONFORME A LA LEY DE LA PROPIEDAD INDUSTRIAL.</p>";
        $htmlsection->addHtml($section, $section2); 

        $section2 = "<p>LAS PARTES CONVIENEN QUE EN CASO DE QUE <b>EL EMPLEADO</b> INCUMPLA CUALQUIERA DE LAS OBLIGACIONES A SU CARGO QUE SE DERIVAN DEL PRESENTE CONVENIO O EN CASO DE QUE NO LAS CUMPLA DE LA MANERA CONVENIDA, $company_name PODRÁ EJERCER LAS ACCIONES PENALES, CIVILES Y ADMINISTRATIVAS QUE CORRESPONDAN, INCLUYENDO, ENUNCIATIVA MAS NO LIMITATIVAMENTE, LAS DISPUESTAS POR LOS ARTÍCULOS 223 Y 224 DE LA LEY DE PROPIEDAD INDUSTRIAL Y POR LOS ARTÍCULOS 211 Y 211 BIS 7 DEL CÓDIGO PENAL FEDERAL; LO ANTERIOR SIN DETRIMENTO DE EXIGIR EL PAGO DE LOS DAÑOS Y PERJUICIOS QUE CONFORME A DERECHO CORRESPONDAN.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>QUINTA.- DATOS PERSONALES. EL EMPLEADO</b> SE OBLIGA RESPECTO DE LOS DATOS PERSONALES A LOS CUALES TENGA ACCESO, INCLUYENDO SIN LIMITAR, LOS NOMBRES, YA SEA DE PERSONA FÍSICA O MORAL, TELÉFONOS, DIRECCIONES, CORREOS ELECTRÓNICOS, INFORMACIÓN DE COMPRA O VENTA, EMPLEADOS, PUESTOS DE LOS EMPLEADOS, ASÍ COMO CUALQUIER OTRA INFORMACIÓN A LA QUE PUDIERA TENER ACCESO RESPECTO DE LOS CLIENTES, PROVEEDORES, PROSPECTOS, EMPLEADOS, CONTRATISTAS Y/O VENDEDORES PASADOS, PRESENTES Y FUTUROS DE $company_name, CUALQUIERA DE SUS SUBSIDIARIAS O CUALQUIER PERSONA FÍSICA O MORAL QUE APAREZCA EN LA LISTA DE CLIENTES (LOS “<b>DATOS PERSONALES</b>”), A QUE ÉSTOS SERÁN TRATADOS DE CONFORMIDAD CON LO DISPUESTO EN LA LEY FEDERAL DE PROTECCIÓN DE DATOS PERSONALES EN POSESIÓN DE LOS PARTICULARES PUBLICADA EN EL DIARIO OFICIAL DE LA FEDERACIÓN (EN ADELANTE “DOF”) EL CINCO DE JULIO DE DOS MIL DIEZ, ASÍ COMO SU REGLAMENTO PUBLICADO EN EL DOF EL VEINTIUNO DE DICIEMBRE DE DOS MIL ONCE, SIN PERJUICIO DE LAS DEMÁS DISPOSICIONES LEGALES APLICABLES.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LA OBLIGACIÓN SEÑALADA EN EL PÁRRAFO INMEDIATO ANTERIOR, SIN LIMITARSE A ESTA, LE SERÁ APLICABLE LA LISTA DE CLIENTES QUE SE ADJUNTA AL PRESENTE COMO ANEXO 1, ASÍ COMO A SUS ACCIONISTAS, FILIALES, SUBSIDIARIAS, EMPLEADOS, REPRESENTANTES, AGENTES, CONSULTORES Y/O APODERADOS (LA “<b>LISTA DE CLIENTES</b>”), MISMA QUE SE ACTUALIZARÁ DE TIEMPO EN TIEMPO A DISCRECIÓN DE $company_name. LO ANTERIOR, EN EL ENTENDIDO DE QUE LAS SOCIEDADES SEÑALADAS EN LA LISTA DE CLIENTES SE CONSIDERARÁN EN TODO MOMENTO COMO CLIENTES DE $company_name, INDEPENDIENTEMENTE DE LA ASIGNACIÓN DE CUENTAS QUE LLEVE A CABO $company_name. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>EN TODO MOMENTO <b>EL EMPLEADO</b> SE OBLIGA A RESPETAR Y HACER CUMPLIR LOS DERECHOS DE LOS TITULARES DE LOS DATOS PERSONALES EN TÉRMINOS DE LA LEGISLACIÓN CITADA, SIN PERJUICIO DE LAS DEMÁS OBLIGACIONES Y RESPONSABILIDADES QUE LES CORRESPONDAN CONFORME AL PRESENTE CONVENIO O CUALQUIER OTRA LEGISLACIÓN APLICABLE. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>SEXTA. - USO DE LA INFORMACION CONFIDENCIAL. EL EMPLEADO</b> SE OBLIGA A QUE EN NINGÚN MOMENTO Y POR NINGÚN MOTIVO, PODRÁ DUPLICAR, COPIAR, EDITAR O REPRODUCIR POR NINGÚN MÉTODO, USAR PARA SU PROPIO BENEFICIO O DE TERCERO LA INFORMACIÓN CONFIDENCIAL A LA QUE TUVIERA ACCESO COMO CONSECUENCIA DEL CUMPLIMIENTO DE SUS OBLIGACIONES COMERCIALES, CONTRACTUALES O DE CUALQUIER OTRA ÍNDOLE FRENTE A $company_name. ASIMISMO, SE OBLIGA A NO USAR PARA SU PROPIO BENEFICIO O DE TERCERO LAS HERRAMIENTAS O SISTEMAS DE TRABAJO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LO ESTABLECIDO EN EL PÁRRAFO INMEDIATO ANTERIOR LE SERÁ APLICABLE EN TODO MOMENTO A LAS OBLIGACIONES DE NO COMPETENCIA ESTABLECIDAS EN LA CLÁUSULA SEGUNDA DEL PRESENTE CONVENIO, EN EL ENTENDIDO QUE EL USO DE LA INFORMACIÓN CONFIDENCIAL EN CONTRAVENCIÓN A LA PRESENTE CLÁUSULA RESULTARÁ EN INCUMPLIMIENTO POR PARTE DE <b>EL EMPLEADO</b>, TANTO DE LA OBLIGACIÓN DE CONFIDENCIALIDAD COMO LA OBLIGACIÓN DE NO COMPETENCIA, DE CONFORMIDAD CON EL PRESENTE CONVENIO.  </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>SÉPTIMA. - OBLIGACIONES DE EL EMPLEADO</b> EN TODO MOMENTO DURANTE LA VIGENCIA DEL PRESENTE CONVENIO, <b>EL EMPLEADO</b> ESTARÁ OBLIGADA A: </p>";
        $htmlsection->addHtml($section, $section2);

        $listItemRun = $section->addListItemRun(4, $multilevelListStyleName,[]);
        $listItemRun->addText('CUMPLIR CON LAS OBLIGACIONES DE NO COMPETENCIA ESTABLECIDAS EN LA CLÁUSULA SEGUNDA DEL PRESENTE CONVENIO.',$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(4, $multilevelListStyleName,[]);
        $listItemRun->addText('TOMAR LAS MEDIDAS NECESARIAS PARA PREVENIR EL ROBO O LA PÉRDIDA DE LA INFORMACIÓN CONFIDENCIAL, ASÍ COMO PROTEGERLA CON LA MISMA DILIGENCIA QUE PROTEGE SU PROPIA INFORMACIÓN CONFIDENCIAL;',$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(4, $multilevelListStyleName,[]);
        $listItemRun->addText('RESPONDER POR CUALQUIER VIOLACIÓN A LA OBLIGACIÓN DE NO COMPETENCIA Y/O CONFIDENCIALIDAD AQUÍ ESTABLECIDA, ASÍ COMO A LOS DERECHOS DE PROPIEDAD INTELECTUAL DE' . $company_name .' EN ESTE ACTO, EL EMPLEADO SE OBLIGA A RESPONDER DIRECTAMENTE ANTE '.$company_name. ' POR CUALQUIER VIOLACIÓN A LA NO COMPETENCIA Y/O CONFIDENCIALIDAD QUE SEA COMETIDA POR EL EMPLEADO O EN GENERAL POR CUALQUIER OTRA PERSONA VINCULADA A EL EMPLEADO; Y ;',$bodyNormalStyle);

        $listItemRun = $section->addListItemRun(4, $multilevelListStyleName,[]);
        $listItemRun->addText('DEVOLVER INMEDIATAMENTE A '. $company_name .' TODA LA INFORMACIÓN CONFIDENCIAL, INCLUYENDO LOS DATOS PERSONALES, EN CASO DE QUE ASÍ LO SOLICITE '. $company_name. ' Y/O CUANDO EL PRESENTE CONVENIO SEA TERMINADO O RESCINDIDO POR CUALQUIER MOTIVO. UNA VEZ TERMINADO EL PRESENTE CONVENIO, EL EMPLEADO NO PODRÁ, POR NINGÚN MOTIVO, MANTENER EN SU POSESIÓN INFORMACIÓN CONFIDENCIAL.',$bodyNormalStyle);

        $section2 = "<p><b>OCTAVA. - INCUMPLIMIENTO POR PARTE DE EL EMPLEADO</b>. EN CASO DE QUE <b>EL EMPLEADO</b>, O CUALESQUIER PERSONAS VINCULADAS DE CUALQUIER FORMA CON EL MISMO, INCUMPLA CON ALGUNA DE LAS OBLIGACIONES DERIVADAS DE ESTE CONVENIO, ASÍ COMO LAS OBLIGACIONES RESPECTO A LA NO COMPETENCIA, A LA INFORMACIÓN CONFIDENCIAL Y/O LAS OBLIGACIONES ESTABLECIDAS EN LA CLÁUSULA SÉPTIMA DEL PRESENTE CONVENIO, <b>EL EMPLEADO</b> ESTARÁ SUJETA A LA PENA CONVENCIONAL (SEGÚN DICHO TÉRMINO SE DEFINE MÁS ADELANTE) ESTABLECIDA EN LA CLÁUSULA NOVENA DEL PRESENTE CONVENIO; SIN PERJUICIO DE QUE DEBERÁ RESPONDER A LOS DAÑOS Y PERJUICIOS OCASIONADOS A $company_name, SUS EMPLEADOS, FUNCIONARIOS, ACCIONISTAS, O CUALQUIER OTRA PERSONA RELACIONADA CON $company_name. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LO ANTERIOR SIN PERJUICIO DEL DERECHO DE $company_name DE EJERCITAR LAS DEMÁS ACCIONES LEGALES, SANCIONES Y MULTAS QUE PROCEDAN POR LA VIOLACIÓN A LOS DERECHOS DE PROPIEDAD INTELECTUAL DE $company_name, INCLUIDAS, DE MANERA ENUNCIATIVA MAS NO LIMITATIVA, EN LA LEY DE LA PROPIEDAD INDUSTRIAL Y EN EL CÓDIGO PENAL FEDERAL O CUALESQUIER LEYES QUE EN UN FUTURA LAS SUSTITUYAN Y/O SEA APLICABLE.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>EL EMPLEADO</b> EN ESTE ACTO SE OBLIGA A SACAR EN PAZ Y A SALVO A $company_name, SUS EMPLEADOS, FUNCIONARIOS, ACCIONISTAS, O CUALQUIER OTRA PERSONA RELACIONADA CON $company_name DE CUALQUIER RECLAMACIÓN, DEMANDA, DENUNCIA, PROCEDIMIENTO JUDICIAL O EXTRAJUDICIAL O INVESTIGACIÓN DE CUALQUIER NATURALEZA, QUE SEAN CONSECUENCIA DEL INCUMPLIMIENTO DE LAS OBLIGACIONES DE NO COMPETENCIA Y/O CONFIDENCIALIDAD PREVISTAS EN EL PRESENTE CONVENIO POR PARTE DE <b>EL EMPLEADO</b>, O CUALESQUIER PERSONAS VINCULADAS DE CUALQUIER FORMA CON EL MISMO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LAS OBLIGACIONES A CARGO DE <b>EL EMPLEADO</b> PREVISTAS EN LA PRESENTE CLÁUSULA NO REQUERIRÁN DE LA EXISTENCIA DE UNA SENTENCIA JUDICIAL QUE SE PRONUNCIE SOBRE EL INCUMPLIMIENTO POR PARTE DE <b>EL EMPLEADO</b> A LAS OBLIGACIONES PREVISTAS EN EL PRESENTE CONVENIO. </p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>NOVENA. - PENA CONVENCIONAL.  EL EMPLEADO</b> ACEPTA Y SE OBLIGA EXPRESAMENTE A QUE EN CASO DE INCURRIR EN INCUMPLIMIENTO DE CUALQUIERA DE SUS OBLIGACIONES DERIVADAS DEL PRESENTE CONVENIO, INCLUYENDO DE MANERA ENUNCIATIVA MÁS NO LIMITATIVA, LAS OBLIGACIONES DE NO COMPETENCIA, DE CONFIDENCIALIDAD Y/O LAS OBLIGACIONES ESTABLECIDAS EN LA CLÁUSULA SÉPTIMA DEL PRESENTE CONVENIO, DEBERÁ PAGAR A $company_name POR CONCEPTO DE PENA CONVENCIONAL LA CANTIDAD DE $5,000,000.00 (CINCO MILLONES DE PESOS 00/100 MONEDA NACIONAL) (LA “PENA CONVENCIONAL”). LA PENA CONVENCIONAL DEBERÁ SER PAGADA DENTRO DE LOS 2 (DOS) DÍAS HÁBILES CONTADOS A PARTIR DE QUE SE ACTUALICE EL INCUMPLIMIENTO CONFORME AL PRESENTE CONVENIO, EN EL DOMICILIO DE $company_name QUE SE SEÑALA EN ESTE CONVENIO O EN LA CUENTA BANCARIA QUE SEÑALE PARA TALES EFECTOS $company_name.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>EN CASO DE INCUMPLIMIENTO, <b>EL EMPLEADO</b> FACULTA A $company_name A COMPENSAR CUALESQUIER MONTOS QUE EXISTAN O PUDIEREN EXISTIR EN UN FUTURO A FAVOR DE <b>EL EMPLEADO</b>, INCLUYENDO, COMISIONES, SUELDOS FUTUROS Y/O REPARTOS DE UTILIDADES, LAS CANTIDADES NECESARIAS PARA CUBRIR EL IMPORTE DE LA PENA CONVENCIONAL DE ACUERDO CON LO PREVISTO EN ESTA CLÁUSULA.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA.-  VIGENCIA.</b> EL PRESENTE CONVENIO SURTIRÁ SUS EFECTOS A PARTIR DEL DÍA DE SU FIRMA Y HASTA 5 (CINCO) AÑOS, ES DECIR, (60 MESES) A PARTIR DE QUE SE TERMINE CUALQUIER RELACIÓN COMERCIAL Y/O CONTRACTUAL O DE CUALQUIER OTRA ÍNDOLE QUE EXISTA ACTUALMENTE O EN EL FUTURO ENTRE <b>EL EMPLEADO</b> Y $company_name Y/O CUALQUIERA DE SUS SUBSIDIARIAS, ACCIONISTAS Y/O PROPIETARIOS (EN LO SUCESIVO LA “<b><u>VIGENCIA</u></b>”).</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA PRIMERA. - CESIÓN. EL EMPLEADO</b> SE OBLIGA A NO CEDER, TRASPASAR O DE CUALQUIER FORMA TRANSMITIR LOS DERECHOS Y/U OBLIGACIONES A SU FAVOR Y/O CARGO DERIVADOS DEL PRESENTE CONVENIO, EN EL ENTENDIDO DE QUE SI <b>EL EMPLEADO</b> INCUMPLE CON ESTA OBLIGACIÓN ESTARÁ SUJETO A LA PENA CONVENCIONAL ESTABLECIDA EN LA CLÁUSULA NOVENA DEL PRESENTE CONVENIO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA SEGUNDA. - MODIFICACIONES</b>. LA NOVACIÓN DEL PRESENTE CONVENIO NUNCA SE PRESUMIRÁ, POR LO QUE CUALQUIER ADICIÓN O MODIFICACIÓN QUE LAS PARTES DESEEN REALIZAR AL CONTENIDO DEL PRESENTE CONVENIO, DEBERÁ EFECTUARSE MEDIANTE CONVENIO REALIZADO POR ESCRITO Y FIRMADO POR LAS PARTES, EN DONDE EXPRESAMENTE CONSTEN LOS CAMBIOS O ACUERDOS ADICIONALES.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>EN CASO DE QUE SE REALICE CUALQUIER MODIFICACIÓN AL PRESENTE CONVENIO, DE CONFORMIDAD CON LO ESTABLECIDO EN EL PÁRRAFO ANTERIOR, LA MISMA ÚNICAMENTE AFECTARÁ LA MATERIA SOBRE LA QUE EXPRESAMENTE VERSE, POR LO TANTO, SE MANTENDRÁN EN VIGOR LAS DEMÁS CLÁUSULAS DE ESTE ACUERDO DE VOLUNTADES EN TODOS SUS TÉRMINOS Y CONDICIONES.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA TERCERA. - DOMICILIOS</b>. LAS PARTES SEÑALAN COMO SUS DOMICILIOS CONVENCIONALES PARA TODO LO RELATIVO AL PRESENTE CONVENIO, LOS SIGUIENTES</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>EMPLEADO: $address</b></p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>$company_name</b>: $company_address</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>TODOS LOS AVISOS Y/O NOTIFICACIONES QUE LAS PARTES DEBAN DARSE O EFECTUARSE CON MOTIVO DE LA CELEBRACIÓN Y CUMPLIMIENTO DE ESTE CONVENIO, SERÁN POR ESCRITO Y SE ENTREGARÁN PERSONALMENTE O POR CORREO CERTIFICADO, EN AMBOS CASOS CON ACUSE DE RECIBO, DIRIGIDO A LOS DOMICILIOS ANTES MENCIONADOS O A CUALQUIER OTRO DOMICILIO QUE CADA PARTE SEÑALE CON POSTERIORIDAD.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>CUALQUIERA DE LAS PARTES PODRÁ, DE TIEMPO EN TIEMPO, SEÑALAR COMO SU DOMICILIO PARA LOS FINES DE ESTE ACUERDO CUALQUIER OTRO DOMICILIO, PREVIO AVISO POR ESCRITO AL RESPECTO ENTREGADO CON 10 (DIEZ) DÍAS NATURALES DE ANTICIPACIÓN DEL CAMBIO A LA OTRA PARTE. MIENTRAS QUE NO SE RECIBA UNA NOTIFICACIÓN DE CAMBIO DE DOMICILIO DE CONFORMIDAD CON LO DISPUESTO EN LA PRESENTE CLÁUSULA, LAS NOTIFICACIONES EFECTUADAS A LOS DOMICILIOS ARRIBA INDICADOS SERÁN EFECTIVAS.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA CUARTA. - ACUERDOS, CONTRATOS Y CONVENIOS PREVIOS</b>. LAS PARTES MANIFIESTAN EXPRESAMENTE QUE ESTÁN CONFORMES CON QUE LAS CLÁUSULAS DEL PRESENTE CONVENIO REFLEJAN FIEL Y PUNTUALMENTE LA TOTALIDAD DE LOS ACUERDOS TOMADOS POR ELLAS, Y QUE SUSTITUYEN CUALQUIER OTRO ACUERDO, CONTRATO O CONVENIO QUE LAS PARTES O SUS REPRESENTANTES O APODERADOS PUDIERAN HABER CELEBRADO PREVIAMENTE.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA QUINTA. - TÍTULOS DE LAS CLÁUSULAS</b>. LOS TÍTULOS O DENOMINACIÓN DE LAS CLÁUSULAS DE ESTE CONVENIO TIENEN COMO ÚNICO OBJETO FACILITAR SU IDENTIFICACIÓN, POR LO QUE NO DETERMINARÁN EN FORMA ALGUNA SU INTERPRETACIÓN O CONTENIDO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA SEXTA. - DIVISIBILIDAD</b>: LA INVALIDEZ DE CUALQUIERA DE LAS DISPOSICIONES CONTENIDAS EN EL PRESENTE CONVENIO NO AFECTARÁ LA VALIDEZ DE CUALQUIER OTRA DISPOSICIÓN Y LAS DISPOSICIONES PREVALECIENTES SE MANTENDRÁN EN PLENO VIGOR Y EFECTO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p><b>DÉCIMA SÉPTIMA. - LEYES Y TRIBUNALES COMPETENTES</b>. LAS PARTES ACUERDAN SOMETER LA INTERPRETACIÓN Y CUMPLIMIENTO DEL PRESENTE CONVENIO A LAS LEYES Y TRIBUNALES DEL ESTADO DE MÉXICO, RENUNCIANDO, POR TANTO, A CUALQUIER FUERO DEL DOMICILIO O VECINDAD QUE TUVIEREN O LLEGAREN A ADQUIRIR EN EL FUTURO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>LEÍDO QUE FUE POR LAS PARTES EL PRESENTE CONVENIO, LO RATIFICARON Y FIRMARON PARA CONSTANCIA, EN EL ESTADO DE MÉXICO, EL DÍA <b>$date_admission</b></p>";
        $htmlsection->addHtml($section, $section2);

        $section->addText(''); 
        $section->addText(''); 
        $section->addText(''); 

        $cellRowSpan = array('width' => 5000);
        $table = $section->addTable([]);
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('EMPLEADO',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText($company_name,$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('__________________________________',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('__________________________________',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText($name. ' '. $lastname. '<w:br/>'.$position,$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText($social_reason. '<w:br/>'.'REPRESENTADA POR:'. '<w:br/>'.$employer ,$bodyBoldStyle, $center);
       
        $section->addPageBreak();

        $section->addText(
            'ANEXO 1',
            $bodyBoldStyle,$center
        ); 
        
        $section->addText(
            'LISTA DE CLIENTES',
            $textLineBoldCenter,$center
        );

        $section2 = "<p>LA PRESENTE LISTA DE CLIENTES INCLUIRÁ A TODAS LAS SOCIEDADES SUBSIDIARIAS Y FILIALES QUE FORMEN O LLEGAREN A FORMAR PARTE DEL GRUPO CORPORATIVO DE LAS SOCIEDADES CONOCIDAS CON LOS NOMBRES COMERCIALES QUE SE SEÑALAN A CONTINUACIÓN:</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>ASIMISMO, LA INFORMACIÓN CONFIDENCIAL SEÑALADA EN EL PRESENTE CONVENIO. INCLUYE TODA INFORMACIÓN POR LA VÍA QUE SEA QUE EL <b>EMPLEADO</b> OBTENGA DE LOS CLIENTES AQUÍ SEÑALADOS Y LOS QUE SE SIGAN GENERANDO MIENTRAS ESTÉ VIGENTE EL PRESENTE INSTRUMENTO.</p>";
        $htmlsection->addHtml($section, $section2);

        $section2 = "<p>ESTE DOCUMENTO ES ANEXO QUE FORMA PARTE INTEGRAL DEL CONVENIO DE NO COMPETENCIA Y CONFIDENCIALIDAD QUE CELEBRAN POR UNA PARTE $social_reason, Y POR OTRA PARTE EL/LA C. $name $lastname DE FECHA DEL DÍA FECHA DE INGRESO $date_admission.</p>";
        $htmlsection->addHtml($section, $section2);

        $section->addText(''); 
        $section->addText(''); 
        $section->addText(''); 

        $cellRowSpan = array('width' => 5000);
        $table = $section->addTable([]);
        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('EMPLEADO',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText($company_name,$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText('__________________________________',$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText('__________________________________',$bodyBoldStyle, $center);

        $table->addRow();
        $table->addCell(5000, $cellRowSpan)->addText($name. ' '. $lastname. '<w:br/>'.$position,$bodyBoldStyle, $center);
        $table->addCell(5000, $cellRowSpan)->addText($social_reason. '<w:br/>'.'REPRESENTADA POR:'. '<w:br/>'.$employer ,$bodyBoldStyle, $center);
       
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . 'NO COMPETE' . strtoupper($company_name) . ' ' . strtoupper($name) .' '. strtoupper($lastname) . '.docx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");
        
    }
    
}

