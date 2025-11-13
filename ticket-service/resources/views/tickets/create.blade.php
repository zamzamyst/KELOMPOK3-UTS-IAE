@extends('layouts.app')
@section('title','Create Ticket')
@section('content')
<h1>Create Ticket</h1>
@if($errors->any())
  <div class="alert alert-danger"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<form method="POST" action="{{ route('tickets.store') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Passenger Name</label>
    <input type="text" name="passenger_name" value="{{ old('passenger_name') }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Passenger Contact</label>
    <input type="text" name="passenger_contact" value="{{ old('passenger_contact') }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Schedule</label>
    <select id="schedule_id" name="schedule_id" class="form-select" required>
      <option value="">-- select schedule --</option>
      @foreach($schedules as $s)
        <option 
          value="{{ $s->id }}"
          data-price="{{ $s->price ?? 0 }}"
          data-available="{{ $s->available_seats ?? 0 }}"
        >
          #{{ $s->id }} — {{ $s->departure_at }} → {{ $s->arrival_at }} (Seats: {{ $s->available_seats }})
        </option>
      @endforeach
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Seat Count</label>
    <input id="seat_count" type="number" name="seat_count" value="1" min="1" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Total Price</label>
    <input id="total_price_display" type="text" class="form-control" value="-" disabled>
  </div>
  
  <button class="btn btn-primary">Create Ticket</button>
</form>
<script>
  (function(){
    const scheduleSelect = document.getElementById('schedule_id');
    const seatInput = document.getElementById('seat_count');
    const totalDisplay = document.getElementById('total_price_display');

    const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' });

    function recalc(){
      const opt = scheduleSelect.options[scheduleSelect.selectedIndex];
      const price = parseFloat(opt?.getAttribute('data-price') || '0');
      const available = parseInt(opt?.getAttribute('data-available') || '0', 10);
      const seats = parseInt(seatInput.value || '0', 10);

      if (!isNaN(available) && available > 0) {
        seatInput.max = String(available);
      } else {
        seatInput.removeAttribute('max');
      }

      const total = (isFinite(price) && isFinite(seats)) ? price * seats : 0;
      totalDisplay.value = (price > 0 && seats > 0) ? currency.format(total) : '-';
    }

    scheduleSelect?.addEventListener('change', recalc);
    seatInput?.addEventListener('input', recalc);
    document.addEventListener('DOMContentLoaded', recalc);
    recalc();
  })();
</script>
@endsection
