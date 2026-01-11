<?php

use App\Http\Controllers\ordrApiController;
use Illuminate\Support\Facades\Route;

Route::get("/getData", [ordrApiController::class, "create"])->name("getData");
         
?>











