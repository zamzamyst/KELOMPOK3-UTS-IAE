@extends('layouts.app')

@section('title','Tracking Details')

@section('content')
<div class="mb-3">
    <a href="{{ env('API_GATEWAY_URL','http://127.0.0.1:4000').'/tracking-service/trackings' }}" class="btn btn-secondary">Back to Trackings</a>
</div>

<div id="tracking-detail" style="display:none;">
    <h1>Tracking #<span id="t-id"></span></h1>
    
    <div class="card mb-3">
        <div class="card-header">
            <h5>Tracking Info</h5>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> <span id="t-tracking-id"></span></p>
            <p><strong>Latitude:</strong> <span id="t-lat"></span></p>
            <p><strong>Longitude:</strong> <span id="t-lng"></span></p>
            <p><strong>Created:</strong> <span id="t-created"></span></p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5>Bus Info</h5>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> <span id="t-bus-id"></span></p>
            <p><strong>Name:</strong> <span id="t-bus-name"></span></p>
            <p><strong>Plate:</strong> <span id="t-bus-plate"></span></p>
            <p><strong>Capacity:</strong> <span id="t-bus-capacity"></span></p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5>Route Info</h5>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> <span id="t-route-id"></span></p>
            <p><strong>Code:</strong> <span id="t-route-code"></span></p>
            <p><strong>Origin:</strong> <span id="t-route-origin"></span></p>
            <p><strong>Destination:</strong> <span id="t-route-destination"></span></p>
            <p><strong>Stops:</strong></p>
            <ul id="t-route-stops"></ul>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5>Schedule Info</h5>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> <span id="t-schedule-id"></span></p>
            <p><strong>Departure:</strong> <span id="t-schedule-departure"></span></p>
            <p><strong>Arrival:</strong> <span id="t-schedule-arrival"></span></p>
            <p><strong>Available Seats:</strong> <span id="t-schedule-seats"></span></p>
            <p><strong>Price:</strong> <span id="t-schedule-price"></span></p>
        </div>
    </div>

    <div>
        <a href="{{ env('API_GATEWAY_URL','http://127.0.0.1:4000').'/tracking-service/trackings' }}" class="btn btn-secondary">Back</a>
        <button class="btn btn-danger" onclick="deleteTracking()">Delete</button>
    </div>
</div>

<div id="loading" style="display:none;">
    <p>Loading...</p>
</div>

<div id="error" class="alert alert-danger" style="display:none;"></div>

<script>
  const trackingId = {{ $id }};
  const currentOrigin = window.location.origin;
  
  let apiUrl;
  if (currentOrigin.includes('4000')) {
    // Via gateway: use /api/ endpoint which gets proxied to service /api/
    apiUrl = 'http://127.0.0.1:4000/api/tracking-service/trackings/' + trackingId;
  } else if (currentOrigin.includes('8004')) {
    // Direct service access: use local /api/ endpoint
    apiUrl = 'http://127.0.0.1:8004/api/trackings/' + trackingId;
  } else {
    // Fallback to gateway
    apiUrl = 'http://127.0.0.1:4000/api/tracking-service/trackings/' + trackingId;
  }

  document.getElementById('loading').style.display = 'block';

  console.log('Fetching from API URL:', apiUrl);

  fetch(apiUrl)
    .then(response => {
      document.getElementById('loading').style.display = 'none';
      console.log('Response status:', response.status, response.statusText);
      if (!response.ok) {
        throw new Error('Failed to fetch tracking data: ' + response.statusText);
      }
      return response.text().then(text => {
        if (!text) throw new Error('Empty response');
        try {
          return JSON.parse(text);
        } catch (e) {
          console.error('JSON parse error. Response text preview:', text.substring(0, 200));
          throw new Error('Invalid JSON response: ' + e.message);
        }
      });
    })
    .then(data => {
      document.getElementById('tracking-detail').style.display = 'block';
      
      let t = data.data || data;
      
      // Handle if data is wrapped in array
      if (Array.isArray(t)) {
        t = t[0] || {};
      }
      
      console.log('Tracking data:', t);
      
      document.getElementById('t-id').textContent = t.id || '-';
      document.getElementById('t-tracking-id').textContent = t.id || '-';
      document.getElementById('t-lat').textContent = t.lat || '-';
      document.getElementById('t-lng').textContent = t.lng || '-';
      document.getElementById('t-created').textContent = t.created_at || '-';
      
      if (t.bus && typeof t.bus === 'object') {
        document.getElementById('t-bus-id').textContent = t.bus.id || '-';
        document.getElementById('t-bus-name').textContent = t.bus.name || '-';
        document.getElementById('t-bus-plate').textContent = t.bus.plate_number || '-';
        document.getElementById('t-bus-capacity').textContent = t.bus.capacity || '-';
      }
      
      if (t.route && typeof t.route === 'object') {
        document.getElementById('t-route-id').textContent = t.route.id || '-';
        document.getElementById('t-route-code').textContent = t.route.code || '-';
        document.getElementById('t-route-origin').textContent = t.route.origin || '-';
        document.getElementById('t-route-destination').textContent = t.route.destination || '-';
        
        const stopsList = document.getElementById('t-route-stops');
        stopsList.innerHTML = '';
        if (t.route.stops) {
          const stops = Array.isArray(t.route.stops) ? t.route.stops : (typeof t.route.stops === 'string' ? JSON.parse(t.route.stops) : []);
          stops.forEach(stop => {
            const li = document.createElement('li');
            li.textContent = stop;
            stopsList.appendChild(li);
          });
        }
      }
      
      if (t.schedule && typeof t.schedule === 'object') {
        document.getElementById('t-schedule-id').textContent = t.schedule.id || '-';
        document.getElementById('t-schedule-departure').textContent = t.schedule.departure_at || '-';
        document.getElementById('t-schedule-arrival').textContent = t.schedule.arrival_at || '-';
        document.getElementById('t-schedule-seats').textContent = t.schedule.available_seats || '-';
        document.getElementById('t-schedule-price').textContent = (t.schedule.price ? 'Rp ' + t.schedule.price : '-');
      }
    })
    .catch(error => {
      document.getElementById('loading').style.display = 'none';
      document.getElementById('error').style.display = 'block';
      document.getElementById('error').textContent = 'Error: ' + error.message;
      console.error('Error fetching tracking:', error);
      console.log('API URL was:', apiUrl);
    });

  function deleteTracking() {
    if (!confirm('Are you sure you want to delete this tracking?')) {
      return;
    }
    
    const deleteUrl = apiUrl;
    
    fetch(deleteUrl, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
      }
    })
    .then(response => {
      console.log('Delete response status:', response.status, response.statusText);
      if (response.status === 200 || response.ok) {
        alert('Tracking deleted successfully!');
        window.location.href = currentOrigin.includes('4000') 
          ? 'http://127.0.0.1:4000/tracking-service/trackings' 
          : 'http://127.0.0.1:8004/trackings';
      } else {
        alert('Error deleting tracking: HTTP ' + response.status);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error: ' + error.message);
    });
  }
</script>

@endsection
