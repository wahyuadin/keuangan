<div class="modal fade" id="addItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('item.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addItemLabel">Tambah Data Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama item -->
                    <div class="mb-3">
                        <label for="item" class="form-label">Item <span class="text-danger">*</span></label>
                        <input type="text" name="item" id="item" class="form-control" placeholder="Masukkan Nama Item" required value="{{ old('item') }}">
                    </div>

                    <!-- kategori -->
                    <div class="mb-3">
                        <label for="Kategori" class="form-label">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-control select2">
                            <option value="">Pilih Kategori</option>
                            @php
                            $kategori = \App\Models\Kategori::select('id', 'kategori')->get();
                            @endphp
                            @foreach ($kategori as $item)
                            <option value="{{ $item->id }}">{{ $item->kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
