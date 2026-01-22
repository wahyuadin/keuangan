<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    protected $item;

    public function __construct(ItemService $item)
    {
        $this->item = $item;
    }

    public function index()
    {
        return view('item.index', ['data' => Item::showData()]);
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

        $this->validate($request, [
            'item' => [
                'required',
                Rule::unique('items', 'item')->whereNull('deleted_at'),
            ],
        ], [
            'item.required' => 'Nama Item wajib diisi.',
            'item.unique' => 'Nama Item sudah ada di database.',
        ]);

        return $this->item->tambah($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Item::showData($id);
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
        return $this->item->edit($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->item->hapus($id);
    }
}
