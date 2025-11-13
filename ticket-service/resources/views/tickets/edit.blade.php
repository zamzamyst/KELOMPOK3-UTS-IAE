@extends('layouts.app')
@section('title','Edit Ticket')
@section('content')
<h1>Edit Ticket</h1>
@if($errors->any())
  <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<form method="POST" action="{{ route('tickets.update',$ticket) }}">
  @csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Passenger Name</label>
    <input type="text" name="passenger_name" value="{{ old('passenger_name',$ticket->passenger_name) }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Passenger Contact</label>
    <input type="text" name="passenger_contact" value="{{ old('passenger_contact',$ticket->passenger_contact) }}" class="form-control" required>
  </div>
  <button class="btn btn-primary">Save</button>
</form>
@endsection
