<?php
// 1. Tentukan URL Gateway
$gatewayUrl = getenv('API_GATEWAY_URL') ?: 'http://127.0.0.1:4000';

// 2. Definisikan KONSTANTA agar bisa dipanggil di file lain (login.php, dll)
if (!defined('API_URL')) {
    define('API_URL', $gatewayUrl);
}

// 3. Return array (opsional, jika masih dibutuhkan oleh bagian kode lain)
return [
    'gateway' => API_URL,
    // Jika ingin akses langsung ke service (biasanya tidak disarankan jika sudah ada gateway)
    'bus_direct' => getenv('BUS_SERVICE_URL') ?: 'http://127.0.0.1:8001',
    'ticket_direct' => getenv('TICKET_SERVICE_URL') ?: 'http://127.0.0.1:8003',
    'payment_direct' => getenv('PAYMENT_SERVICE_URL') ?: 'http://127.0.0.1:8002',
    'tracking_direct' => getenv('TRACKING_SERVICE_URL') ?: 'http://127.0.0.1:8004',
];