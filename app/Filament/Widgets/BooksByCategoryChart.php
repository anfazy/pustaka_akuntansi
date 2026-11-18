<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Category;
use Filament\Widgets\ChartWidget;

class BooksByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Koleksi Buku per Kategori';

    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '280px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    public ?string $filter = 'all';

    public function getDescription(): ?string
    {
        $hasData = Category::count() > 0 && Book::count() > 0;

        if (! $hasData) {
            return 'Belum ada data koleksi buku pada sistem.';
        }

        $filterLabel = match ($this->filter) {
            'year' => 'sepanjang tahun ini',
            'month' => 'sepanjang bulan ini',
            'week' => 'sepanjang minggu ini',
            default => 'keseluruhan periode',
        };

        return "Sebaran judul buku terdaftar ({$filterLabel}).";
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'Semua Periode',
            'year' => 'Tahun Ini',
            'month' => 'Bulan Ini',
            'week' => 'Minggu Ini',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter;

        $categories = Category::withCount([
            'books' => function ($query) use ($filter) {
                match ($filter) {
                    'year' => $query->whereYear('created_at', now()->year),
                    'month' => $query->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month),
                    'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                    default => $query,
                };
            },
        ])->get();

        $totalBooks = $categories->sum('books_count');

        // Jika Category kosong atau total buku 0, tampilkan 1 segmen abu-abu fallback
        if ($categories->isEmpty() || $totalBooks === 0) {
            return [
                'datasets' => [
                    [
                        'label' => 'Status Koleksi',
                        'data' => [1],
                        'backgroundColor' => ['#94a3b8'],
                        'borderWidth' => 0,
                    ],
                ],
                'labels' => ['Belum ada data koleksi'],
            ];
        }

        $labels = $categories->pluck('nama')->toArray();
        $data = $categories->pluck('books_count')->toArray();

        $palette = [
            '#3b82f6', '#6366f1', '#10b981', '#f59e0b',
            '#8b5cf6', '#ec4899', '#06b6d4', '#14b8a6',
            '#f97316', '#64748b',
        ];

        $backgroundColors = array_slice($palette, 0, count($labels));
        if (count($labels) > count($backgroundColors)) {
            $backgroundColors = array_pad($backgroundColors, count($labels), '#6366f1');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Judul Buku',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderWidth' => 2,
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'right',
                    'align' => 'center',
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'boxWidth' => 8,
                        'padding' => 12,
                        'font' => [
                            'family' => 'inherit',
                            'size' => 12,
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
            'cutout' => '65%',
            'maintainAspectRatio' => false,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
