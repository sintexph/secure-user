<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrganizationalUnit;
use App\UserOrganizationalUnit;
use App\User;
use App\SyncUser;

class ApiSecureController extends Controller
{
    public function get_users($ou_name)
    {
        
        $ou=OrganizationalUnit::where('name',$ou_name)->first();
        
        if(empty($ou))
            return response()->json([]);
        else
        {
            $users_id=SyncUser::select(['user_id'])->where('ou_id',$ou->id)->get();
            $users=UserOrganizationalUnit::where('ou_id',$ou->id)
            ->whereIn('user_id',$users_id)
            ->with('user')->get();

            return json_encode($users);
        }
    }
}
