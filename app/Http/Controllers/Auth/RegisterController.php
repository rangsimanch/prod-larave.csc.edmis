<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Team;
use App\ConstructionContract;
use App\Organization;
use App\Jobtitle;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
   
     protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'workphone' => $data['workphone'],
            'team_id' => $data['team_id'],
            'jobtitle_id' => $data['jobtitle_id'],
            'organization_id' => $data['organization_id'],
        ]);
        
        $user->construction_contracts()->sync($data['construction_contracts']);

        
        return $user;
        
    }


    public function showRegistrationForm()
    {
        $organizations = Organization::all()->pluck('title_th', 'id')->prepend(trans('global.pleaseSelect'), '');

        $teams = Team::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jobtitles = Jobtitle::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $construction_contracts = ConstructionContract::where('id', '!=', 15)->pluck('code', 'id');

        return view('auth.register', compact('organizations','teams', 'jobtitles', 'construction_contracts'));
    }

}
