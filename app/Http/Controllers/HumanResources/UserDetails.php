<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetails as ModelsUserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserDetails extends Controller
{
    //
    public function moreInformation ($user_id)
    {       
        $user_details = ModelsUserDetails::all()->where('user_id', $user_id)->last();
        return view('rh.more-information',compact('user_id', 'user_details'));
    }
    
    public function createMoreInformation(Request $request)
    {
        $find_user_details = ModelsUserDetails::all()->where('user_id', $request->user_id)->last();
        if($find_user_details==null){
            
            $create_users_details= new ModelsUserDetails();
            $create_users_details->user_id= $request->user_id;
            $create_users_details->place_of_birth=$request->place_of_birth;
            $create_users_details->birthdate=$request->birthdate;
            $create_users_details->fathers_name=$request->fathers_name;
            $create_users_details->mothers_name=$request->mothers_name;
            $create_users_details->civil_status=$request->civil_status;
            $create_users_details->age=$request->age;
            $create_users_details->address=$request->address;
            $create_users_details->street=$request->street;
            $create_users_details->colony=$request->colony;
            $create_users_details->delegation=$request->delegation;
            $create_users_details->postal_code=$request->postal_code;
            $create_users_details->cell_phone=$request->cell_phone;
            $create_users_details->home_phone=$request->home_phone;
            $create_users_details->curp=$request->curp;
            $create_users_details->rfc=$request->rfc;
            $create_users_details->imss_number=$request->imss_number;
            $create_users_details->fiscal_postal_code=$request->fiscal_postal_code;
            $create_users_details->position=$request->position;
            $create_users_details->area=$request->area;
            $create_users_details->salary_sd=$request->salary_sd;
            $create_users_details->salary_sbc=$request->salary_sbc;
            $create_users_details->horary=$request->horary;
            $create_users_details->date_admission=$request->date_admission;
            $create_users_details->card_number=$request->card_number;
            $create_users_details->bank_name=$request->bank_name;
            $create_users_details->infonavit_credit=$request->infonavit_credit;
            $create_users_details->factor_credit_number	=$request->factor_credit_number;
            $create_users_details->fonacot_credit=$request->fonacot_credit;
            $create_users_details->discount_credit_number=$request->discount_credit_number;
            $create_users_details->home_references=$request->home_references;
            $create_users_details->house_characteristics=$request->house_characteristics;
            $create_users_details->save();


        }else{
            DB::table('users_details')->where('user_id', intval($request->user_id))->update([
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

        }
        

        return redirect()->back()->with('message', 'Información guardada correctamente');
    }
}
