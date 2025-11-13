@extends('layouts.app')
@section('title','Bus Detail')
@section('content')
<h1>Bus #{{ $bus->id }}</h1>
<dl class="row">
  <dt class="col-sm-3">Plate</dt><dd class="col-sm-9">{{ $bus->plate_number }}</dd>
  <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $bus->name }}</dd>
  <dt class="col-sm-3">Capacity</dt><dd class="col-sm-9">{{ $bus->capacity }}</dd>
  <dt class="col-sm-3">Type</dt><dd class="col-sm-9">{{ $bus->type }}</dd>
  <dt class="col-sm-3">Route ID</dt><dd class="col-sm-9">{{ $bus->route_id }}</dd>
</dl>
<a href="{{ route('buses.index') }}" class="btn btn-secondary">Back</a>
@endsection
