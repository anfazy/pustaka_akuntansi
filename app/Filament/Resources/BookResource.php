<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Buku';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('judul')
                    ->label('Judul Buku')
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? ucwords(strtolower(trim($state))) : null),

                Forms\Components\TextInput::make('penulis')
                    ->label('Penulis')
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? ucwords(strtolower(trim($state))) : null),

                Forms\Components\TextInput::make('penerbit')
                    ->label('Penerbit')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('tahun_terbit')
                    ->label('Tahun Terbit')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(date('Y'))
                    ->required(),

                Forms\Components\TextInput::make('isbn')
                    ->label('ISBN')
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('stok')
                    ->label('Stok Tersedia')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->required(),

                Forms\Components\FileUpload::make('cover')
                    ->label('Cover Buku')
                    ->image()
                    ->directory('covers')
                    ->disk('public'),

                Forms\Components\Textarea::make('sinopsis')
                    ->label('Sinopsis / Abstrak')
                    ->placeholder('Tulis ringkasan isi buku atau abstrak Tugas Akhir/Skripsi di sini...')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Koleksi Pustaka Akuntansi')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\ImageEntry::make('cover')
                                    ->disk('public')
                                    ->label('Cover Buku')
                                    ->columnSpan(1),
                                Infolists\Components\Grid::make(2)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('judul')
                                            ->label('Judul Buku')
                                            ->weight('bold')
                                            ->columnSpanFull(),
                                        Infolists\Components\TextEntry::make('category.nama')
                                            ->label('Kategori')
                                            ->badge()
                                            ->color('info'),
                                        Infolists\Components\TextEntry::make('penulis')
                                            ->label('Penulis'),
                                        Infolists\Components\TextEntry::make('penerbit')
                                            ->label('Penerbit'),
                                        Infolists\Components\TextEntry::make('tahun_terbit')
                                            ->label('Tahun Terbit'),
                                        Infolists\Components\TextEntry::make('isbn')
                                            ->label('ISBN')
                                            ->placeholder('-'),
                                        Infolists\Components\TextEntry::make('stok')
                                            ->label('Stok Tersedia')
                                            ->badge()
                                            ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger'),
                                        Infolists\Components\TextEntry::make('active_loans_count')
                                            ->state(fn (Book $record): int => $record->activeLoans()->count())
                                            ->label('Sedang Dipinjam')
                                            ->badge()
                                            ->color(fn (int $state): string => $state > 0 ? 'warning' : 'gray'),
                                    ])
                                    ->columnSpan(2),
                            ]),
                        Infolists\Components\TextEntry::make('sinopsis')
                            ->label('Sinopsis / Abstrak')
                            ->placeholder('Belum ada sinopsis untuk buku ini.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')
                    ->disk('public')
                    ->label('Cover'),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Buku')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.nama')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('penulis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_terbit')
                    ->label('Tahun')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('active_loans_count')
                    ->counts('activeLoans')
                    ->label('Dipinjam')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'warning' : 'gray')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'nama'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
