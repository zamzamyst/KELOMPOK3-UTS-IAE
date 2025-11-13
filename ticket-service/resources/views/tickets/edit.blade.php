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
  <div class="mb-3">
    <label class="form-label">Seat Count</label>
    <input 
      id="seat_count" 
      type="number" 
      name="seat_count" 
      value="{{ old('seat_count',$ticket->seat_count) }}" 
      min="1" 
      class="form-control" 
      required
      data-price="{{ $price ?? 0 }}"
      data-available="{{ $available ?? '' }}"
    >
  </div>
  <div class="mb-3">
    <label class="form-label">Total Price</label>
    <input id="total_price_display" type="text" class="form-control" value="-" disabled>
  </div>
  <button class="btn btn-primary">Save</button>
</form>
<script>
  (function(){
    const seatInput = document.getElementById('seat_count');
    const totalDisplay = document.getElementById('total_price_display');
    const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' });
    const price = parseFloat(seatInput.dataset.price || '0');
    const availableRaw = seatInput.dataset.available || '';
    const available = availableRaw === '' ? null : parseInt(availableRaw, 10);

    if (available !== null && !isNaN(available)) {
      seatInput.max = String(available);
    }

    function recalc(){
      const seats = parseInt(seatInput.value || '0', 10);
      const total = (isFinite(price) && isFinite(seats)) ? price * seats : 0;
      totalDisplay.value = (price > 0 && seats > 0) ? currency.format(total) : '-';
    }

    seatInput?.addEventListener('input', recalc);
    document.addEventListener('DOMContentLoaded', recalc);
    recalc();
  })();
</script>
@endsection
