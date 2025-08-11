@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Daftar Kategori</h3>
            <div class="card-tools">
                <a href="{{ route('kategori.create') }}" class="btn btn-light">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>
            </div>
        </div>

        <!-- serching -->
        <div class="card-tools mt-3 container-fluid">
                <form method="GET" action="{{ route('kategori.index') }}" id="searchForm">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari disini" 
                               value="{{ request('search') }}"
                               id="searchInput">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
        </div>
        <!-- end serching -->

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategori as $item)
                    <tr>
                        <!-- <td>{{ $item->id }}</td> -->
                        <td>{{ ($kategori->currentPage() - 1) * $kategori->perPage() + $loop->iteration }}</td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td>
                            <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                @if ($kategori->lastPage() > 1)
                    {{ $kategori->links('pagination::bootstrap-4') }}
                @else
                    <ul class="pagination">
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    </ul>
                @endif
            </div>



        </div>
    </div>
</div>
@endsection