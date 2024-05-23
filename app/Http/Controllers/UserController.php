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
                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    $action =  '<a href="javascript:void(0)" onClick="editUser(' . $id . ')" class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="hapusUser(' . $id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
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
                    'name' => $request->input('name'),
                    'user_id' => $userId,
                ]);
            }
            return $user;
        } else {
            // $user = User::findOrFail($user->id);
            // $user->delete();
        }
        return response()->json($user)
            ->with('success', 'Email dan password pengguna berhasil ditambah.');
    }

    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
        {
            $request->validate([
                'email' => 'required|email|unique:users,email,' . $request->input('id'),
                'name' => 'required',
            ]);

            // Simpan atau perbarui data pengguna
            $user = User::updateOrCreate(
                ['id' => $request->id],
                [
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]
            );

            if ($user) {
                // Simpan atau perbarui data terkait sesuai dengan peran pengguna
                $userId = $user->id;
                if ($user->role == 'super_admin') {
                    SuperAdmin::updateOrCreate(
                        ['user_id' => $userId],
                        ['name' => $request->name],
                    );
                } else if ($user->role == 'admin') {
                    Admin::updateOrCreate(
                        ['user_id' => $userId],
                        ['name' => $request->name]
                    );
                } else if ($user->role == 'direksi') {
                    Direksi::updateOrCreate(
                        ['user_id' => $userId],
                        ['name' => $request->name]
                    );
                } else if ($user->role == 'unit') {
                    Unit::updateOrCreate(
                        ['user_id' => $userId],
                        ['name' => $request->input('name')]
                    );
                }

                return response()->json(['success' => 'Data pengguna berhasil disimpan.']);
            } else {
                return response()->json(['error' => 'Gagal menyimpan data pengguna.']);
            }
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
    public function edit(Request $request)
    {
        $id = array('id' => $request->id);
        $user  = User::where($id)->first();
        // $user = User::find($id);

        return Response()->json($user);
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
    public function destroy(Request $request)
    {
        $id = $request->id;
        // $user = User::where('id', $request->id)->delete();
        $user = User::findOrFail($id);

        // Hapus data terkait berdasarkan peran (role) pengguna
        // if ($user) {
        //     if ($user->role == 'super_admin') {
        //         SuperAdmin::where('user_id', $id)->delete();
        //     } elseif ($user->role == 'admin') {
        //         Admin::where('user_id', $id)->delete();
        //     } elseif ($user->role == 'direksi') {
        //         Direksi::where('user_id', $id)->delete();
        //     } elseif ($user->role == 'unit') {
        //         Unit::where('user_id', $id)->delete();
        //     }
        // Hapus pengguna itu sendiri
        $user->delete();
        //         return response()->json(['message' => 'User and associated data deleted successfully']);
        //     } else {
        //         return response()->json(['message' => 'User not found'], 404);
        //     }
        // }
        return Response()->json($user);
    }
}
