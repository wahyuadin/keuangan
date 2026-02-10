<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use App\Services\SlaService;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    protected $sla;

    public function __construct(SlaService $sla)
    {
        $this->sla = $sla;
    }

    public function index()
    {
        return view('sla.index', ['data' => Sla::showData()]);
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
        return $this->sla->tambah($request);
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
        return $this->sla->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->sla->hapus($id);
    }
}
