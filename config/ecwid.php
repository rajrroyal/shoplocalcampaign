<?php
return [
    'client_id' => env('ECWID_CLIENT_ID', ''),
    'client_secret' => env('ECWID_CLIENT_SECRET', ''),
    'scope' => env('ECWID_SCOPE', 'read_store_profile,read_catalog'),
    'confirm_uri' => env('ECWID_CONFIRM_URI', 'http://shop20.local/account/connect/powershop/confirm')
];

// Approved scopes: 'read_store_profile,update_orders,read_orders,create_catalog,update_catalog,read_catalog'
