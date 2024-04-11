<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Direksi;
use App\Models\SuperAdmin;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(
                User::select(
                    'users.*',
                    // 'super_admin.*',
                    // 'admin.*',
                    // 'direksi.*',
                    // 'unit.*',
                    // 'unit.nama_unit as name',
                    // 'direksi.name as direksi_name',
                    // 'admin.name as admin_name',
                    // 'super_admin.name as superadmin_name',
                )
                    // ->leftJoin('super_admin', 'users.id', '=', 'super_admin.user_id')
                    // ->leftJoin('admin', 'users.id', '=', 'admin.user_id')
                    // ->leftJoin('direksi', 'users.id', '=', 'direksi.user_id')
                    // ->leftJoin('unit', 'users.id', '=', 'unit.user_id')
                    ->get()
            )
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $id = $row->id; // Ambil ID dari baris data
                $action =  '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                return $action;
                })
                ->rawColumns(['action'])

                ->make(true);
        }
        return view('super_admin.user');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = User::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
        ]);
        if ($user) {
            $userId = $user->id;
            if ($user->role == 'super_admin') {
                SuperAdmin::create([
                    'name' => $request->input('name'),
                    'user_id' => $userId,
                ]);
            } else if ($user->role == 'admin') {
                Admin::create([
                    'name' => $request->input('name'),
                    'user_id' => $userId,
                ]);
            } else if ($user->role == 'direksi') {
                Direksi::create([
                    'name' => $request->input('name'),
                    'user_id' => $userId,
                ]);
            } else if ($user->role == 'unit') {
                Unit::create([
                    'nama_unit' => $request->input('nama_unit'),
                    'user_id' => $userId,
                ]);
            }
            return $user;
        } else {
            // $user = User::findOrFail($user->id);
            // $user->delete();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
