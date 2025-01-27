<?php

use Illuminate\Support\Facades\Route;

Route::get('/', static function () {
    return redirect(route(config("plagiarism-checker.panels.user.routes.dashboard")));
});
