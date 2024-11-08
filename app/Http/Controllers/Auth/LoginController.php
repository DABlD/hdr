<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Helper;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            Helper::log(auth()->user()->id, 'logged in.');

            if(auth()->user()->role == "Company"){
                return redirect()->route('company.dashboard');
            }
            elseif(in_array(auth()->user()->role, ["Nurse", "Receptionist"])){
                return redirect()->route('patient.patient');
            }
            elseif(in_array(auth()->user()->role, ["Doctor"])){
                return redirect()->route('exam.examape');
            }
            else{
                return redirect()->route('dashboard');
            }
    
        }
    
        return back()->withErrors([
            'auth' => 'Invalid Username/Password.',
        ]);
    }
}