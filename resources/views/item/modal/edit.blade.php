@foreach ($data as $dataEdit)
<div class="modal fade" id="editItem{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addeditItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('item.update', $dataEdit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editItemLabel{{ $dataEdit->id }}">Edit Data
                        Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="item" class="form-label">Item <span class="text-danger">*</span></label>
                        <input type="text" name="item" id="item" class="form-control" value="{{ $dataEdit->item }}" placeholder="Masukkan nama item" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-select">
                            @php
                            $kategori = \App\Models\Kategori::select('id', 'kategori')->get();
                            @endphp
                            @foreach ($kategori as $kategoris)
                            <option value="{{ $kategoris->id }}" {{ $dataEdit->kategori_id == $kategoris->id ? 'selected' : '' }}>{{ $kategoris->kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
