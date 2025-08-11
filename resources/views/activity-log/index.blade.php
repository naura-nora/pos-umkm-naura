@extends('layouts.adminlte')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Aktivitas Terakhir</h3>
            <div class="card-tools">
                <form action="{{ route('aktivitas.index') }}" method="GET">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" name="search" class="form-control" placeholder="Cari aktivitas..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Event</th>
                        <th>Deskripsi</th>
                        <th>Detail</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>{{ ($activities->currentPage() - 1) * $activities->perPage() + $loop->iteration }}</td>
                        <td>
                            @if($activity->causer)
                                {{ $activity->causer->name }}
                                <br>
                                <small class="text-muted">{{ $activity->causer->email }}</small>
                            @else
                                System
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                                @if($activity->event == 'created') badge-success
                                @elseif($activity->event == 'updated') badge-warning
                                @elseif($activity->event == 'deleted') badge-danger
                                @elseif($activity->event == 'login') badge-info
                                @elseif($activity->event == 'logout') badge-secondary
                                @else badge-primary @endif">
                                {{ ucfirst($activity->event) }}
                            </span>
                        </td>
                        <td>{{ $activity->description }}</td>
                        <td>
                            @if($activity->properties && count($activity->properties) > 0)
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#detailModal{{ $activity->id }}">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="detailModal{{ $activity->id }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel">Detail Aktivitas</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <pre>{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $activity->created_at->format('d M Y H:i') }}
                            <br>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada aktivitas yang tercatat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $activities->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection