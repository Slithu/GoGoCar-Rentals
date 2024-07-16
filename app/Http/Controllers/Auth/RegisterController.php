<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\ValidLicenseNumber;

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
    protected $redirectTo = '/home';

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
            'name' => ['required', 'alpha', 'max:255'],
            'surname' => ['required', 'alpha', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'numeric', 'digits:9', 'unique:users'],
            'license' => ['required', 'string', new ValidLicenseNumber, 'unique:users,license'],
            'birth' => ['required', 'date'],
            'town' => ['required', 'alpha', 'max:255'],
            'zip_code' => ['required', 'string', 'max:6'],
            'country' => ['required', 'alpha', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'sex' => $data['sex'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'license' => $data['license'],
            'birth' => $data['birth'],
            'town' => $data['town'],
            'zip_code' => $data['zip_code'],
            'country' => $data['country'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
