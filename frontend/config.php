<?php
// Frontend dashboard target: default to API gateway so all links go through gateway
return [
    'gateway' => getenv('API_GATEWAY_URL') ?: 'http://127.0.0.1:4000',
    // if you prefer direct service links, uncomment and set these
    'bus_direct' => getenv('BUS_SERVICE_URL') ?: 'http://127.0.0.1:8001',
    'ticket_direct' => getenv('TICKET_SERVICE_URL') ?: 'http://127.0.0.1:8003',
    'payment_direct' => getenv('PAYMENT_SERVICE_URL') ?: 'http://127.0.0.1:8002',
    'tracking_direct' => getenv('TRACKING_SERVICE_URL') ?: 'http://127.0.0.1:8004',
];
