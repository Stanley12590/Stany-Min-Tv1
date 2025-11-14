<?php
// Enable all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Supabase Configuration
define('SUPABASE_URL', 'https://ythiqgnxurgrfuifzsfz.supabase.co');
define('SUPABASE_KEY', 'sb_secret_sXhMqHgPGw572UtVh4ap8A_PuQGKn2X');

// Supabase API Functions with DEBUGGING
function supabaseFetch($table, $filters = []) {
    $url = SUPABASE_URL . "/rest/v1/$table?" . http_build_query($filters);
    
    error_log("🔍 SUPABASE FETCH: $url");
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Content-Type: application/json'
        ]
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        error_log("❌ CURL ERROR: " . $error);
        return [];
    }
    
    if ($http_code === 200) {
        $data = json_decode($response, true);
        error_log("✅ FETCH SUCCESS: " . count($data) . " records from $table");
        return is_array($data) ? $data : [];
    }
    
    error_log("❌ SUPABASE ERROR: HTTP $http_code for $table - Response: $response");
    return [];
}

function supabaseInsert($table, $data) {
    $url = SUPABASE_URL . "/rest/v1/$table";
    
    error_log("🔍 SUPABASE INSERT: $url - Data: " . json_encode($data));
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ]
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        error_log("❌ CURL ERROR: " . $error);
        return false;
    }
    
    if ($http_code === 201) {
        error_log("✅ INSERT SUCCESS: Data added to $table");
        return json_decode($response, true);
    }
    
    error_log("❌ SUPABASE INSERT ERROR: HTTP $http_code - Response: $response");
    return false;
}

function supabaseUpdate($table, $id, $data) {
    $url = SUPABASE_URL . "/rest/v1/$table?id=eq.$id";
    
    error_log("🔍 SUPABASE UPDATE: $url - Data: " . json_encode($data));
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ]
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        error_log("❌ CURL ERROR: " . $error);
        return false;
    }
    
    if ($http_code === 200) {
        error_log("✅ UPDATE SUCCESS: Data updated in $table");
        return json_decode($response, true);
    }
    
    error_log("❌ SUPABASE UPDATE ERROR: HTTP $http_code - Response: $response");
    return false;
}

function supabaseDelete($table, $id) {
    $url = SUPABASE_URL . "/rest/v1/$table?id=eq.$id";
    
    error_log("🔍 SUPABASE DELETE: $url");
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY
        ]
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        error_log("❌ CURL ERROR: " . $error);
        return false;
    }
    
    if ($http_code === 200 || $http_code === 204) {
        error_log("✅ DELETE SUCCESS: Data deleted from $table");
        return true;
    }
    
    error_log("❌ SUPABASE DELETE ERROR: HTTP $http_code - Response: $response");
    return false;
}

// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debug session
error_log("🔍 SESSION STATUS: " . session_status());
error_log("🔍 SESSION DATA: " . json_encode($_SESSION));
?>        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Content-Type: ' . SUPABASE_KEY,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ]
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 200) {
        $result = json_decode($response, true);
        return is_array($result) ? $result : false;
    }
    
    return false;
}

function supabaseDelete($table, $id) {
    $url = SUPABASE_URL . "/rest/v1/$table?id=eq.$id";
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'apikey: ' . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY
        ]
    ]);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $http_code === 200 || $http_code === 204;
}

// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Authentication check function
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Redirect to login if not authenticated
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
