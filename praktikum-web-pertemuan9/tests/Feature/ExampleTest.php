<?php

test('halaman utama mengarah ke daftar mahasiswa', function () {
    $this->get('/')->assertRedirect('/mahasiswa');
});
