@extends('layouts.app')

@section('title','Create Tracking')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Create Tracking</h5>

    <p class="text-muted">
      Create or update tracking for a bus. Only <code>bus_id</code> is required;
      coordinates will be generated automatically by the API.
    </p>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @php
      $apiGateway = env('API_GATEWAY_URL') ? rtrim(env('API_GATEWAY_URL'), '/') : 'http://127.0.0.1:4000';
      $formAction = $apiGateway.'/api/tracking-service/trackings';
      $gateway = rtrim(env('API_GATEWAY_URL', 'http://127.0.0.1:4000'), '/');
    @endphp

    <form id="trackingForm" onsubmit="return false;">
      @csrf
      <div class="mb-3">
        <label for="bus_id" class="form-label">Bus ID</label>
        <input type="number" class="form-control" id="bus_id" name="bus_id" required>
      </div>

      <div class="mb-3">
        <label for="schedule_id" class="form-label">Schedule ID (optional)</label>
        <input type="number" class="form-control" id="schedule_id" name="schedule_id">
      </div>

      <button type="submit" class="btn btn-primary">Create / Update Tracking</button>
      <a href="{{ $gateway }}/tracking-service/trackings" class="btn btn-secondary">Cancel</a>
    </form>

    <script>
      document.getElementById('trackingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const bus_id = document.getElementById('bus_id').value;
        const schedule_id = document.getElementById('schedule_id').value;
        const csrfToken = document.querySelector('input[name="_token"]')?.value;
        
        const formData = new URLSearchParams();
        formData.append('bus_id', bus_id);
        if (schedule_id) {
          formData.append('schedule_id', schedule_id);
        }
        if (csrfToken) {
          formData.append('_token', csrfToken);
        }
        
        fetch('{{ $formAction }}', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
          },
          body: formData
        })
        .then(response => {
          if (response.ok) {
            alert('Tracking created successfully!');
            window.location.href = '{{ $gateway }}/tracking-service/trackings';
          } else {
            return response.json().then(data => {
              alert('Error: ' + (data.message || response.statusText));
            }).catch(() => {
              alert('Error: ' + response.statusText);
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error: ' + error.message);
        });
      });
    </script>
  </div>
</div>
@endsection
