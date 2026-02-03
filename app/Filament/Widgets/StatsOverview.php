<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\News;
use App\Models\Gallery;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $articleCount = Article::count();
        $newsCount = News::count();
        $imageCount = Gallery::where('type', 'photo')->count();
        $videoCount = Gallery::where('type', 'video')->count();
        $totalDonation = \App\Models\Donation::where('status', 'verified')->sum('amount');

        return [
            Stat::make('Kas Saldo Keuangan', 'Rp ' . number_format($balance, 0, ',', '.'))
                ->description('Total saldo saat ini')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success'),

            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalIncome, 0, ',', '.'))
                ->description('Total seluruh pemasukan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalExpense, 0, ',', '.'))
                ->description('Total seluruh pengeluaran')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Total Donasi', 'Rp ' . number_format($totalDonation, 0, ',', '.'))
                ->description('Total donasi terverifikasi')
                ->descriptionIcon('heroicon-m-heart')
                ->color('primary'),

            Stat::make('Jumlah Artikel', $articleCount)
                ->description('Total artikel diterbitkan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),

            Stat::make('Jumlah Berita', $newsCount)
                ->description('Total berita diterbitkan')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary'),

            Stat::make('Jumlah Gambar', $imageCount)
                ->description('Total koleksi gambar')
                ->descriptionIcon('heroicon-m-photo')
                ->color('info'),

            Stat::make('Jumlah Video', $videoCount)
                ->description('Total koleksi video')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color('success'),
        ];
    }
}
