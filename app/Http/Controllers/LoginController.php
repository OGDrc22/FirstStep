<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\student_tb;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('login');
    }

    public function login_data(Request $request) {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required'],
        ]);

        $student = student_tb::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Auth ::login($student);
        return redirect(route('start-exam'));
    }

    // public function start() {
    //     return view('start_exam');
    // }
}
