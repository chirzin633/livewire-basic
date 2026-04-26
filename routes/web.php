<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::users');
Route::livewire('/count', 'pages::post.create');
