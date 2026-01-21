<div class="modal fade" id="addRkap" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('rkap.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addItemLabel">Tambah Data Penetapan RKAP</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Nama item -->
                    <div class="mb-3">
                        <label for="item" class="form-label">Item <span class="text-danger">*</span></label>
                        <select name="item_id" id="item_id" class="form-select select2" required>
                            <option value="" disabled selected>== Pilih Salah Satu ==</option>
                            @php
                            $items = \App\Models\Item::showData();
                            @endphp
                            @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ Str::upper($item->item) }} - {{ Str::upper($item->kategori->kategori) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Jumlah -->
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" placeholder="Masukkan Jumlah Item" required value="{{ old('jumlah') }}">
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
