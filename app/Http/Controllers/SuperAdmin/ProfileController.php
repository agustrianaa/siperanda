<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function redirectProfile()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.profile');
            case 'unit':
                return redirect()->route('unit.profile');
            case 'direksi':
                return redirect()->route('direksi.profile');
            case 'super_admin':
                return redirect()->route('superadmin.profile');
            default:
                return redirect('/')->withErrors('Role tidak dikenali');
        }
    }

    public function index(){
        $user = Auth::user();
        $profileSA = $user->super_admin;
        return view('super_admin.profile', compact('profileSA'));
    }
}
