# Praktikum Pemrograman Web - Pertemuan 10

**Nama:** ................................................  
**NIM:** 2401020151  
**Mata kuliah:** Praktikum Pemrograman Web (INF11197)

Project ini merupakan lanjutan praktikum Modul 9 (CRUD mahasiswa) dengan tambahan
CRUD Buku menggunakan migration, seeder, model Eloquent, relasi
`Buku belongsTo Kategori` dan `Kategori hasMany Buku`, resource controller,
validasi, serta proteksi CSRF.

## Fitur Modul 10

- Migration tabel `kategoris` dan `bukus` beserta foreign key `kategori_id`.
- Seeder dua kategori (`Pemrograman`, `Basis Data`) dan dua buku awal (`BK001`, `BK002`).
- CRUD buku: daftar, tambah, ubah, hapus.
- Validasi kode unik, kode maksimal 10 karakter, judul wajib, dan kategori valid.
- Eager loading `Buku::with('kategori')` pada halaman daftar.
- Form POST/PUT/DELETE memakai `@csrf` dan `@method`.
- Pengujian Pest untuk create, read, update, delete, dan validasi.

## Menjalankan project

1. Pastikan PHP 8.3+, Composer, dan MySQL berjalan.
2. `composer install`
3. Salin `.env.example` menjadi `.env`, lalu sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.
4. `php artisan key:generate`
5. `php artisan migrate --seed`
6. `php artisan serve`
7. Buka `http://127.0.0.1:8000/buku`.

Database yang dipakai pada praktikum ini: `praktikum_web_2401020151`.

## Route utama

| Method | URI | Name | Keterangan |
|---|---|---|---|
| GET | `/buku` | `buku.index` | Daftar buku dan kategorinya |
| GET | `/buku/create` | `buku.create` | Form tambah buku |
| POST | `/buku` | `buku.store` | Simpan buku |
| GET | `/buku/{buku}/edit` | `buku.edit` | Form ubah buku |
| PUT/PATCH | `/buku/{buku}` | `buku.update` | Perbarui buku |
| DELETE | `/buku/{buku}` | `buku.destroy` | Hapus buku |

Route Modul 9 (`/mahasiswa`) tetap dipertahankan.

## Struktur tabel

### `kategoris`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary key, auto increment |
| `nama` | VARCHAR(255) | Unik, wajib |
| `created_at`, `updated_at` | TIMESTAMP | Waktu dibuat/diubah |

### `bukus`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT UNSIGNED | Primary key, auto increment |
| `kode` | VARCHAR(10) | Unik, wajib |
| `judul` | VARCHAR(255) | Wajib |
| `kategori_id` | BIGINT UNSIGNED | Foreign key ke `kategoris.id` |
| `created_at`, `updated_at` | TIMESTAMP | Waktu dibuat/diubah |

## Pengujian

```bash
php artisan test tests/Feature/BukuCrudTest.php
```

Skenario yang diuji: halaman indeks, form tambah, simpan data valid,
tolak kode duplikat, tolak judul kosong, ubah, dan hapus buku.

## Laporan

Laporan praktikum beserta tangkapan layar tersedia pada folder [`laporan`](laporan).
