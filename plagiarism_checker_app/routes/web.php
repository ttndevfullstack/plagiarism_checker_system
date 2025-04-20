<?php

use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return redirect(route(config("plagiarism-checker.panels.user.routes.dashboard")));
});

Route::get('/health', function () {
    try {
        return response()->json(['status' => 'healthy']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'unhealthy', 'error' => $e->getMessage()], 500);
    }
});
