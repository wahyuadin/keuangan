<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branchoffice;
use App\Services\BranchOfficeService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $branchOffice;

    public function __construct(BranchOfficeService $branchOffice)
    {
        $this->branchOffice = $branchOffice;
    }

    public function index()
    {
        return view('branch-office.index', ['data' => Branchoffice::showData()]);
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
        return $this->branchOffice->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Branchoffice::showData($id);
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
        return $this->branchOffice->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->branchOffice->hapus($id);
    }
}
