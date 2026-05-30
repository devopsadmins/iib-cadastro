<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/health', function () {
    $dbOk = true;
    $dbMessage = 'ok';

    try {
        DB::select('SELECT 1');
    } catch (Throwable $e) {
        $dbOk = false;
        $dbMessage = $e->getMessage();
    }

    return response()->json([
        'status' => $dbOk ? 'healthy' : 'degraded',
        'timestamp' => now()->toIso8601String(),
        'app' => config('app.name'),
        'env' => config('app.env'),
        'checks' => [
            'database' => [
                'ok' => $dbOk,
                'message' => $dbMessage,
            ],
        ],
    ], $dbOk ? 200 : 503);
});
