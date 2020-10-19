<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Devices;
use App\UserCompany;
use Spatie\QueryBuilder\AllowedFilter;


class LaporanCSMController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    //Devices
    public function index()
    {
        //$user_companies = UserCompany::where('user_id', $user_id)->first();
        $devices = DB::table('pawoon2.devices')
        ->select(DB::raw('distinct pawoon2.devices.name as device_name, pawoon2.companies.name as company_name, pawoon2.users.email as email_owner, pawoon2.transactions.device_timestamp as last_transaction, pawoon2.roles_2.name as tier'))
        ->join('pawoon2.companies','pawoon2.devices.company_id','=','pawoon2.companies.id')
        ->join('pawoon1.user_companies','pawoon1.user_companies.company_id','=','pawoon2.companies.id')
        ->join('pawoon2.outlets', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.transactions', 'pawoon2.transactions.outlet_id', '=', 'pawoon2.outlets.id')
        ->join('pawoon2.user_has_companies', 'pawoon2.companies.id', '=', 'pawoon2.user_has_companies.company_id')
        ->join('pawoon2.users','pawoon2.users.id','=','pawoon2.user_has_companies.user_id')
        ->join('pawoon2.model_has_roles', 'pawoon2.model_has_roles.model_id', '=', 'pawoon2.users.id')
        ->join('pawoon2.roles', 'pawoon2.model_has_roles.role_id', '=', 'pawoon2.roles.id')
        ->join('pawoon2.model_has_roles as model_has_roles_2', 'pawoon2.model_has_roles_2.model_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.roles as roles_2', function($join){
            $join->on('pawoon2.model_has_roles_2.role_id', '=', 'pawoon2.roles_2.id')
                ->where('pawoon2.model_has_roles_2.model_type', 'App\Company');
        })
        ->where('pawoon2.roles.id', '1')
        //->where('pawoon1.user_companies.user_id', $user_id)
        ->groupBy('pawoon2.devices.id')
        ->limit(500)

        // dd($devices->toSql());
         ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Show devices',
            'data' => [
                'users' => $devices,
            ],
        ],200)
        ->header('Access-Control-Allow-Origin', '*');
    }

    //Company
    public function show()
    {
        $users = DB::table('pawoon2.users')
        ->select(DB::raw('distinct pawoon2.roles_2.name as tier, pawoon2.companies.name as company_name, pawoon2.users.email as email_owner, pawoon2.transactions.final_amount as transaction_amount, pawoon2.transactions.device_timestamp as Tanggal'))
        ->where('pawoon2.roles.id', '1')
        ->join('pawoon2.user_has_companies', 'pawoon2.users.id', '=', 'pawoon2.user_has_companies.user_id')
        ->join('pawoon2.companies', 'pawoon2.companies.id', '=', 'pawoon2.user_has_companies.company_id')
        ->join('pawoon1.user_companies','pawoon1.user_companies.company_id','=','pawoon2.companies.id')
        ->join('pawoon2.model_has_roles', 'pawoon2.model_has_roles.model_id', '=', 'pawoon2.user_has_companies.id')
        ->join('pawoon2.roles', 'pawoon2.roles.id', '=', 'pawoon2.model_has_roles.role_id')
        ->join('pawoon2.outlets', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.transactions', 'pawoon2.transactions.outlet_id', '=', 'pawoon2.outlets.id')
        ->join('pawoon2.model_has_roles as model_has_roles_2', 'pawoon2.model_has_roles_2.model_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.roles as roles_2', function($join){
            $join->on('pawoon2.model_has_roles_2.role_id', '=', 'pawoon2.roles_2.id')
                ->where('pawoon2.model_has_roles_2.model_type', 'App\Company');
        })
        ->groupBy('pawoon2.companies.id')
        ->limit(1000)
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Show User',
            'data' => [
                'user' => $users,
            ],
        ],200)
        ->header('Access-Control-Allow-Origin', '*');
        //}
    }

    //Outlets
    public function showoutlets()
    {
        $outlets = DB::table('pawoon2.outlets')
        ->select(DB::raw('distinct pawoon2.outlets.id, pawoon2.outlets.uuid, pawoon2.outlets.name as outlet_name, pawoon2.companies.name as company_name, pawoon2.users.email as email_owner, pawoon2.roles_2.name as tier'))
        ->where('pawoon2.roles.id', '1')
        ->join('pawoon2.companies', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.transactions', 'pawoon2.outlets.id', '=', 'pawoon2.transactions.outlet_id')
        ->join('pawoon2.user_has_companies', 'pawoon2.companies.id', '=', 'pawoon2.user_has_companies.company_id')
        ->join('pawoon2.users','pawoon2.users.id','=','pawoon2.user_has_companies.user_id')
        ->join('pawoon1.user_companies','pawoon1.user_companies.company_id','=','pawoon2.companies.id')
        ->join('pawoon2.model_has_roles', 'pawoon2.model_has_roles.model_id', '=', 'pawoon2.user_has_companies.id')
        ->join('pawoon2.roles', 'pawoon2.roles.id', '=', 'pawoon2.model_has_roles.role_id')
        ->join('pawoon2.model_has_roles as model_has_roles_2', 'pawoon2.model_has_roles_2.model_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.roles as roles_2', function($join){
            $join->on('pawoon2.model_has_roles_2.role_id', '=', 'pawoon2.roles_2.id')
                ->where('pawoon2.model_has_roles_2.model_type', 'App\Company');
        })
        ->get();


        return response()->json([
            'success' => true,
            'message' => 'Berhasil Show outlets',
            'data' => [
                'user' => $outlets,
            ],
        ],200)
        ->header('Access-Control-Allow-Origin', '*');
        //}
    }

    //OutletsReport
    public function reportoutlet($id)
    {
        $transactions = DB::table('pawoon2.transactions')
        ->select(DB::raw('distinct pawoon2.outlets.name as outlet_name, pawoon2.companies.name as company_name, pawoon2.devices.name as device_name,FORMAT(pawoon2.transactions.final_amount,2, "id_ID") as amount, pawoon2.transactions.device_timestamp as Tanggal')) /*DB::raw("SUM(final_amount) as grand_total"))
        ->whereDate('device_timestamp', '2020-01-13')*/
        ->where('pawoon2.outlets.id', $id)
        ->join('pawoon2.outlets', 'pawoon2.transactions.outlet_id', '=', 'pawoon2.outlets.id')
        ->join('pawoon2.companies', 'pawoon2.outlets.company_id', '=', 'pawoon2.companies.id')
        ->join('pawoon2.devices', 'pawoon2.transactions.device_id', '=', 'pawoon2.devices.id')
        ->get();


        return response()->json([
            'success' => true,
            'message' => 'Berhasil Show outlets',
            'data' => [
                'user' => $transactions,
            ],
        ],200)
        ->header('Access-Control-Allow-Origin', '*');
        //}
    }

    //SumAmount
    public function sumamount($id)
    {
        $transactions = DB::table('pawoon2.transactions')
        ->select(DB::raw("FORMAT(SUM(final_amount),2 , 'id_ID')as total"))
        ->where('pawoon2.outlets.id',$id)
        ->join('pawoon2.outlets','pawoon2.transactions.outlet_id', '=', 'pawoon2.outlets.id')
        ->get();


        return response()->json([
            'success' => true,
            'message' => 'Berhasil Sum Amount',
            'data' => [
                'user' => $transactions,
            ],
        ],200)
        ->header('Access-Control-Allow-Origin', '*');
        //}
    }
}