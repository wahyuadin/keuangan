<?php

namespace App\Http\Controllers;

use App\Models\rkap;
use App\Services\RkapService;
use Illuminate\Http\Request;

class RkapController extends Controller
{
    protected $rkap;

    public function __construct(RkapService $rkap)
    {
        $this->rkap = $rkap;
    }

    public function index()
    {
        return view('rkap.index', ['data' => rkap::showData()]);
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
        return $this->rkap->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return rkap::showData($id);
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
        return $this->rkap->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->rkap->hapus($id);
    }
}
