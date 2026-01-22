<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Services\KlinikService;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    protected $klinik;

    public function __construct(KlinikService $klinik)
    {
        $this->klinik = $klinik;
    }

    public function index()
    {
        return view('clinic.index', [
            'data' => Clinic::showData(),
        ]);
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
        return $this->klinik->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Clinic::showData($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // return Provider::showData($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return $this->klinik->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->klinik->hapus($id);
    }
}
