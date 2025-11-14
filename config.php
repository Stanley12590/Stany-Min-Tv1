<?php
// Render-specific configuration
if (getenv('RENDER')) {
    // On Render, use /tmp for sessions
    ini_set('session.save_path', '/tmp');
    ini_set('session.gc_probability', 1);
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Supabase Configuration
define('SUPABASE_URL', 'https://ythiqgnxurgrfuifzsfz.supabase.co');
define('SUPABASE_KEY', 'sb_secret_sXhMqHgPGw572UtVh4ap8A_PuQGKn2X');

function supabaseFetch($table, $filters = []) {
    $url = SUPABASE_URL . "/rest/v1/$table?" . http_build_query($filters);
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Content-Type: application/json'
        ]
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $http_code === 200 ? json_decode($response, true) : [];
}

// ... rest of your supabase functions

// Session handling for Render
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
