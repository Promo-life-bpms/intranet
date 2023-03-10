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
        return redirect()->back()->with('message', 'Información guardada correctamente');;
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
            $this->determinateContract();
        }
    }

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

    public function determinateContract()
    {
        // Creating the new document...
    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    /* Note: any element you append to a document must reside inside of a Section. */

    // Adding an empty Section to the document...
    $section = $phpWord->addSection();
    // Adding Text element to the Section having font styled by default...
    $section->addText(
        '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
    );

    /*
    * Note: it's possible to customize font style of the Text element you add in three ways:
    * - inline;
    * - using named font style (new font style object will be implicitly created);
    * - using explicitly created font style object.
    */

    // Adding Text element with font customized inline...
    $section->addText(
        '"Great achievement is usually born of great sacrifice, '
            . 'and is never the result of selfishness." '
            . '(Napoleon Hill)',
        array('name' => 'Tahoma', 'size' => 10)
    );

    // Adding Text element with font customized using named font style...
    $fontStyleName = 'oneUserDefinedStyle';
    $phpWord->addFontStyle(
        $fontStyleName,
        array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
    );
    $section->addText(
        '"The greatest accomplishment is not in never falling, '
            . 'but in rising again after you fall." '
            . '(Vince Lombardi)',
        $fontStyleName
    );

    // Adding Text element with font customized using explicitly created font style object...
    $fontStyle = new \PhpOffice\PhpWord\Style\Font();
    $fontStyle->setBold(true);
    $fontStyle->setName('Tahoma');
    $fontStyle->setSize(13);
    $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
    $myTextElement->setFontStyle($fontStyle);

    
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . 'CONTRATO DETERMINADO' . '.doc');
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
                'size' => 14, 'bold' => false,
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
            strtoupper($name)  .' '. strtoupper($lastname),
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
