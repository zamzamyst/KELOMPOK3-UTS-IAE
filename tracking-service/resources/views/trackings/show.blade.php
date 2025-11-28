@extends('layouts.app')

@section('title','Trackings & Details')

@section('content')

{{-- =============================== --}}
{{-- LIST TRACKINGS (index.blade) --}}
{{-- =============================== --}}
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
            <th>Action</th>
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
            <td>
                <a href="{{ route('trackings.show', $t->id) }}" class="btn btn-sm btn-info">
                    View
                </a>

                <form action="{{ route('trackings.destroy', $t->id) }}" 
                      method="POST" 
                      style="display:inline-block;" 
                      onsubmit="return confirm('Yakin ingin menghapus tracking ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="7">No tracking records yet</td></tr>
    @endforelse
    </tbody>
</table>

{{ $trackings->links() }}

<hr class="my-5">

{{-- =============================== --}}
{{-- DETAIL TRACKING (show.blade) --}}
{{-- =============================== --}}

<h2 class="mb-3">Tracking Details</h2>

<div id="tracking-detail" style="display:none;">
    <h3>Tracking #<span id="t-id"></span></h3>

    {{-- ========================== --}}
    {{-- FIXED — FULL TRACKING INFO --}}
    {{-- ========================== --}}
    <div class="card mb-3">
        <div class="card-header"><h5>Tracking Info</h5></div>
        <div class="card-body">

            <p><strong>ID:</strong> <span id="t-tracking-id"></span></p>
            <p><strong>Bus ID:</strong> <span id="t-bus-id-raw"></span></p>
            <p><strong>Schedule ID:</strong> <span id="t-schedule-id-raw"></span></p>
            <p><strong>Latitude:</strong> <span id="t-lat"></span></p>
            <p><strong>Longitude:</strong> <span id="t-lng"></span></p>
            <p><strong>Created At:</strong> <span id="t-created"></span></p>

        </div>
    </div>

    {{-- BUS INFO --}}
    <div class="card mb-3">
        <div class="card-header"><h5>Bus Info</h5></div>
        <div class="card-body">
            <p><strong>ID:</strong> <span id="t-bus-id"></span></p>
            <p><strong>Name:</strong> <span id="t-bus-name"></span></p>
            <p><strong>Plate:</strong> <span id="t-bus-plate"></span></p>
            <p><strong>Capacity:</strong> <span id="t-bus-capacity"></span></p>
        </div>
    </div>

    {{-- ROUTE --}}
    <div class="card mb-3">
        <div class="card-header"><h5>Route Info</h5></div>
        <div class="card-body">
            <p><strong>ID:</strong> <span id="t-route-id"></span></p>
            <p><strong>Code:</strong> <span id="t-route-code"></span></p>
            <p><strong>Origin:</strong> <span id="t-route-origin"></span></p>
            <p><strong>Destination:</strong> <span id="t-route-destination"></span></p>
            <p><strong>Stops:</strong></p>
            <ul id="t-route-stops"></ul>
        </div>
    </div>

    {{-- SCHEDULE --}}
    <div class="card mb-3">
        <div class="card-header"><h5>Schedule Info</h5></div>
        <div class="card-body">
            <p><strong>ID:</strong> <span id="t-schedule-id"></span></p>
            <p><strong>Departure:</strong> <span id="t-schedule-departure"></span></p>
            <p><strong>Arrival:</strong> <span id="t-schedule-arrival"></span></p>
            <p><strong>Available Seats:</strong> <span id="t-schedule-seats"></span></p>
            <p><strong>Price:</strong> <span id="t-schedule-price"></span></p>
        </div>
    </div>
</div>

<div id="tracking-detail" style="display:none;">
<div id="loading" style="display:none;"><p>Loading...</p></div>
<div id="error" class="alert alert-danger" style="display:none;"></div>



{{-- =============================== --}}
{{-- JAVASCRIPT DETAIL FETCHER --}}
{{-- =============================== --}}
<script>
@if(isset($id))
  const trackingId = {{ $id }};
@else
  const trackingId = null;
@endif

if (trackingId) {

  const currentOrigin = window.location.origin;

  let apiUrl;
  if (currentOrigin.includes('4000')) {
    apiUrl = 'http://127.0.0.1:4000/api/tracking-service/trackings/' + trackingId;
  } else if (currentOrigin.includes('8004')) {
    apiUrl = 'http://127.0.0.1:8004/api/trackings/' + trackingId;
  } else {
    apiUrl = 'http://127.0.0.1:4000/api/tracking-service/trackings/' + trackingId;
  }

  document.getElementById('loading').style.display = 'block';

  fetch(apiUrl)
    .then(response => {
      document.getElementById('loading').style.display = 'none';
      if (!response.ok) throw new Error('Failed to fetch tracking data');
      return response.json();
    })
    .then(result => {
      document.getElementById('tracking-detail').style.display = 'block';

      let t = result.data || result;
      if (Array.isArray(t)) t = t[0] || {};

      // ============================
      // FIX — Semua Kolom Index
      // ============================
      document.getElementById('t-id').textContent = t.id ?? '-';
      document.getElementById('t-tracking-id').textContent = t.id ?? '-';

      document.getElementById('t-bus-id-raw').textContent = t.bus_id ?? '-';
      document.getElementById('t-schedule-id-raw').textContent = t.schedule_id ?? '-';

      document.getElementById('t-lat').textContent = t.lat ?? '-';
      document.getElementById('t-lng').textContent = t.lng ?? '-';
      document.getElementById('t-created').textContent = t.created_at ?? '-';

      // BUS
      if (t.bus) {
        document.getElementById('t-bus-id').textContent = t.bus.id ?? '-';
        document.getElementById('t-bus-name').textContent = t.bus.name ?? '-';
        document.getElementById('t-bus-plate').textContent = t.bus.plate_number ?? '-';
        document.getElementById('t-bus-capacity').textContent = t.bus.capacity ?? '-';
      }

      // ROUTE
      if (t.route) {
        document.getElementById('t-route-id').textContent = t.route.id ?? '-';
        document.getElementById('t-route-code').textContent = t.route.code ?? '-';
        document.getElementById('t-route-origin').textContent = t.route.origin ?? '-';
        document.getElementById('t-route-destination').textContent = t.route.destination ?? '-';

        const stopsList = document.getElementById('t-route-stops');
        stopsList.innerHTML = '';

        if (t.route.stops) {
          const stops = Array.isArray(t.route.stops)
            ? t.route.stops
            : JSON.parse(t.route.stops);

          stops.forEach(s => {
            const li = document.createElement('li');
            li.textContent = s;
            stopsList.appendChild(li);
          });
        }
      }

      // SCHEDULE
      if (t.schedule) {
        document.getElementById('t-schedule-id').textContent = t.schedule.id ?? '-';
        document.getElementById('t-schedule-departure').textContent = t.schedule.departure_at ?? '-';
        document.getElementById('t-schedule-arrival').textContent = t.schedule.arrival_at ?? '-';
        document.getElementById('t-schedule-seats').textContent = t.schedule.available_seats ?? '-';
        document.getElementById('t-schedule-price').textContent = t.schedule.price ? 'Rp ' + t.schedule.price : '-';
      }

    })
    .catch(err => {
      document.getElementById('error').style.display = 'block';
      document.getElementById('error').textContent = err.message;
    });

    function submitDelete() {
      if (confirm('Are you sure you want to delete this tracking?')) {
          document.getElementById('deleteTrackingForm').submit();
      }
  }
}
</script>

@endsection
