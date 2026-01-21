<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rememberDevice = Cookie::get('remember_device', false);
        return view('template.login', compact('rememberDevice'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('remember_device')) {
            Cookie::queue(Cookie::forever('remember_device', json_encode($request->all())));
        } else {
            Cookie::queue(Cookie::forget('remember_device'));
        }

        if (Auth::attempt([
            'username' => $request->input('username'),
            'password' => $request->input('password') // bukan 'user_pass' key, tapi 'password'
        ])) {
            if (Auth::user()->is_active != 1) {
                toastify()->error('Akun Anda belum aktif');
                Auth::logout();
                return redirect(route('login.index'));
            }
            toastify()->success('Selamat Datang, ' . auth()->user()->nama);
            return redirect()->route('index');
        } else {
            toastify()->error('Username atau Password yang anda masukkan salah');
            return redirect()->back();
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

    public function logout()
    {
        try {
            Auth::logout();
            toastify()->success('Anda telah berhasil keluar');
            return redirect(route('login.index'));
        } catch (\Exception $e) {
            toastify()->error('Terjadi kesalahan saat logout: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
