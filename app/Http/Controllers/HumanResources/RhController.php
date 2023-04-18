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
use App\Models\PostulantDocumentation;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserBeneficiary;
use App\Models\UserDetails;
use App\Models\UserDocumentation;
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
        
       
        $motive =  $this->motiveDown($start, $end);
        
        return view('rh.stadistics', compact('totalEmpleados', 'nuevosingresos', 'bajas', 'start', 'end', 'motive'));
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
                $employee = Employee::where('id', $company->employee_id)->get()->last();
              
                $admission = explode('-', $employee->date_admission);
                $year = $admission[0];
                $mont = $admission[1];

                if($employee->user != null && $employee->user->status == 1){
                    if($mont == $monthpresent && $year == $yearpresent ){
                            $totalPLFilter = $totalPLFilter + 1;
                         
                    }
                }
            }

            foreach($bh_trade_market as $company){
                $employee = Employee::where('id', $company->employee_id)->get()->last();
              
                $admission = explode('-', $employee->date_admission);
                $year = $admission[0];
                $mont = $admission[1];

                if($employee->user != null && $employee->user->status == 1){
                    if($mont == $monthpresent && $year == $yearpresent ){
                        $totalBHFilter = $totalBHFilter + 1;
                    }
                }
            }


            foreach($promo_zale as $company){
                $employee = Employee::where('id', $company->employee_id)->get()->last();
              
                $admission = explode('-', $employee->date_admission);
                $year = $admission[0];
                $mont = $admission[1];

                if($employee->user != null && $employee->user->status == 1){
                    if($mont == $monthpresent && $year == $yearpresent ){
                        $totalPLFilter = $totalPLFilter + 1; 
                    }
                }
            }


            foreach($trade_market57 as $company){
                $employee = Employee::where('id', $company->employee_id)->get()->last();
              
                $admission = explode('-', $employee->date_admission);
                $year = $admission[0];
                $mont = $admission[1];

                if($employee->user != null && $employee->user->status == 1){
                    if($mont == $monthpresent && $year == $yearpresent ){
                        $totalTM57Filter = $totalTM57Filter + 1;  
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

        }else{

            $format_start =date('Y-m-d', strtotime($start));
            $format_end =date('Y-m-d', strtotime($end));

            foreach($promolife as $company){
                $employee = Employee::where('id', $company->employee_id)->get()->last();
                if($employee->user != null && $employee->user->status == 1){
                    if($employee->date_admission >= $format_start && $employee->date_admission <= $format_end ){
                        $totalPLFilter = $totalPLFilter + 1;
                    }
                }
            }

            foreach($bh_trade_market as $company){
                $employee = Employee::where('id', $company->employee_id)->get()->last();
                if($employee->user != null && $employee->user->status == 1){
                    if($employee->date_admission >= $format_start && $employee->date_admission <= $format_end ){
                        $totalBHFilter = $totalBHFilter + 1;
                    }   
                }
            }

            foreach($trade_market57 as $company){
                $employee = Employee::where('id', $company->employee_id)->get()->last();
                if($employee->user != null && $employee->user->status == 1){
                    if($employee->date_admission >= $format_start && $employee->date_admission <= $format_end ){
                        $totalTM57Filter = $totalTM57Filter + 1;
                    }
                }
            }

            foreach($promo_zale as $company){
                $employee = Employee::where('id', $company->employee_id)->get()->last();
                if($employee->user != null && $employee->user->status == 1){
                    if($employee->date_admission >= $format_start && $employee->date_admission <= $format_end ){
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
            return  $data;
        }
    }

    public function bajas($start, $end)
    {
        $data=[];
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $users = User::where('status',2)->get();

        $totalPLFilter = 0;
        $totalBHFilter = 0;
        $totalTM57Filter = 0;
        $totalPZFilter = 0;

        $yearpresent = $date->format('Y');
        $monthpresent = $date->format('m');
        $format_start =date('Y-m-d', strtotime($start));
        $format_end =date('Y-m-d', strtotime($end));
        
      
        foreach($users as $user){
                
            if($user->employee->companies != null){

                switch ($user->employee->companies->last()->name_company) {
                    
                    case "Promo Life":
                        //Valores iniciales
                        if($user->userDetails !=null && $start == null && $end == null){
                            
                            $down = explode('-', $user->userDetails->date_down);
                            $year = $down[0];
                            $mont = $down[1];
                    
                            if($user != null && $user->status == 2 && $mont == $monthpresent && $year == $yearpresent ){
                                $totalPLFilter = $totalPLFilter + 1;
                            }
                        }else{
                            //Fecha filtrada
                            if($user->userDetails->date_down >= $format_start && $user->userDetails->date_down <= $format_end ){
                                $totalPLFilter = $totalPLFilter + 1;
                            }
                                
                        }
                        break;
                    case "BH Trade Market":
                    
                        if($user->userDetails !=null && $start == null && $end == null){
                            
                            $down = explode('-', $user->userDetails->date_down);
                            $year = $down[0];
                            $mont = $down[1];
                
                            if($user != null && $user->status == 2 && $mont == $monthpresent && $year == $yearpresent ){
                                $totalBHFilter = $totalBHFilter + 1;
                            }
                        }else{
                            //Fecha filtrada
                            if($user->userDetails->date_down >= $format_start && $user->userDetails->date_down <= $format_end ){
                                $totalBHFilter = $totalBHFilter + 1;
                            }
                        }
                        
                        break;
                    case "Promo Zale":
                       
                        if($user->userDetails !=null && $start == null && $end == null){

                                $down = explode('-', $user->userDetails->date_down);
                                $year = $down[0];
                                $mont = $down[1];
                
                                if($user != null && $user->status == 2 && $mont == $monthpresent && $year == $yearpresent ){
                                    $totalPZFilter  = $totalPZFilter  + 1;
                                }
                                
                        }else{
                            
                            //Fecha filtrada
                            if($user->userDetails->date_down >= $format_start && $user->userDetails->date_down <= $format_end ){
                                $totalPZFilter  = $totalPZFilter  + 1;
                            }
                        }
                        
                        break;
                    case "Trade Market 57":
                    
                        if($user->userDetails !=null && $start == null && $end == null){

                            $down = explode('-', $user->userDetails->date_down);
                            $year = $down[0];
                            $mont = $down[1];
                   
                            if($user != null && $user->status == 2 && $mont == $monthpresent && $year == $yearpresent ){
                                $totalTM57Filter = $totalTM57Filter + 1;
                            }
                                
                        }else{
                            //Fecha filtrada
                            if($user->userDetails->date_down >= $format_start && $user->userDetails->date_down <= $format_end ){
                                $totalTM57Filter = $totalTM57Filter + 1;
                            }
                        }
                        
                        break;
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

        return  $data;
    }

    public function motiveDown($start, $end)
    {
        $departments = Department::all();
        $users = User::where('status', 2)->get();
        $department_data = [];

        //Fechas
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $yearpresent = $date->format('Y');
        $monthpresent = $date->format('m');
 
       
        foreach($departments as $department){
            
            //Contadores
            $total_users = 0; $type = "";  
            $growth_salary = 0; $growth_promotion = 0; $growth_activity = 0;
            $climate_partnet = 0; $climate_manager = 0; $climate_boss= 0;
            $psicosocial_workloads = 0; $psicosocial_appreciation = 0; $psicosocial_violence = 0; $psicosocial_workday = 0;
            $demographics_distance = 0; $demographics_physical = 0; $demographics_personal = 0; $demographics_school = 0;
            $health_personal = 0; $health_familiar = 0;
            $other_motive = 0;

            foreach($users as $user){
               
                if($user->employee->position->department->name == $department->name){
                    $admission = explode('-', $user->userDetails->date_down);
                    $year = $admission[0];
                    $mont = $admission[1];

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
        
                    if($start == null && $end == null && $year ==$yearpresent && $mont == $monthpresent){
                        $total_users = $total_users + 1;
                        $type = "No filtrada";
                    }elseif($user->userDetails->date_down >= $start && $user->userDetails->date_down <= $end){ 
                        $total_users = $total_users + 1;
                        $type = "Filtrada";
                    }
                }
            }

            if($total_users > 0){
                array_push($department_data, (object)[
                    'department' => $department->name,
                    'total' => $total_users,
                    'type' =>$type,
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

        if(count($department_data) == 0 ){
            array_push($department_data, (object)[
                'department' => 'Sin registros',
                'total' => 100,
                'type' =>'Sin datos',
                'growth_salary' => 0,
                'growth_promotion' => 0,
                'growth_activity' => 0,
                'climate_partnet' => 0,
                'climate_manager' => 0,
                'climate_boss' => 0,
                'psicosocial_workloads' =>0,
                'psicosocial_appreciation' => 0,
                'psicosocial_violence' => 0,
                'psicosocial_workday' => 0,
                'demographics_distance' => 0,
                'demographics_physical' => 0,
                'demographics_personal' => 0,
                'demographics_school' => 0,
                'health_personal' => 0,
                'health_familiar' => 0,
                'other_motive' => 0,
            ]);
        }

        return $department_data;

    }

    public function postulants()
    {  
        $postulants = Postulant::all()->where('status','<>' , 'no seleccionado');
         
       
        return view('rh.postulants', compact('postulants'));  
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

    public function dropPostulant(Request $request)
    {
        DB::table('postulant')->where('id', intval($request->id) )->update(['status' => 'no seleccionado']); 

        return redirect()->action([RhController::class, 'postulants'])->with('message', 'El postulante se borrado satisfactoriamente');
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
            'vacant' => 'required',
            'birthdate' => 'required',
            'nss' => 'required',
            'curp' => 'required',
            'full_address' => 'required',
            'phone' => 'required',
            'message_phone' => 'required',
            'email' => 'required',
        ]);
       
        $create_postulant = new Postulant();
        $create_postulant->name  = $request->name;
        $create_postulant->lastname  = $request->lastname;
        $create_postulant->vacant  = $request->vacant;
        $create_postulant->birthdate  = $request->birthdate;
        $create_postulant->nss  = $request->nss;
        $create_postulant->curp  = $request->curp;
        $create_postulant->full_address  = $request->full_address;
        $create_postulant->phone  = $request->phone;
        $create_postulant->message_phone = $request->message_phone;
        $create_postulant->email = $request->email;
        $create_postulant->status = 'candidato';
        $create_postulant->save();  

        if ($request->hasFile('cv')) {
            $filenameWithExt = $request->file('cv')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cv')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
            $cv = $request->file('cv')->move('storage/postulant/', $fileNameToStore);

            $create_file = new PostulantDocumentation();
            $create_file->type = $extension;
            $create_file->description = 'cv';
            $create_file->resource = $cv;
            $create_file->postulant_id = $create_postulant->id;
            $create_file->save();
        }

        return redirect()->action([RhController::class, 'postulants']);

    }

    public function editPostulant($post)
    {
        $postulant = Postulant::all()->where('id',$post)->last();
        $companies = Company::all()->pluck('name_company', 'id');
        $departments = Department::all()->pluck('name','id');
        

        return view('rh.edit-postulant', compact('postulant', 'companies', 'departments'));
    }
    

    public function updatePostulant(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'vacant' => 'required',
            'birthdate' => 'required',
            'nss' => 'required',
            'curp' => 'required',
            'full_address' => 'required',
            'phone' => 'required',
            'message_phone' => 'required',
            'email' => 'required',
        ]); 


        if ($request->hasFile('cv')) {
            $find_postulant = PostulantDocumentation::where('postulant_id', $request->postulant_id)->get()->last();
            
            $filenameWithExt = $request->file('cv')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cv')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
            $path = $request->file('cv')->move('storage/postulant/', $fileNameToStore);

            if($find_postulant == null){
                $create_postulant_documentation = new PostulantDocumentation();
                $create_postulant_documentation->type = $extension;
                $create_postulant_documentation->description = 'cv';
                $create_postulant_documentation->resource = $path ;
                $create_postulant_documentation->postulant_id = $request->postulant_id;
                $create_postulant_documentation->save();
            }else{
                File::delete($find_postulant->resource);
                DB::table('postulant_documentation')->where('postulant_id', intval($request->postulant_id))->update([
                    'type' => $extension,
                    'description' => 'cv',
                    'resource' => $path
                ]);
            }
        }

        DB::table('postulant')->where('id', intval($request->postulant_id))->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'vacant' => $request->vacant,
            'birthdate' => $request->birthdate,
            'nss' => $request->nss,
            'curp' => $request->curp,
            'full_address' => $request->full_address,
            'phone' => $request->phone,
            'message_phone' => $request->message_phone,
            'email' => $request->email,
        ]); 

        return redirect()->back()->with('message', 'Información actualizada correctamente');
    }

    public function createPostulantDocumentation($postulant_id)
    {
        $postulant = Postulant::all()->where('id',$postulant_id)->last();
        return View('rh.create-postulant-documentation', compact('postulant'));
    }

    public function buildPostulantDocumentation(Request $request)
    {
        
        $postulant = Postulant::all()->where('id',$request->postulant)->last();

        if($request->has('up_personal')){ 
            $up_document = new UpDocument();
            $up_document->upDocument($postulant);
        }

        if($request->has('determined_contract')){
            $determined_contract = new DeterminateContract();
            $determined_contract->determinateContract($postulant);
        }

        if($request->has('indetermined_contract')){
            $indeterminate_contract = new IndeterminateContract();
            $indeterminate_contract->indeterminateContract($postulant );
        }

        if($request->has('confidentiality_agreement')){
            $confidentiality_agreement = new confidentialityAgreement();
            $confidentiality_agreement->confidentialityAgreement($postulant);
        }

        if($request->has('work_condition_update')){
            $work_condition_update = new WorkConditionUpdate();
            $work_condition_update->workConditionUpdate($postulant);
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
            $no_compete_agreement->noCompeteAgreement($postulant);
        }   

        if($request->has('letter_for_bank')){
            $letter_for_bank = new LetterForBank();
            $letter_for_bank->letterForBank($postulant);
        }
        
        
        if($postulant->status == 'recepcion de documentos'){
            DB::table('postulant')->where('id', intval($request->postulant))->update([ 
                'status'=>'kit legal de ingreso'
            ]);
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

    public function dropUpdateDocumentation($id)
    {
        $status = 1;

        $user = User::where('id',$id)->get()->last();
        $user_documents= UserDocumentation::all()->where('user_id', $id);
        $user_details = UserDetails::where('user_id',$id)->get();

        $status = $user->status;
        return view('rh.drop-update-documentation', compact('id','user_documents','user_details','status' ));
    }

    public function dropUserDetails($id)
    {
        $status = 1;

        $user = User::where('id',$id)->get()->last();
        $user_documents= UserDocumentation::all()->where('user_id', $id);
        $user_details = UserDetails::where('user_id',$id)->get();

        $status = $user->status;
        return view('rh.drop-user-details', compact('id','user_documents','user_details','status', 'user'));
    }


    public function createMorePostulant($postulant_id)
    {
        $postulant = Postulant::where('id',$postulant_id)->get()->last();
        $companies = Company::all()->pluck('name_company','id');
        $departments = Department::all()->pluck('name','id');
        return view('rh.create-more-postulant', compact('postulant','companies', 'departments'));
    }

    public function storeMoreInformation(Request $request)
    {        
        $request->validate([
            'civil_status' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'nacionality' => 'required',
            'id_credential' => 'required',
            'fiscal_postal_code' => 'required',
            'rfc' => 'required',
            'place_of_birth' => 'required',
            'street' => 'required',
            'colony' => 'required',
            'delegation' => 'required',
            'postal_code' => 'required',
            'home_phone' => 'required',
            'home_references' => 'required',
            'house_characteristics' => 'required',
            'month_salary_net' => 'required',
            'month_salary_gross' => 'required',
            'daily_salary' => 'required',
            'daily_salary_letter' => 'required',
            'position_objetive' => 'required',
            'contract_duration' => 'required',
            'company_id' => 'required',
            'department_id' => 'required',
        ]); 
     
        DB::table('postulant')->where('id', intval($request->postulant_id))->update([
            'vacant' => $request->vacant,
            'fathers_name' =>  $request->fathers_name,
            'mothers_name' =>  $request->mothers_name,
            'age' =>  $request->age,
            'gender' =>  $request->gender,
            'nacionality' =>  $request->nacionality,
            'id_credential' =>  $request->id_credential,
            'fiscal_postal_code' =>  $request->fiscal_postal_code,
            'rfc' =>  $request->rfc,
            'place_of_birth' =>  $request->place_of_birth,
            'street' =>  $request->street,
            'colony' =>  $request->colony,
            'delegation' =>  $request->delegation,
            'postal_code' =>  $request->postal_code,
            'home_phone' =>  $request->home_phone,
            'home_references' =>  $request->home_references,
            'house_characteristics' =>  $request->house_characteristics,
            'date_admission' =>  $request->date_admission,
            'card_number' =>  $request->card_number,
            'bank_name' =>  $request->bank_name,
            'infonavit_credit' =>  $request->infonavit_credit,
            'factor_credit_number' =>  $request->factor_credit_number,
            'fonacot_credit' =>  $request->fonacot_credit,
            'discount_credit_number' =>  $request->discount_credit_number,
            'month_salary_net' =>  $request->month_salary_net,
            'month_salary_gross' =>  $request->month_salary_gross,
            'daily_salary' =>  $request->daily_salary,
            'daily_salary_letter' =>  $request->daily_salary_letter,
            'position_objetive' =>  $request->position_objetive,
            'contract_duration' =>  $request->contract_duration,
            'civil_status'=>  $request->civil_status,
            'company_id' =>$request->company_id,
            'department_id'=>$request->department_id,
        ]);

        $postulant = Postulant::where('id', intval($request->postulant_id))->get()->last();
        if($postulant->status == "candidato"){
            
            DB::table('postulant')->where('id', intval($request->postulant_id))->update([ 
                'status'=>'recepcion de documentos'
            ]);
        }
        return redirect()->back()->with('message', 'Informacion de candidato actualizada satisfactoriamente, ya puedes generar el Kit Legal de Ingreso.');

    }

    public function createWorkplan($postulant_id)
    {
        $postulant = Postulant::where('id',$postulant_id)->get()->last();
        return view('rh.create-workplan', compact('postulant'));
    }

    public function createSignedKit($postulant_id)
    {
        $postulant = Postulant::where('id',$postulant_id)->get()->last();
        return view('rh.create-signed-kit', compact('postulant'));
    }

    public function createUpPostulant($postulant_id)
    {
        $postulant = Postulant::where('id',$postulant_id)->get()->last();
        return view('rh.create-up-postulant', compact('postulant'));
    }

   
}


