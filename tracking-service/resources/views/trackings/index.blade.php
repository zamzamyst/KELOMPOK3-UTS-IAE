@extends('layouts.app')

@section('title','Trackings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Trackings</h1>
    <a class="btn btn-primary" href="{{ route('trackings.create') }}">Create Tracking</a>
</div>

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Bus</th>
            <th>Schedule</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
    @forelse($trackings as $t)
        <tr>
            <td>{{ $t->id }}</td>
            <td>{{ $t->bus_id }}</td>
            <td>{{ $t->schedule_id }}</td>
            <td>{{ $t->lat }}</td>
            <td>{{ $t->lng }}</td>
            <td>{{ $t->created_at }}</td>
        </tr>
    @empty
        <tr><td colspan="6">No tracking records yet</td></tr>
    @endforelse
    </tbody>
</table>

{{ $trackings->links() }}
@endsection
