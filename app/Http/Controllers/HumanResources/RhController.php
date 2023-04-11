<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use App\Models\EmployeeCompany;
use App\Models\Company;
use App\Models\CompanyEmployee;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Postulant;
use App\Models\PostulantBeneficiary;
use App\Models\PostulantDetails;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserBeneficiary;
use App\Models\UserDetails;
use App\Models\UserDownMotive;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class RhController extends Controller
{
    public function stadistics()
    {
        $start = null;
        $end = null;

        $totalEmpleados = $this->totalempleados($start, $end);
        $nuevosingresos = $this->nuevosingresos($start, $end);
        $bajas = $this->bajas($start, $end);

        $motive =  $this->motiveDown($start, $end);

       
        return view('rh.stadistics', compact('totalEmpleados', 'nuevosingresos', 'bajas', 'start', 'end' , 'motive'));
    }

    public function filterstadistics(Request $request)
    {
        $start = $request->start;
        $end= $request->end;
        $totalEmpleados = $this->totalempleados($start, $end);
        $nuevosingresos = $this->nuevosingresos($start, $end);
        $bajas = $this->bajas($start, $end);
      
        return view('rh.stadistics', compact('totalEmpleados', 'nuevosingresos', 'bajas', 'start', 'end'));
    }

    public function totalempleados($start, $end)
    {
        $data = [];
        //$Promolife = CompanyEmployee::all()->where('company_id', 1);
        $promolife = CompanyEmployee::where('company_id', 1)->get();
        $bh_trade_market = CompanyEmployee::where('company_id', 2)->get();
        $promo_zale = CompanyEmployee::where('company_id', 3)->get();
        $trade_market57 = CompanyEmployee::where('company_id', 4)->get();


        $totalPLFilter = 0;
        $totalBHFilter = 0;
        $totalTM57Filter = 0;
        $totalPZFilter = 0;


        if($start == null && $end == null){
           

            $data = (object)[
                'promolife' => count($promolife),
                'bh_trade_market' => count($bh_trade_market),
                'promo_zale' => count($promo_zale),
                'trade_market57' => count($trade_market57),
                'total' =>count($promolife) + count($bh_trade_market) +  count($promo_zale) + count($trade_market57)
            ];
            return $data;
        }else{

            $format_start =date('Y-m-d', strtotime($start));
            $format_end =date('Y-m-d', strtotime($end));

            foreach($promolife as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user != null){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalPLFilter = $totalPLFilter + 1;
                    }
                }
            }

            foreach($bh_trade_market as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                
                if($user){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalBHFilter = $totalBHFilter + 1;
                    }   
                }
            }

            foreach($trade_market57 as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalTM57Filter = $totalTM57Filter + 1;
                    }
                }
            }

            foreach($promo_zale as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalPZFilter  = $totalPZFilter  + 1;
                    }
                }
                
            }


            $data = (object)[
                'promolife' => $totalPLFilter,
                'bh_trade_market' =>$totalBHFilter,
                'promo_zale' =>$totalPZFilter,
                'trade_market57' =>$totalTM57Filter,
                'total' => $totalPLFilter + $totalBHFilter + $totalPZFilter + $totalTM57Filter
            ];

            return $data;

        }
        
    }

    public function nuevosingresos($start,$end)
    {  
        $data = [];
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $yearpresent = $date->format('Y');
        $monthpresent = $date->format('m');
        $employee = Employee::all();

        $promolife = CompanyEmployee::where('company_id', 1)->get();
        $bh_trade_market = CompanyEmployee::where('company_id', 2)->get();
        $promo_zale = CompanyEmployee::where('company_id', 3)->get();
        $trade_market57 = CompanyEmployee::where('company_id', 4)->get();

        $totalPLFilter = 0;
        $totalBHFilter = 0;
        $totalTM57Filter = 0;
        $totalPZFilter = 0;

        $format_start =date('Y-m-d', strtotime($start));
        $format_end =date('Y-m-d', strtotime($end));
        if($start == null && $end == null){


            foreach($promolife as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user != null){

                    if ( $user->status == 1 ) {

                        /* $employee = Employee::where() */
                        $admission = explode('-',$user->employee->date_admission);
                        $year = $admission[0];
                        $mont=$admission[1];
                           
                        if ($year == $yearpresent && $mont== $monthpresent) {
                            $totalPLFilter = $totalPLFilter + 1;
                        }   
                    }
                }
            }

            foreach($bh_trade_market as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user != null){
                    if ( $user->status == 1 ) {
                        /* $employee = Employee::where() */
                        $admission = explode('-',$user->employee->date_admission);
                        $year = $admission[0];
                        $mont=$admission[1];
                           
                        if ($year == $yearpresent && $mont== $monthpresent) {
                            $totalBHFilter = $totalBHFilter + 1;
                        }   
                    }
                }
            }


            foreach($promo_zale as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user != null){

                    if ( $user->status == 1 ) {

                        /* $employee = Employee::where() */
                        $admission = explode('-',$user->employee->date_admission);
                        $year = $admission[0];
                        $mont=$admission[1];
                           
                        if ($year == $yearpresent && $mont== $monthpresent) {
                            $totalPLFilter = $totalPLFilter + 1;
                        }   
                    }
                }
            }


            foreach($trade_market57 as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user != null){

                    if ( $user->status == 1 ) {
                        /* $employee = Employee::where() */
                        $admission = explode('-',$user->employee->date_admission);
                        $year = $admission[0];
                        $mont=$admission[1];
                           
                        if ($year == $yearpresent && $mont== $monthpresent) {
                            $totalTM57Filter = $totalTM57Filter + 1;
                        }   
                    }
                }
            }


            $data = (object)[
                'promolife' => $totalPLFilter,
                'bh_trade_market' =>$totalBHFilter,
                'promo_zale' =>$totalPZFilter,
                'trade_market57' =>$totalTM57Filter,
                'total' => $totalPLFilter + $totalBHFilter + $totalPZFilter + $totalTM57Filter
            ];

            return $data;


           /*  foreach (Employee::all() as $employee) {
                if ($employee->date_admission ) {
                    if ($employee->date_admission != null && $employee->user->status == 1) {
                        $admission = explode('-', $employee->date_admission);
                        $year = $admission[0];
                        $mont=$admission[1];
                       
                        if ($year == $yearpresent && $mont== $monthpresent) {
                            array_push($data, $employee);
                        }   
                    }
                }
            } */
        }else{

            $format_start =date('Y-m-d', strtotime($start));
            $format_end =date('Y-m-d', strtotime($end));

            foreach($promolife as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user != null){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalPLFilter = $totalPLFilter + 1;
                    }
                }
            }

            foreach($bh_trade_market as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                
                if($user){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalBHFilter = $totalBHFilter + 1;
                    }   
                }
            }

            foreach($trade_market57 as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalTM57Filter = $totalTM57Filter + 1;
                    }
                }
            }

            foreach($promo_zale as $company){
                $user = User::where('id', $company->employee_id)->where('status',1)->get()->last();
                if($user){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalPZFilter  = $totalPZFilter  + 1;
                    }
                }

                $data = (object)[
                    'promolife' => $totalPLFilter,
                    'bh_trade_market' =>$totalBHFilter,
                    'promo_zale' =>$totalPZFilter,
                    'trade_market57' =>$totalTM57Filter,
                    'total' => $totalPLFilter + $totalBHFilter + $totalPZFilter + $totalTM57Filter
                ];
                
            }
         
            return  $data;

        }
        
       
    }

    public function bajas($start, $end)
    {
        $data=[];
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $yearpresent = $date->format('Y');
        $monthpresent = $date->format('m');

        $promolife = CompanyEmployee::where('company_id', 1)->get();
        $bh_trade_market = CompanyEmployee::where('company_id', 2)->get();
        $promo_zale = CompanyEmployee::where('company_id', 3)->get();
        $trade_market57 = CompanyEmployee::where('company_id', 4)->get();


        $totalPLFilter = 0;
        $totalBHFilter = 0;
        $totalTM57Filter = 0;
        $totalPZFilter = 0;
        $allfilter = 0;

        if($start == null && $end == null){
            foreach (Employee::all() as $employee) {
                if($employee->user->status ==2){
                    if ($employee->date_admission != null) {
                        $admission = explode('-', $employee->date_admission);
                        $year = $admission[0];
                        $mont = $admission[1];
                        if ($year == $yearpresent && $mont== $monthpresent) {
                            array_push($data,(object)[
                                'id' => $employee->user->id
                            ]);

                            $allfilter = $allfilter = 1;
                        }


                    }
                }
            }

            $data = (object)[
                'promolife' => count($promolife),
                'bh_trade_market' => count($bh_trade_market),
                'promo_zale' => count($promo_zale),
                'trade_market57' => count($trade_market57),
                'total' =>  $allfilter,
            ];
            
            
            return $data;

        }else{

            $format_start =date('Y-m-d', strtotime($start));
            $format_end =date('Y-m-d', strtotime($end));

            foreach($promolife as $company){
                $user = User::where('id', $company->employee_id)->where('status',2)->get()->last();
                if($user != null){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalPLFilter = $totalPLFilter + 1;
                    }
                }
            }

            foreach($bh_trade_market as $company){
                $user = User::where('id', $company->employee_id)->where('status',2)->get()->last();
                
                if($user){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalBHFilter = $totalBHFilter + 1;
                    }   
                }
            }

            foreach($trade_market57 as $company){
                $user = User::where('id', $company->employee_id)->where('status',2)->get()->last();
                if($user){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalTM57Filter = $totalTM57Filter + 1;
                    }
                }
            }

            foreach($promo_zale as $company){
                $user = User::where('id', $company->employee_id)->where('status',2)->get()->last();
                if($user){
                    if($user->employee->date_admission >= $format_start && $user->employee->date_admission <= $format_end ){
                        $totalPZFilter  = $totalPZFilter  + 1;
                    }
                }

                $data = (object)[
                    'promolife' => $totalPLFilter,
                    'bh_trade_market' =>$totalBHFilter,
                    'promo_zale' =>$totalPZFilter,
                    'trade_market57' =>$totalTM57Filter,
                    'total' => $totalPLFilter + $totalBHFilter + $totalPZFilter + $totalTM57Filter
                ];
                
            }
         
            return  $data;

        }
       
      
    }

    public function motiveDown($start, $end)
    {
        $departments = Department::all();
        $users = User::where('status', 2)->get();
        $department_name = [];
        $department_total = [];
        $department_data = [];

        $grownd_data = [];

        $grownd_department_data = [];
    
        $count_department = 0;

        
        $down_department_data = [];
        $filter_down_department_data = [];

        if($start == null && $end == null){

           
            foreach($users as $user){

              
                foreach($departments as $department){
                    if($user->employee->position->department->name == $department->name){

                        //Baja por departamento
                        array_push($department_data, (object)[
                            'department' => $department->name,
                            'user_id' => $user->id,
                        ]);

                        //Crecimiento laboral
                        $growth_salary = 0;
                        $growth_promotion = 0;
                        $growth_activity = 0;

                        
                        $climate_partnet = 0;
                        $climate_manager = 0;
                        $climate_boss= 0;

                        $psicosocial_workloads = 0;
                        $psicosocial_appreciation = 0;
                        $psicosocial_violence = 0;
                        $psicosocial_workday = 0;

                        $demographics_distance = 0;
                        $demographics_physical = 0;
                        $demographics_personal = 0;
                        $demographics_school = 0;

                        $health_personal = 0;
                        $health_familiar = 0;

                        $other_motive = 0;
                      
                        if($user->userDownMotive != null){

                            if($user->userDownMotive->growth_salary == true){
                                $growth_salary = $growth_salary + 1;
                            }
                        
                            if($user->userDownMotive->growth_promotion == true){
                                $growth_promotion = $growth_promotion + 1;
                            }

                            if($user->userDownMotive->growth_activity == true){
                                $growth_activity = $growth_activity + 1;
                            }



                            if($user->userDownMotive->climate_partnet == true){
                                $climate_partnet = $climate_partnet + 1;
                            }
                        
                            if($user->userDownMotive->climate_manager == true){
                                $climate_manager = $climate_manager + 1;
                            }

                            if($user->userDownMotive->climate_boss == true){
                                $climate_boss = $climate_boss + 1;
                            }



                            if($user->userDownMotive->psicosocial_workloads == true){
                                $psicosocial_workloads = $psicosocial_workloads + 1;
                            }
                        
                            if($user->userDownMotive->psicosocial_appreciation == true){
                                $psicosocial_appreciation = $psicosocial_appreciation + 1;
                            }

                            if($user->userDownMotive->psicosocial_violence == true){
                                $psicosocial_violence = $psicosocial_violence + 1;
                            }

                            if($user->userDownMotive->psicosocial_workday == true){
                                $psicosocial_workday = $psicosocial_workday + 1;
                            }




                            if($user->userDownMotive->demographics_distance == true){
                                $demographics_distance = $demographics_distance + 1;
                            }
                        
                            if($user->userDownMotive->demographics_physical == true){
                                $demographics_physical = $demographics_physical + 1;
                            }

                            if($user->userDownMotive->demographics_personal == true){
                                $demographics_personal = $demographics_personal + 1;
                            }
                            
                            if($user->userDownMotive->demographics_school == true){
                                $demographics_school = $demographics_school + 1;
                            }


                            if($user->userDownMotive->health_personal == true){
                                $health_personal = $health_personal + 1;
                            }
                        
                            if($user->userDownMotive->health_familiar == true){
                                $health_familiar = $health_familiar + 1;
                            }


                            if($user->userDownMotive->other_motive != null){
                                $other_motive = $other_motive + 1;
                            }
                            


                            array_push($grownd_department_data, (object)[
                                'department' => $department->name,
                                'growth_salary' => $growth_salary,
                                'growth_promotion' => $growth_promotion,
                                'growth_activity' => $growth_activity,
                                
                                'climate_partnet' => $climate_partnet,
                                'climate_manager' => $climate_manager,
                                'climate_boss' => $climate_boss,
            
                                'psicosocial_workloads' => $psicosocial_workloads,
                                'psicosocial_appreciation' => $psicosocial_appreciation,
                                'psicosocial_violence' => $psicosocial_violence,
                                'psicosocial_workday' => $psicosocial_workday,
            
                                'demographics_distance' => $demographics_distance,
                                'demographics_physical' => $demographics_physical,
                                'demographics_personal' => $demographics_personal,
                                'demographics_school' => $demographics_school,
            
                                'health_personal' => $health_personal,
                                'health_familiar' => $health_familiar,

                                'other_motive' => $other_motive,
                            ]);
                        }           
                        
                    }    
                }
             
            }
        }

        
        foreach($departments as $department){

            $department_growth_salary = 0;
            $department_growth_promotion = 0;
            $department_growth_activity = 0;

            $department_climate_partnet = 0;
            $department_climate_manager = 0;
            $department_climate_boss= 0;

            $department_psicosocial_workloads = 0;
            $department_psicosocial_appreciation = 0;
            $department_psicosocial_violence = 0;
            $department_psicosocial_workday = 0;

            $department_demographics_distance = 0;
            $department_demographics_physical = 0;
            $department_demographics_personal = 0;
            $department_demographics_school = 0;

            $department_health_personal = 0;
            $department_health_familiar = 0;

            $department_other_motive = 0;           
            
            //Filtrado  de baja por departamento
            foreach($department_data as $find_department){
                if($find_department->department ==  $department->name){
                    $count_department = $count_department + 1; 
                } 
            }


            foreach($grownd_department_data  as $grownd_department){
                //Crecimiento laboral
                if($grownd_department->department == $department->name){

                    if($grownd_department->growth_salary == 1 ){
                        $department_growth_salary = $department_growth_salary + 1;
                    } 

                    if($grownd_department->growth_promotion == 1 ){
                        $department_growth_promotion = $department_growth_promotion + 1;
                    }

                    if($grownd_department->growth_activity == 1 ){
                        $department_growth_activity = $department_growth_activity + 1;
                    }
                    
                    
                    if($grownd_department->climate_partnet == 1 ){
                        $department_climate_partnet = $department_climate_partnet + 1;
                    } 

                    if($grownd_department->climate_manager == 1 ){
                        $department_climate_manager = $department_climate_manager + 1;
                    }

                    if($grownd_department->climate_boss == 1 ){
                        $department_climate_boss = $department_climate_boss + 1;
                    }


                    if($grownd_department->psicosocial_workloads == 1 ){
                        $department_psicosocial_workloads = $department_psicosocial_workloads + 1;
                    } 

                    if($grownd_department->psicosocial_appreciation == 1 ){
                        $department_psicosocial_appreciation = $department_psicosocial_appreciation + 1;
                    }

                    if($grownd_department->psicosocial_violence == 1 ){
                        $department_psicosocial_violence = $department_psicosocial_violence + 1;
                    }

                    if($grownd_department->psicosocial_workday == 1 ){
                        $department_psicosocial_workday = $department_psicosocial_workday + 1;
                    }

                    if($grownd_department->demographics_distance == 1 ){
                        $department_demographics_distance = $department_demographics_distance + 1;
                    } 

                    if($grownd_department->demographics_physical == 1 ){
                        $department_demographics_physical = $department_demographics_physical + 1;
                    }

                    if($grownd_department->demographics_personal == 1 ){
                        $department_demographics_personal = $department_demographics_personal + 1;
                    }

                    if($grownd_department->demographics_school == 1 ){
                        $department_demographics_school = $department_demographics_school + 1;
                    }
                    
                    if($grownd_department->health_personal == 1 ){
                        $department_health_personal = $department_health_personal + 1;
                    } 

                    if($grownd_department->health_familiar == 1 ){
                        $department_health_familiar = $department_health_familiar + 1;
                    }

                    if($grownd_department->other_motive != null ){
                        $department_other_motive = $department_other_motive + 1;
                    }
                   
                }
            }
 
            //Filtro de departamentos vacios 
            if($count_department != 0){
                array_push($department_name,  $department->name);
                array_push($department_total,  $count_department);
                array_push($filter_down_department_data, (object)[
                    'department' => $department->name,
                    'growth_salary' => $department_growth_salary,
                    'growth_promotion' => $department_growth_promotion,
                    'growth_activity' => $department_growth_activity,
                
                    'climate_partnet' => $department_climate_partnet,
                    'climate_manager' => $department_climate_manager,
                    'climate_boss' => $department_climate_boss,

                    'psicosocial_workloads' => $department_psicosocial_workloads,
                    'psicosocial_appreciation' => $department_psicosocial_appreciation,
                    'psicosocial_violence' => $department_psicosocial_violence,
                    'psicosocial_workday' => $department_psicosocial_workday,

                    'demographics_distance' => $department_demographics_distance,
                    'demographics_physical' => $department_demographics_physical,
                    'demographics_personal' => $department_demographics_personal,
                    'demographics_school' => $department_demographics_school,

                    'health_personal' => $department_health_personal,
                    'health_familiar' => $department_health_familiar,

                    'other_motive' => $department_other_motive
               
                ]);
            }
          
            $count_department = 0;
            
        }

        array_push($down_department_data, (object)[ 
            'department' => $department_name,
            'total' => $department_total,
            'down' => $filter_down_department_data
        ] );

        return $down_department_data;

    }

    public function postulants()
    {  
        $postulants_data = [];
        $postulants = Postulant::all()->whereIn('status', ['postulante','candidato']);
        
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
        $user = User::where('id',$user)->get()->first();
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
        $request->validate([
            'date_down' => 'required',
        ]);

        $user = User::where('id',$request->user_id)->get()->last();

        if($user->userDetails == null){
            $user_details = new UserDetails();
            $user_details->user_id = $user->id;
            $user_details->birthdate = $user->employee->birthday_date;
            $user_details->date_admission = $user->employee->date_admission;
            $user_details->date_down = $request->date_down;
            $user_details->save();
        }else{
            DB::table('users_details')->where('user_id', $request->user_id)->update([
                'date_down' => $request->date_down
            ]);
        }

        if($user->userDownMotive == null){
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
            $create_user_motive->other_motive  = $request->other_motive;
                
            $create_user_motive->save();
        }else{
            DB::table('users_down_motive')->where('user_id', intval($request->user_id))->update([
            'growth_salary'  => $request->growth_salary,
            'growth_promotion'  => $request->growth_promotion,
            'growth_activity'  => $request->growth_activity,
            'climate_partnet'  => $request->climate_partnet,
            'climate_manager'  => $request->climate_manager,
            'climate_boss'  => $request->climate_boss,
            'psicosocial_workloads'  => $request->psicosocial_workloads,
            'psicosocial_appreciation'   => $request->psicosocial_appreciation,
            'psicosocial_violence' => $request->psicosocial_violence,
            'psicosocial_workday'  => $request->psicosocial_workday,
            'demographics_distance'  => $request->demographics_distance,
            'demographics_physical'  => $request->demographics_physical,
            'demographics_personal'  => $request->demographics_personal,
            'demographics_school'  => $request->demographics_school,
            'health_personal'  => $request->health_personal,
            'health_familiar'  => $request->health_familiar,
            'other_motive'   => $request->other_motive,
            ]);

        }

        return redirect()->back()->with('message', 'Información guardada correctamente, ya puedes generar baja del empleado');
        
    }

    public function createMotiveDown(Request $request)
    {

        $down_motive = UserDownMotive::all()->where('user_id', intval($request->user_id))->last();

        if($down_motive == null){
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
            $create_user_motive->other_motive  = $request->other_motive;
                
            $create_user_motive->save();
        }else{
            DB::table('users_down_motive')->where('user_id', intval($request->user_id))->update([
            'growth_salary'  => $request->growth_salary,
            'growth_promotion'  => $request->growth_promotion,
            'growth_activity'  => $request->growth_activity,
            'climate_partnet'  => $request->climate_partnet,
            'climate_manager'  => $request->climate_manager,
            'climate_boss'  => $request->climate_boss,
            'psicosocial_workloads'  => $request->psicosocial_workloads,
            'psicosocial_appreciation'   => $request->psicosocial_appreciation,
            'psicosocial_violence' => $request->psicosocial_violence,
            'psicosocial_workday'  => $request->psicosocial_workday,
            'demographics_distance'  => $request->demographics_distance,
            'demographics_physical'  => $request->demographics_physical,
            'demographics_personal'  => $request->demographics_personal,
            'demographics_school'  => $request->demographics_school,
            'health_personal'  => $request->health_personal,
            'health_familiar'  => $request->health_familiar,
            'other_motive'   => $request->other_motive,
            ]);

        }
        
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
        
        if($request->status <> "postulante")
            $request->validate([
            'date_admission' => 'required',
            'birthdate' => 'required',
        ]); 


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
            
            $create_postulant_details->nacionality  = $request->nacionality;
            $create_postulant_details->id_credential  = $request->id_credential;
            $create_postulant_details->gender  = $request->gender;
            $create_postulant_details->month_salary_net  = $request->month_salary_net;
            $create_postulant_details->month_salary_gross  = $request->month_salary_gross;
            $create_postulant_details->daily_salary  = $request->daily_salary;
            $create_postulant_details->daily_salary_letter  = $request->daily_salary_letter;
            $create_postulant_details->position_objetive  = $request->position_objetive;
            $create_postulant_details->contract_duration  = $request->contract_duration;

            $create_postulant_details->save();
            
            $find_postulant_details = PostulantDetails::all()->where('postulant_id', $request->postulant_id)->last();

           
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary1;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage1;
                $create_postulant_beneficiary->position = 'beneficiary1';
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
           
       
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary2;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage2;
                $create_postulant_beneficiary->position = 'beneficiary2';
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
         
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary3;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage3;
                $create_postulant_beneficiary->position = 'beneficiary3';
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
           

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

                'nacionality' => $request->nacionality,
                'id_credential' => $request->id_credential,
                'gender' => $request->gender,
                'month_salary_net' => $request->month_salary_net,
                'month_salary_gross' => $request->month_salary_gross,
                'daily_salary' => $request->daily_salary,
                'daily_salary_letter' => $request->daily_salary_letter,
                'position_objetive' => $request->position_objetive,
                'contract_duration' => $request->contract_duration,
            ]); 

            $postulant_details = PostulantDetails::all()->where('postulant_id', $request->postulant_id)->last();

            DB::table('postulant_beneficiary')->where('postulant_details_id', intval($postulant_details->id))->where('position','beneficiary1') ->update([
                'name' => $request->beneficiary1,
                'porcentage' => $request->porcentage1,
            ]);

            DB::table('postulant_beneficiary')->where('postulant_details_id', intval($postulant_details->id))->where('position','beneficiary2') ->update([
                'name' => $request->beneficiary2,
                'porcentage' => $request->porcentage2,
            ]);

            DB::table('postulant_beneficiary')->where('postulant_details_id', intval($postulant_details->id))->where('position','beneficiary3') ->update([
                'name' => $request->beneficiary3,
                'porcentage' => $request->porcentage3,
            ]);

            
        }

        if($request->status == 'empleado'){

            $pass = Str::random(8);

            $user = new User();
            $user->name = $request->name;
            $user->image = null;
            $user->lastname = $request->lastname;
            $user->email = $request->mail;
            $user->password = Hash::make($pass);
            $user->save();

            $user->employee->birthday_date = $request->birthdate;
            $user->employee->date_admission = $request->date_admission;
            $user->employee->status = 1;
            $user->employee->jefe_directo_id = null;
            $user->employee->position_id = null;
            $user->employee->save();

            $find_user= User::all()->where('name', $request->name)->where('lastname',$request->lastname)->last();
                
            $role = new RoleUser();
            $role->role_id = 5;
            $role->user_id = $find_user->id;
            $role->user_type = 'App\Models\User';
            $role->save();

            $user_details = new UserDetails();
                
            $user_details->user_id  = $find_user->id;
            $user_details->place_of_birth  = $request->place_of_birth;
            $user_details->birthdate  = $request->birthdate;
            $user_details->fathers_name  = $request->fathers_name;
            $user_details->mothers_name  = $request->mothers_name;
            $user_details->civil_status  = $request->civil_status;
            $user_details->age	  = $request->age;
            $user_details->address  = $request->address;
            $user_details->street  = $request->street;
            $user_details->colony  = $request->colony;
            $user_details->delegation  = $request->delegation;
            $user_details->postal_code  = $request->postal_code;
            $user_details->cell_phone  = $request->cell_phone;
            $user_details->home_phone  = $request->home_phone;
            $user_details->curp  = $request->curp;
            $user_details->rfc  = $request->rfc;
            $user_details->imss_number  = $request->imss_number;
            $user_details->fiscal_postal_code  = $request->fiscal_postal_code;
            $user_details->position  = $request->position;
            $user_details->area  = $request->area;
            $user_details->horary  = $request->horary;
            $user_details->date_admission  = $request->date_admission;
            $user_details->card_number  = $request->card_number;
            $user_details->bank_name  = $request->bank_name;
            $user_details->infonavit_credit  = $request->infonavit_credit;
            $user_details->factor_credit_number  = $request->factor_credit_number;
            $user_details->fonacot_credit  = $request->fonacot_credit;
            $user_details->discount_credit_number  = $request->discount_credit_number;
            $user_details->home_references  = $request->home_references;
            $user_details->house_characteristics  = $request->house_characteristics;
                
            $user_details->nacionality  = $request->nacionality;
            $user_details->id_credential  = $request->id_credential;
            $user_details->gender  = $request->gender;
            $user_details->month_salary_net  = $request->month_salary_net;
            $user_details->month_salary_gross  = $request->month_salary_gross;
            $user_details->daily_salary  = $request->daily_salary;
            $user_details->daily_salary_letter  = $request->daily_salary_letter;
            $user_details->position_objetive  = $request->position_objetive;
            $user_details->contract_duration  = $request->contract_duration;

            $user_details->save();
                
            $find_user_details = UserDetails::all()->where('curp', $request->curp)->last();

            $user_beneficiary = new UserBeneficiary();
            $user_beneficiary->name = $request->beneficiary1;
            $user_beneficiary->phone = null;
            $user_beneficiary->porcentage = $request->porcentage1;
            $user_beneficiary->position = 'beneficiary1';
            $user_beneficiary->users_details_id = $find_user_details->id;
            $user_beneficiary->save();
            
            $user_beneficiary = new  UserBeneficiary();
            $user_beneficiary->name = $request->beneficiary2;
            $user_beneficiary->phone = null;
            $user_beneficiary->porcentage = $request->porcentage2;
            $user_beneficiary->position = 'beneficiary2';
            $user_beneficiary->users_details_id = $find_user_details->id;
            $user_beneficiary->save();
            
            $user_beneficiary = new  UserBeneficiary();
            $user_beneficiary->name = $request->beneficiary3;
            $user_beneficiary->phone = null;
            $user_beneficiary->porcentage = $request->porcentage3;
            $user_beneficiary->position = 'beneficiary3';
            $user_beneficiary->users_details_id = $find_user_details->id;
            $user_beneficiary->save();
           
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

        /* if($request->document == null){
            return redirect()->back()->with('error', 'No has seleccionado ningun documento a generar');          
        } */
        if($request->has('up_personal')){ 
            $up_document = new UpDocument();
            $up_document->upDocument($postulant, $postulant_details, $postulant_beneficiaries);
        }

        if($request->has('determined_contract')){
            $determined_contract = new DeterminateContract();
            $determined_contract->determinateContract($postulant, $postulant_details);
        }

        if($request->has('indetermined_contract')){
            $indeterminate_contract = new IndeterminateContract();
            $indeterminate_contract->indeterminateContract($postulant, $postulant_details,$request->company, $request->determined_contract_duration );
        }

        if($request->has('confidentiality_agreement')){
            $confidentiality_agreement = new confidentialityAgreement();
            $confidentiality_agreement->confidentialityAgreement($postulant, $postulant_details);
        }

        if($request->has('work_condition_update')){
            $work_condition_update = new WorkConditionUpdate();
            $work_condition_update->workConditionUpdate($postulant, $postulant_details);
        }

        if($request->has('no_compete_agreement')){

            //Promo zale
            if(intval($postulant->company_id) == 3){
                return redirect()->back()->with('error', 'Archivo no disponible para la empresa Promo Zale');          
            }   
            //Unipromtex
            if(intval($postulant->company_id)== 5){
                return redirect()->back()->with('error', 'Archivo no disponible para la empresa Unipromtex');          
            }
            $no_compete_agreement = new NoCompeteAgreement();
            $no_compete_agreement->noCompeteAgreement($postulant, $postulant_details);
        }   

        if($request->has('letter_for_bank')){
            $letter_for_bank = new LetterForBank();
            $letter_for_bank->letterForBank($postulant,$postulant_details,intval($request->company));
        }   
    }

    public function downUsers()
    {
        $users = User::all()->where('status',2);
        return view('rh.down-users', compact('users'));  
    }

    public function upUsers(Request $request)
    {
        DB::table('users')->where('id', intval($request->user_id) )->update(['status' => 1]); 
        return redirect()->back()->with('message', 'Usuario dado de alta satisfactoriamente');
    }

    public function convertToEmployee(Request $request)
    {
     
        $postulant = Postulant::all()->where('id',$request->postulant_id)->last();

        $postulant_details = PostulantDetails::all()->where('postulant_id',$postulant->id)->last();
        
        $postulant_beneficiaries = PostulantBeneficiary::all()->where('postulant_details_id',$postulant->id);

        $pass = Str::random(8);

        $user = new User();
        $user->name = $postulant->name;
        $user->image = null;
        $user->lastname = $postulant->lastname;
        $user->email = $postulant->mail;
        $user->password = Hash::make($pass);
        $user->save();

        $user->employee->birthday_date = $postulant_details->birthdate;
        $user->employee->date_admission = $postulant_details->date_admission;
        $user->employee->status = 1;
        $user->employee->jefe_directo_id = null;
        $user->employee->position_id = null;
        $user->employee->save();

        $find_user= User::all()->where('name', $postulant->name)->where('lastname',$postulant->lastname)->last();
                
        $role = new RoleUser();
        $role->role_id = 5;
        $role->user_id = $find_user->id;
        $role->user_type = 'App\Models\User';
        $role->save();

        $user_details = new UserDetails();
                
        $user_details->user_id  = $find_user->id;
        $user_details->place_of_birth  = $postulant_details->place_of_birth;
        $user_details->birthdate  = $postulant_details->birthdate;
        $user_details->fathers_name  = $postulant_details->fathers_name;
        $user_details->mothers_name  = $postulant_details->mothers_name;
        $user_details->civil_status  = $postulant_details->civil_status;
        $user_details->age	  = $postulant_details->age;
        $user_details->address  = $postulant_details->address;
        $user_details->street  = $postulant_details->street;
        $user_details->colony  = $postulant_details->colony;
        $user_details->delegation  = $postulant_details->delegation;
        $user_details->postal_code  = $postulant_details->postal_code;
        $user_details->cell_phone  = $postulant_details->cell_phone;
        $user_details->home_phone  = $postulant_details->home_phone;
        $user_details->curp  = $postulant_details->curp;
        $user_details->rfc  = $postulant_details->rfc;
        $user_details->imss_number  = $postulant_details->imss_number;
        $user_details->fiscal_postal_code  = $postulant_details->fiscal_postal_code;
        $user_details->position  = $postulant_details->position;
        $user_details->area  = $postulant_details->area;
        $user_details->horary  = $postulant_details->horary;
        $user_details->date_admission  = $postulant_details->date_admission;
        $user_details->card_number  = $postulant_details->card_number;
        $user_details->bank_name  = $postulant_details->bank_name;
        $user_details->infonavit_credit  = $postulant_details->infonavit_credit;
        $user_details->factor_credit_number  = $postulant_details->factor_credit_number;
        $user_details->fonacot_credit  = $postulant_details->fonacot_credit;
        $user_details->discount_credit_number  = $postulant_details->discount_credit_number;
        $user_details->home_references  = $postulant_details->home_references;
        $user_details->house_characteristics  = $postulant_details->house_characteristics;
                
        $user_details->nacionality  = $postulant_details->nacionality;
        $user_details->id_credential  = $postulant_details->id_credential;
        $user_details->gender  = $postulant_details->gender;
        $user_details->month_salary_net  = $postulant_details->month_salary_net;
        $user_details->month_salary_gross  = $postulant_details->month_salary_gross;
        $user_details->daily_salary  = $postulant_details->daily_salary;
        $user_details->daily_salary_letter  = $postulant_details->daily_salary_letter;
        $user_details->position_objetive  = $postulant_details->position_objetive;
        $user_details->contract_duration  = $postulant_details->contract_duration;

        $user_details->save();
               
        $find_user_details = UserDetails::all()->where('user_id', $find_user->id)->last();

        $postulant_beneficiaries = PostulantBeneficiary::all()->where('postulant_details_id', $postulant_details    ->id);

        foreach($postulant_beneficiaries as $beneficiary ){
            if($beneficiary->position == 'beneficiary1' ){
                $user_beneficiary = new UserBeneficiary();
                $user_beneficiary->name = $beneficiary->beneficiary1;
                $user_beneficiary->phone = null;
                $user_beneficiary->porcentage = $beneficiary->porcentage1;
                $user_beneficiary->position = 'beneficiary1';
                $user_beneficiary->users_details_id = $find_user_details->id;
                $user_beneficiary->save();
            }

            if($beneficiary->position == 'beneficiary2' ){
                $user_beneficiary = new  UserBeneficiary();
                $user_beneficiary->name = $beneficiary->beneficiary2;
                $user_beneficiary->phone = null;
                $user_beneficiary->porcentage = $beneficiary->porcentage2;
                $user_beneficiary->position = 'beneficiary2';
                $user_beneficiary->users_details_id = $find_user_details->id;
                $user_beneficiary->save();
            }

            if($beneficiary->position == 'beneficiary3' ){
                $user_beneficiary = new  UserBeneficiary();
                $user_beneficiary->name = $beneficiary->beneficiary3;
                $user_beneficiary->phone = null;
                $user_beneficiary->porcentage = $beneficiary->porcentage3;
                $user_beneficiary->position = 'beneficiary3';
                $user_beneficiary->users_details_id = $find_user_details->id;
                $user_beneficiary->save();
            }
        }

        DB::table('postulant')->where('id', $request->postulant_id)->update(['status' => 'empleado']); 

        return redirect()->back()->with('message', 'Candidato ha sido promovido a empleado satisfactoriamente');

    }

    public function createUserDocument(Request $request)
    {
        $user = User::where('id',$request->user_id)->get()->last();
        $employee = Employee::where('user_id',$user->id)->get()->last();
        $user_details = UserDetails::where('user_id',$request->user_id)->get()->last();
        $company = EmployeeCompany::where('employee_id', $employee->id)->get()->last();
      
        $indeterminate_contract = new IndeterminateContractUser();
        $indeterminate_contract->indeterminateContractUser($user, $user_details, $company->company_id);
        
    }

}

