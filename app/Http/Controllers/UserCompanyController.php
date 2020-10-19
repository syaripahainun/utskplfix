<?php

namespace App\Http\Controllers;

use App\UserCompany; //File Model
use App\Users;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Illuminate\Database\Query\Builder;

class UserCompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(Request $request, $uuid)
    {
        $user = Users::where('uuid', $uuid)->first();
        $company = Company::where('uuid', $request->input('company_uuid'))->first();
        $user_companies = new UserCompany();
        $user_companies->user_id = $user->id;
        $user_companies->company_id = $company->id;
        $user_companies->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Tambah User',
            'data' =>[
                'user' => $user_companies,
            ],
        ], 201)
        ->header('Access-Control-Allow-Origin', '*');
    }

       //CREATE
       public function save(Request $request)
       {
           $users = new UserCompany();
           $users->user_id = ($request->input('user_id'));
           $users->company_id = ($request->input('company_id'));
           $users->save();
   
           return response()->json([
               'success' => true,
               'message' => 'Berhasil Tambah User',
               'data' =>[
                   'user' => $users,
               ],
           ], 201)
           ->header('Access-Control-Allow-Origin', '*');
       }

    

    
}
