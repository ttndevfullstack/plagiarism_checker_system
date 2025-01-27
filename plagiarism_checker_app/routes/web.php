<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route(config("plagiarism-checker.panels.user.routes.dashboard")));
});
