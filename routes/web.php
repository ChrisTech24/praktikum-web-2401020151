<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rute Praktikum Pertemuan 2
Route::get('/latihan-php', function () {
    $nama = 'Christian Aprilio Sihite';
    
    // Nilai untuk Pengujian 2 (Status: Perlu Perbaikan)
    // Untuk Pengujian 2, ganti baris ini menjadi: $nilai = [60, 65, 70, 50, 68];
    $nilai = [60, 65, 70, 50, 68]; 

    // Closure / Fungsi anonim untuk menghitung rata-rata
    $hitungRataRata = function ($arr) {
        $total = 0;
        foreach ($arr as $n) {
            $total += $n;
        }
        return $total / count($arr);
    };

    $rataRata = $hitungRataRata($nilai);
    $rataRataFormat = number_format($rataRata, 2);

    // Operator Ternary untuk menentukan status kelulusan
    $status = ($rataRata >= 75) ? 'Lulus' : 'Perlu Perbaikan';

    return view('latihan', [
        'nama' => $nama,
        'nilai' => $nilai,
        'rataRata' => $rataRataFormat,
        'status' => $status
    ]);
});