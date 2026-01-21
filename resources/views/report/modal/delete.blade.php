@foreach ($data as $dataDelete)
<div class="modal fade" id="delete-kategori{{ $dataDelete->id }}" tabindex="-1" aria-labelledby="delete{{ $dataDelete->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <i class="bi bi-exclamation-triangle-fill text-danger mb-3" style="font-size: 4rem;"></i>
                <h4 class="fw-bold">Apakah Anda Yakin?</h4>
                <p class="text-muted">
                    Anda akan menghapus data:<br>
                    Kategori : <strong class="text-dark">
                        {{ $dataDelete->kategori }}</strong>.
                    <br>Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('kategori.destroy', $dataDelete->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
