<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

Route::get('/', function () {
    return view('welcome');
});

// Simple Hello World endpoint
Route::get('/hello', function () {
    return 'Hello Laravel!';
});

// Status page that checks database and Redis connectivity
Route::get('/status', function () {
    $status = [
        'status' => 'ok',
        'laravel_version' => app()->version(),
        'php_version' => PHP_VERSION,
        'timestamp' => now()->toIso8601String(),
    ];

    // Check database connectivity
    try {
        DB::connection()->getPdo();
        $status['database'] = [
            'status' => 'connected',
            'driver' => config('database.default'),
            'name' => DB::connection()->getDatabaseName(),
        ];
    } catch (\Exception $e) {
        $status['database'] = [
            'status' => 'disconnected',
            'error' => $e->getMessage(),
        ];
        $status['status'] = 'degraded';
    }

    // Check Redis connectivity
    try {
        $redis = Redis::connection();
        $pong = $redis->ping();
        $status['redis'] = [
            'status' => 'connected',
            'response' => $pong,
        ];
    } catch (\Exception $e) {
        $status['redis'] = [
            'status' => 'disconnected',
            'error' => $e->getMessage(),
        ];
        $status['status'] = 'degraded';
    }

    return response()->json($status, $status['status'] === 'ok' ? 200 : 503);
});
