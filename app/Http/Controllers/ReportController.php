<?php

namespace App\Http\Controllers;

use App\Models\report;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $report;

    public function __construct(ReportService $report)
    {
        $this->report = $report;
    }

    public function index()
    {
        return view('report.index', ['data' => report::showData()]);
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
        return $this->report->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return report::showData($id);
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
        return $this->report->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->report->hapus($id);
    }
}
