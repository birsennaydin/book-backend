<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web','auth','verified','role:writer'])
    ->prefix('writer')
    ->name('writer.')
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\Writer\Dashboard::class)->name('dashboard');
        Route::get('/books', \App\Livewire\Writer\Books\Index::class)->name('books.index');
        Route::get('/books/create', \App\Livewire\Writer\Books\Form::class)->name('books.create');
        Route::get('/books/{book}/edit', \App\Livewire\Writer\Books\Form::class)->name('books.edit');
        Route::get('/books/{book}/chapters', \App\Livewire\Writer\Chapters\Index::class)->name('chapters.index');
        Route::get('/books/{book}/chapters/create', \App\Livewire\Writer\Chapters\Form::class)->name('chapters.create');
        Route::get('/chapters/{chapter}/edit', \App\Livewire\Writer\Chapters\Form::class)->name('chapters.edit');
    });

// Admin Panel (özet)
Route::middleware(['web','auth','verified','role:admin|editor'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        // … kitap onayları, banner, ödemeler, vb.
    });
