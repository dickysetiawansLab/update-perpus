<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function index(){
		return view('Users/signup');
	}
	public function signup(Request $request)
	{
		$user = new User();
		$role = 'anggota';
		$validateData = $request -> validate([
			'username'=> ['required','min:8','max:300','unique:users'],
			'email' =>'required|email',
			'password'=>'required|min:8|max:300',
			
		]);
		$validateData['password'] = Hash::make($validateData['password']);

		$user->username = $validateData['username'];
		$user->email = $validateData['email'];
		$user->password = $validateData['password'];
		$user->role = $role;
		$user->save();

		// User::create($validateData);
		return redirect('/login');
	}
	
}
