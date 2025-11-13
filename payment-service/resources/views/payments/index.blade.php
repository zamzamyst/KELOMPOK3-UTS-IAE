@extends('layouts.app')

@section('title','Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Payments</h1>
    @php $gateway = rtrim(env('API_GATEWAY_URL','http://127.0.0.1:4000'), '/'); @endphp
    <a class="btn btn-primary" href="{{ $gateway.'/payment-service/payments/create' }}">Create Payment</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Ticket</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
    @forelse($payments as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->ticket_id }}</td>
            <td>{{ $p->amount }}</td>
            <td>{{ $p->status }}</td>
            <td>{{ $p->created_at }}</td>
        </tr>
    @empty
        <tr><td colspan="5">No payments yet</td></tr>
    @endforelse
    </tbody>
</table>

{{ $payments->links() }}

@endsection
