<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Services\BugReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BugReportController extends Controller
{
    protected $bug;

    public function __construct(BugReportService $bug)
    {
        $this->bug = $bug;
    }

    public function index()
    {
        if (Auth::user()->role != 2) {
            return view('bug-report.index', ['data' => BugReport::showData(Auth::user()->id)]);
        }
        return view('bug-report.index', ['data' => BugReport::showData()]);
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
        return $this->bug->tambah($request);
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
        return $this->bug->hapus($id);
    }

    public function accept($id)
    {
        return $this->bug->accept($id);
    }

    public function reject($id)
    {
        return $this->bug->reject($id);
    }
}
