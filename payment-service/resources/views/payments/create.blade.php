@extends('layouts.app')

@section('title','Create Payment')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Create Payment</h5>

    <p class="text-muted">Create a payment for a ticket.</p>

    @php
      $apiGateway = env('API_GATEWAY_URL') ? rtrim(env('API_GATEWAY_URL'), '/') : 'http://127.0.0.1:4000';
      $formAction = $apiGateway.'/api/payment-service/payments';
      $gateway = rtrim(env('API_GATEWAY_URL', 'http://127.0.0.1:4000'), '/');
    @endphp

    <form id="paymentForm" onsubmit="return false;">
      @csrf
      <div class="mb-3">
        <label for="ticket_id" class="form-label">Ticket ID</label>
        <input type="number" class="form-control" id="ticket_id" name="ticket_id" required>
      </div>

      <div class="mb-3">
        <label for="amount" class="form-label">Amount</label>
        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status" required>
          <option value="pending">Pending</option>
          <option value="completed">Completed</option>
          <option value="failed">Failed</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Create Payment</button>
      <a href="{{ $gateway.'/payment-service/payments' }}" class="btn btn-secondary">Cancel</a>
    </form>

    <script>
      // Dynamically determine the API endpoint based on current origin
      // If accessed via gateway (4000), POST to gateway; if accessed directly (8002), POST to service
      const currentOrigin = window.location.origin;
      let formAction;
      
      if (currentOrigin.includes('4000')) {
        // Accessed via gateway: POST to gateway API endpoint
        formAction = '{{ $formAction }}';
      } else if (currentOrigin.includes('8002')) {
        // Accessed directly on payment service port: POST to local service API
        formAction = 'http://127.0.0.1:8002/api/payments';
      } else {
        // Fallback
        formAction = '{{ $formAction }}';
      }
      
      document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const ticket_id = document.getElementById('ticket_id').value;
        const amount = document.getElementById('amount').value;
        const status = document.getElementById('status').value;
        const csrfToken = document.querySelector('input[name="_token"]')?.value;
        
        const formData = new URLSearchParams();
        formData.append('ticket_id', ticket_id);
        formData.append('amount', amount);
        formData.append('status', status);
        if (csrfToken) {
          formData.append('_token', csrfToken);
        }
        
        console.log('Submitting to:', formAction);
        
        fetch(formAction, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
          },
          body: formData
        })
        .then(response => {
          if (response.ok) {
            alert('Payment created successfully!');
            window.location.href = '{{ $gateway }}/payment-service/payments';
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
