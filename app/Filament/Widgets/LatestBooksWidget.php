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

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Book::query()
                    ->with('category')
                    ->latest()
                    ->limit(5)
            )
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('book_code')
                    ->label('Kode Buku')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Buku')
                    ->weight('bold')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('category.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('penulis')
                    ->label('Penulis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger'),
            ])
            ->recordUrl(fn (Book $record): string => route('filament.admin.resources.buku.edit', ['record' => $record]))
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Detail / Edit')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(fn (Book $record): string => route('filament.admin.resources.buku.edit', ['record' => $record]))
                    ->size('xs')
                    ->color('gray'),
            ])
            ->emptyStateHeading('Belum ada koleksi buku')
            ->emptyStateDescription('Klik tombol di bawah untuk mulai menambahkan buku.')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Tambah Buku Baru')
                    ->url(route('filament.admin.resources.buku.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ]);
    }
}
