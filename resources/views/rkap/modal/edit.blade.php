@foreach ($data as $dataEdit)
<div class="modal fade" id="editRkap{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addbranch-officeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('rkap.update', $dataEdit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="kategoriLabel{{ $dataEdit->id }}">Edit Data Penetapan RKAP</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Item <span class="text-danger">*</span></label>
                        <select name="item_id" id="item_id" class="form-select" required>
                            <option value="" disabled selected>== Pilih Salah Satu ==</option>
                            @php
                            $items = \App\Models\Item::showData();
                            @endphp
                            @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ $dataEdit->item_id == $item->id ? 'selected' : '' }}>
                                {{ Str::upper($item->item) }} - {{ Str::upper($item->kategori->kategori) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan Jumlah Item" required value="{{ old('jumlah', $dataEdit->jumlah) }}">
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
