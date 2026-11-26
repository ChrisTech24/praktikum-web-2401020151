<?php

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function buatKategori(string $nama = 'Pemrograman'): Kategori
{
    return Kategori::create(['nama' => $nama]);
}

test('halaman indeks menampilkan buku beserta kategorinya', function () {
    $kategori = buatKategori();
    Buku::create([
        'kode' => 'BK001',
        'judul' => 'Dasar PHP',
        'kategori_id' => $kategori->id,
    ]);

    $this->get(route('buku.index'))
        ->assertOk()
        ->assertSee('BK001')
        ->assertSee('Dasar PHP')
        ->assertSee('Pemrograman');
});

test('form tambah buku menampilkan pilihan kategori', function () {
    buatKategori('Basis Data');

    $this->get(route('buku.create'))
        ->assertOk()
        ->assertSee('Tambah Buku')
        ->assertSee('Basis Data');
});

test('buku valid dapat disimpan', function () {
    $kategori = buatKategori();

    $this->post(route('buku.store'), [
        'kode' => 'BK003',
        'judul' => 'Pengantar SQL',
        'kategori_id' => $kategori->id,
    ])->assertRedirect(route('buku.index'))
        ->assertSessionHas('status', 'Buku ditambahkan.');

    $this->assertDatabaseHas('bukus', [
        'kode' => 'BK003',
        'judul' => 'Pengantar SQL',
        'kategori_id' => $kategori->id,
    ]);
});

test('kode buku duplikat ditolak dan data lama tidak berubah', function () {
    $kategori = buatKategori();
    Buku::create([
        'kode' => 'BK001',
        'judul' => 'Dasar PHP',
        'kategori_id' => $kategori->id,
    ]);

    $this->from(route('buku.create'))
        ->post(route('buku.store'), [
            'kode' => 'BK001',
            'judul' => 'Buku Lain',
            'kategori_id' => $kategori->id,
        ])
        ->assertRedirect(route('buku.create'))
        ->assertSessionHasErrors('kode');

    expect(Buku::count())->toBe(1);
    expect(Buku::first()->judul)->toBe('Dasar PHP');
});

test('judul kosong ditolak oleh validasi', function () {
    $kategori = buatKategori();

    $this->from(route('buku.create'))
        ->post(route('buku.store'), [
            'kode' => 'BK010',
            'judul' => '',
            'kategori_id' => $kategori->id,
        ])
        ->assertRedirect(route('buku.create'))
        ->assertSessionHasErrors('judul');

    expect(Buku::count())->toBe(0);
});

test('buku dapat diubah dan dihapus', function () {
    $kategori = buatKategori();
    $buku = Buku::create([
        'kode' => 'BK003',
        'judul' => 'Pengantar SQL',
        'kategori_id' => $kategori->id,
    ]);

    $this->put(route('buku.update', $buku), [
        'kode' => 'BK003',
        'judul' => 'Pengantar Basis Data',
        'kategori_id' => $kategori->id,
    ])->assertRedirect(route('buku.index'));

    expect($buku->fresh()->judul)->toBe('Pengantar Basis Data');

    $this->delete(route('buku.destroy', $buku))
        ->assertRedirect(route('buku.index'));

    $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
});
