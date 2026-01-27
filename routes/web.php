<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\BeritaIndex;
use App\Livewire\BeritaShow;
use App\Livewire\ArtikelIndex;
use App\Livewire\ArtikelShow;
use App\Livewire\Profil;
use App\Livewire\GaleriIndex;
use App\Livewire\KasDigital;
use App\Livewire\UmkmIndex;
use App\Livewire\PetaMasjid;
use App\Livewire\TanyaKiai;
use App\Livewire\ZakatCalculator;
use App\Livewire\RuangDoa;

// Home
Route::get('/', Home::class)->name('home');

// Berita
Route::get('/berita', BeritaIndex::class)->name('berita.index');
Route::get('/berita/{slug}', BeritaShow::class)->name('berita.show');

// Artikel
Route::get('/artikel', ArtikelIndex::class)->name('artikel.index');
Route::get('/artikel/{slug}', ArtikelShow::class)->name('artikel.show');

// Profil
Route::get('/profil', Profil::class)->name('profil');

// Galeri
Route::get('/galeri', GaleriIndex::class)->name('galeri.web');

// KAS Digital
Route::get('/kas-digital', KasDigital::class)->name('kas-digital');

// UMKM
Route::get('/umkm', UmkmIndex::class)->name('umkm.index');

// Peta Masjid
Route::get('/peta-masjid', PetaMasjid::class)->name('peta-masjid');

// Tanya Kiai
Route::get('/tanya-kiai', TanyaKiai::class)->name('tanya-kiai');

// Zakat Calculator
Route::get('/zakat', ZakatCalculator::class)->name('zakat');

// Ruang Doa
Route::get('/ruang-doa', RuangDoa::class)->name('ruang-doa');