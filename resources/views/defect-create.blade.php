@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-bug-fill"></i> Tambah Data Defect</h5>
        </div>
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <form action="{{ route('defect.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Pilih Data Produksi</label>
                    <select name="data_produksi_id" class="form-control" required>
                        <option value="">-- Pilih Produksi --</option>
                        @foreach($produksiList as $produksi)
                            <option value="{{ $produksi->id }}">
                                {{ $produksi->Tanggal_Produksi }} - {{ $produksi->User }} (Line {{ $produksi->Line_Produksi }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Produksi</label>
                    <input type="date" name="Tanggal_Produksi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="Nama_Barang" class="form-control" placeholder="Masukkan nama barang" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Defect</label>
                    <input type="text" name="Jenis_Defect" class="form-control" placeholder="Contoh: Cacat Jahitan, Kotor, Robek" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah Cacat Per Jenis</label>
                    <input type="number" name="Jumlah_Cacat_perjenis" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Severity</label>
                    <select name="Severity" class="form-control" required>
                        <option value="">-- Pilih Tingkat Keparahan --</option>
                        <option value="Minor">Minor</option>
                        <option value="Major">Major</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-save"></i> Simpan Data
                </button>
                <a href="/dashboard" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>

            </form>
        </div>
    </div>
</div>
@endsection
