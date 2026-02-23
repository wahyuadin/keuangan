@foreach ($data as $dataEdit)
<div class="modal fade" id="editSla{{ $dataEdit->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addeditSlaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('sla.update', $dataEdit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editSlaLabel{{ $dataEdit->id }}">Edit Data
                        Sla</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- kategori -->
                    <div class="mb-3">
                        <label for="Kategori" class="form-label">Item</label>
                        <select name="item_id" id="item_id" class="form-select">
                            <option value="">Pilih Item</option>
                            @php
                            $item = \App\Models\Item::showData();
                            @endphp
                            @foreach ($item as $itemData)
                            <option value="{{ $itemData->id }}" @selected($dataEdit->item_id == $itemData->id)>{{ Str::upper($itemData->item) }} [{{ $itemData->kategori->kategori ?? '-' }}]</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Nama item -->
                    <div class="mb-3">
                        <label for="clinic_id" class="form-label">Klinik</label>
                        <select name="clinic_id" id="clinic_id" class="form-select">
                            <option value="">Pilih Klinik</option>
                            @php
                            $clinic = \App\Models\Clinic::select('id', 'nama_klinik')->get();
                            @endphp
                            @foreach ($clinic as $clinicItem)
                            <option value="{{ $clinicItem->id }}" @selected($dataEdit->clinic_id == $clinicItem->id)>{{ $clinicItem->nama_klinik }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="rkap" class="form-label">Penetapan RKAP <span class="text-danger">*</span></label>
                        <input type="number" name="rkap" id="rkap" class="form-control" placeholder="Masukkan Penetapan RKAP" required value="{{ $dataEdit->rkap ?? old('rkap') }}">
                    </div>
                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select">
                            <option value="">Pilih Tahun</option>
                            @php
                            $currentYear = date('Y');
                            @endphp
                            @for ($year = $currentYear; $year >= 2000; $year--)
                            <option value="{{ $year }}" @selected($dataEdit->tahun == $year)>{{ $year }}</option>
                            @endfor
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
