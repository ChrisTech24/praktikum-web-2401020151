<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

Route::get('/', function () {
    return view('welcome');
});

// Route Pertemuan 2
Route::get('/latihan-php', function () {
    $nama = 'Christian Aprilio Sihite';
    $nilai = [80, 85, 90, 78, 88];

    $hitungRataRata = function ($arr) {
        $total = 0;
        foreach ($arr as $n) {
            $total += $n;
        }
        return $total / count($arr);
    };

    $rataRata = $hitungRataRata($nilai);
    $rataRataFormat = number_format($rataRata, 2);
    $status = ($rataRata >= 75) ? 'Lulus' : 'Perlu Perbaikan';

    return view('latihan', [
        'nama' => $nama,
        'nilai' => $nilai,
        'rataRata' => $rataRataFormat,
        'status' => $status
    ]);
});

// Route Pertemuan 3: GET Form
Route::get('/form-mahasiswa', function () {
    return view('form-mahasiswa');
});

// Route Pertemuan 3: POST Form
Route::post('/form-mahasiswa', function (Request $request) {
    // Sanitasi
    $dataBersih = [
        'nama'  => strip_tags(trim((string) $request->input('nama'))),
        'nim'   => trim((string) $request->input('nim')),
        'email' => filter_var((string) $request->input('email'), FILTER_SANITIZE_EMAIL),
        'usia'  => trim((string) $request->input('usia')),
    ];

    // Validasi
    $validator = Validator::make($dataBersih, [
        'nama'  => ['required', 'min:3', 'max:50'],
        'nim'   => ['required', 'numeric', 'digits_between:8,12'],
        'email' => ['required', 'email'],
        'usia'  => ['required', 'integer', 'min:17', 'max:60'],
    ], [
        'nama.required'      => 'Nama wajib diisi.',
        'nama.min'           => 'Nama minimal 3 karakter.',
        'nama.max'           => 'Nama maksimal 50 karakter.',
        'nim.required'       => 'NIM wajib diisi.',
        'nim.numeric'        => 'NIM harus berupa angka.',
        'nim.digits_between' => 'NIM harus berisi 8 sampai 12 digit.',
        'email.required'     => 'Email wajib diisi.',
        'email.email'        => 'Format email tidak valid.',
        'usia.required'      => 'Usia wajib diisi.',
        'usia.integer'       => 'Usia harus berupa angka.',
        'usia.min'           => 'Usia minimal 17 tahun.',
        'usia.max'           => 'Usia maksimal 60 tahun.',
    ]);

    if ($validator->fails()) {
        return redirect('/form-mahasiswa')
            ->withErrors($validator)
            ->withInput();
    }

    $data = $validator->validated();
    $data['usia'] = (int) $data['usia'];

    return view('hasil-form', ['data' => $data]);
});