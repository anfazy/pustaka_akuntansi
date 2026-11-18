<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBooksWidget extends BaseWidget
{
    protected static ?string $heading = 'Koleksi Buku Terbaru';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Book::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Buku')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('penulis')
                    ->label('Penulis'),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger'),
            ])
            ->emptyStateHeading('Belum ada koleksi buku')
            ->emptyStateDescription('Klik tombol di bawah untuk mulai menambahkan buku.')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Tambah Buku Baru')
                    ->url(route('filament.admin.resources.books.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ]);
    }
}