<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use App\Models\BookCover;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $slug = 'buku';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Buku';

    protected static ?string $modelLabel = 'Buku';

    protected static ?string $pluralModelLabel = 'Daftar Buku';

    protected static ?int $navigationSort = 2;

    /**
     * Cek apakah kategori yang dipilih termasuk non-ISBN (TA/Skripsi atau Proposal TA/PKL & Laporan PKL)
     */
    protected static function isNonIsbnCategory(?int $categoryId): bool
    {
        if (! $categoryId) {
            return false;
        }

        $category = Category::find($categoryId);
        if (! $category) {
            return false;
        }

        $nama = strtolower($category->nama);

        return str_contains($nama, 'ta')
            || str_contains($nama, 'tugas akhir')
            || str_contains($nama, 'skripsi')
            || str_contains($nama, 'pkl')
            || str_contains($nama, 'proposal');
    }

    /**
     * Resolve URL gambar cover dari disk 'uploads' (permanen di public/uploads)
     * atau fallback ke disk 'public' jika masih ada file lama.
     */
    protected static function resolveCoverUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (Storage::disk('uploads')->exists($path)) {
            return asset('uploads/' . ltrim($path, '/'));
        }

        if (Storage::disk('public')->exists($path)) {
            return route('storage.file', ['path' => $path]);
        }

        return null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Klasifikasi & Identitas Buku')
                    ->description('Pilih kategori buku, kode koleksi, judul, dan penulis.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama')
                                            ->label('Nama Kategori')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(Category::class, 'nama')
                                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? ucwords(strtolower(trim($state))) : null),
                                    ])
                                    ->createOptionAction(function ($action) {
                                        return $action
                                            ->modalHeading('Tambah Kategori Baru')
                                            ->modalSubmitActionLabel('Simpan Kategori')
                                            ->modalWidth('md');
                                    })
                                    ->createOptionUsing(function (array $data): int {
                                        $nama = ucwords(strtolower(trim($data['nama'])));
                                        $baseSlug = Str::slug($nama) ?: 'kategori';
                                        $slug = $baseSlug;
                                        $counter = 1;

                                        while (Category::where('slug', $slug)->exists()) {
                                            $slug = "{$baseSlug}-{$counter}";
                                            $counter++;
                                        }

                                        $category = Category::create([
                                            'nama' => $nama,
                                            'slug' => $slug,
                                        ]);

                                        return $category->id;
                                    })
                                    ->suffixAction(
                                        Action::make('hapusKategori')
                                            ->icon('heroicon-o-trash')
                                            ->color('danger')
                                            ->tooltip('Hapus kategori yang sedang dipilih')
                                            ->requiresConfirmation()
                                            ->modalHeading('Hapus Kategori')
                                            ->modalDescription('Kategori yang dipilih akan dihapus permanen. Pastikan tidak ada buku yang masih menggunakan kategori ini.')
                                            ->visible(fn (Forms\Get $get) => filled($get('category_id')))
                                            ->action(function (Forms\Get $get, Forms\Set $set) {
                                                $categoryId = $get('category_id');
                                                $category = Category::find($categoryId);

                                                if (! $category) {
                                                    return;
                                                }

                                                if ($category->books()->exists()) {
                                                    Notification::make()
                                                        ->title('Kategori Tidak Bisa Dihapus')
                                                        ->body('Masih ada buku yang menggunakan kategori "' . $category->nama . '". Hapus atau pindahkan bukunya terlebih dahulu.')
                                                        ->danger()
                                                        ->send();

                                                    return;
                                                }

                                                $namaKategori = $category->nama;
                                                $category->delete();
                                                $set('category_id', null);

                                                Notification::make()
                                                    ->title('Kategori Berhasil Dihapus')
                                                    ->body('Kategori "' . $namaKategori . '" telah dihapus.')
                                                    ->success()
                                                    ->send();
                                            })
                                    )
                                    ->required(),

                                Forms\Components\TextInput::make('book_code')
                                    ->label('Kode Buku / Koleksi')
                                    ->placeholder('Contoh: AKT-001, TA-2026-001, atau PKL-2026-012')
                                    ->helperText('Wajib untuk TA/Skripsi & Laporan PKL; opsional sebagai kode inventaris untuk buku umum.')
                                    ->maxLength(50)
                                    ->unique(table: Book::class, column: 'book_code', ignoreRecord: true)
                                    ->required(fn (Forms\Get $get): bool => self::isNonIsbnCategory($get('category_id')))
                                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? strtoupper(trim($state)) : null),

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
                            ]),
                    ]),

                Forms\Components\Section::make('Detail Terbit & Identitas ISBN')
                    ->description('Informasi penerbit, tahun terbit, dan pencarian data otomatis lewat ISBN.')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
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
                                    ->helperText('Nomor ISBN standar internasional (hanya untuk buku umum).')
                                    ->maxLength(20)
                                    ->nullable()
                                    ->visible(fn (Forms\Get $get): bool => ! self::isNonIsbnCategory($get('category_id')))
                                    ->unique(
                                        table: Book::class,
                                        column: 'isbn',
                                        ignoreRecord: true,
                                        modifyRuleUsing: fn (Unique $rule) => $rule->whereNotNull('isbn')
                                    )
                                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? trim($state) : null)
                                    ->live(onBlur: true)
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('searchIsbn')
                                            ->icon('heroicon-m-magnifying-glass')
                                            ->tooltip('Cari data buku berdasarkan ISBN')
                                            ->action(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                                $isbn = trim((string) ($state ?? $get('isbn')));

                                                if (blank($isbn)) {
                                                    Notification::make()
                                                        ->title('ISBN Masih Kosong')
                                                        ->body('Silakan ketik nomor ISBN terlebih dahulu sebelum mencari.')
                                                        ->warning()
                                                        ->send();

                                                    return;
                                                }

                                                $cleanIsbn = preg_replace('/[^0-9X]/i', '', $isbn);

                                                if (empty($cleanIsbn)) {
                                                    Notification::make()
                                                        ->title('Format ISBN Tidak Valid')
                                                        ->body('Nomor ISBN hanya boleh mengandung angka dan huruf X.')
                                                        ->warning()
                                                        ->send();

                                                    return;
                                                }

                                                $title = null;
                                                $authors = null;
                                                $publisher = null;
                                                $publishedYear = null;
                                                $description = null;

                                                // 1. Google Books API
                                                try {
                                                    $googleResponse = Http::withoutVerifying()
                                                        ->connectTimeout(8)
                                                        ->timeout(15)
                                                        ->get('https://www.googleapis.com/books/v1/volumes', [
                                                            'q' => 'isbn:' . $cleanIsbn,
                                                        ]);

                                                    if ($googleResponse->successful() && $googleResponse->json('totalItems', 0) > 0) {
                                                        $volumeInfo = $googleResponse->json('items.0.volumeInfo', []);

                                                        $title = $volumeInfo['title'] ?? null;
                                                        if (! empty($volumeInfo['subtitle'])) {
                                                            $title .= ': ' . $volumeInfo['subtitle'];
                                                        }

                                                        if (! empty($volumeInfo['authors']) && is_array($volumeInfo['authors'])) {
                                                            $authors = implode(', ', $volumeInfo['authors']);
                                                        }

                                                        $publisher = $volumeInfo['publisher'] ?? null;

                                                        if (! empty($volumeInfo['publishedDate']) && preg_match('/\b\d{4}\b/', (string) $volumeInfo['publishedDate'], $matches)) {
                                                            $publishedYear = (int) $matches[0];
                                                        }

                                                        $description = $volumeInfo['description'] ?? null;
                                                    }
                                                } catch (\Throwable $e) {
                                                    // Abaikan jika Google Books timeout, lanjut ke Open Library
                                                }

                                                // 2. Fallback ke Open Library API
                                                if (empty($title) || empty($authors) || empty($publisher) || empty($publishedYear) || empty($description)) {
                                                    try {
                                                        $openLibraryResponse = Http::withoutVerifying()
                                                            ->connectTimeout(8)
                                                            ->timeout(15)
                                                            ->get('https://openlibrary.org/api/books', [
                                                                'bibkeys' => 'ISBN:' . $cleanIsbn,
                                                                'format' => 'json',
                                                                'jscmd' => 'data',
                                                            ]);

                                                        if ($openLibraryResponse->successful()) {
                                                            $bookData = $openLibraryResponse->json('ISBN:' . $cleanIsbn);
                                                            if (! empty($bookData)) {
                                                                $title = $title ?? ($bookData['title'] ?? null);

                                                                if (empty($authors) && ! empty($bookData['authors']) && is_array($bookData['authors'])) {
                                                                    $authors = collect($bookData['authors'])->pluck('name')->filter()->implode(', ');
                                                                }

                                                                if (empty($publisher) && ! empty($bookData['publishers']) && is_array($bookData['publishers'])) {
                                                                    $publisher = collect($bookData['publishers'])->pluck('name')->filter()->implode(', ');
                                                                }

                                                                if (empty($publishedYear) && ! empty($bookData['publish_date']) && preg_match('/\b\d{4}\b/', (string) $bookData['publish_date'], $matches)) {
                                                                    $publishedYear = (int) $matches[0];
                                                                }

                                                                if (empty($description)) {
                                                                    $rawDesc = $bookData['notes'] ?? $bookData['subtitle'] ?? null;
                                                                    if (is_array($rawDesc) && isset($rawDesc['value'])) {
                                                                        $rawDesc = $rawDesc['value'];
                                                                    }
                                                                    if (is_string($rawDesc)) {
                                                                        $description = $rawDesc;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    } catch (\Throwable $e) {
                                                        // Abaikan timeout Open Library agar tidak memunculkan error merah
                                                    }
                                                }

                                                if (empty($title) && empty($authors) && empty($publisher)) {
                                                    Notification::make()
                                                        ->title('Data ISBN Tidak Ditemukan')
                                                        ->body("Data untuk ISBN {$isbn} tidak tersedia di server atau koneksi sedang lambat. Silakan isi manual.")
                                                        ->warning()
                                                        ->send();

                                                    return;
                                                }

                                                if ($title) {
                                                    $set('judul', ucwords(strtolower(trim($title))));
                                                }
                                                if ($authors) {
                                                    $set('penulis', ucwords(strtolower(trim($authors))));
                                                }
                                                if ($publisher) {
                                                    $set('penerbit', trim($publisher));
                                                }
                                                if ($publishedYear) {
                                                    $set('tahun_terbit', $publishedYear);
                                                }
                                                if ($description) {
                                                    $set('sinopsis', trim(strip_tags($description)));
                                                }

                                                Notification::make()
                                                    ->title('Data Buku Berhasil Ditemukan')
                                                    ->body("Informasi buku '{$title}' berhasil dimuat otomatis.")
                                                    ->success()
                                                    ->send();
                                            })
                                    ),
                            ]),
                    ]),

                Forms\Components\Section::make('Stok, Cover & Deskripsi')
                    ->description('Atur jumlah stok tersedia. Cover dan deskripsi/sinopsis bersifat opsional.')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('stok')
                                    ->label('Stok Tersedia')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(0)
                                    ->required(),

                                Forms\Components\FileUpload::make('cover')
                                    ->label('Cover Buku (Opsional)')
                                    ->helperText('Format JPG/PNG/WebP maksimal 2 MB. Boleh dikosongkan jika tidak ada gambar sampul.')
                                    ->image()
                                    ->disk('uploads')
                                    ->directory('covers')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->previewable(false)
                                    ->fetchFileInformation(false)
                                    ->getUploadedFileUsing(function (string $file): ?array {
                                        if (Storage::disk('uploads')->exists($file)) {
                                            return [
                                                'name' => basename($file),
                                                'size' => Storage::disk('uploads')->size($file),
                                                'type' => Storage::disk('uploads')->mimeType($file),
                                                'url' => asset('uploads/'.ltrim($file, '/')),
                                            ];
                                        }

                                        $cleanFile = ltrim($file, '/');
                                        if (! Storage::disk('public')->exists($cleanFile)) {
                                            BookCover::restoreToPublicDisk($cleanFile);
                                        }

                                        if (Storage::disk('public')->exists($cleanFile)) {
                                            return [
                                                'name' => basename($cleanFile),
                                                'size' => Storage::disk('public')->size($cleanFile),
                                                'type' => Storage::disk('public')->mimeType($cleanFile),
                                                'url' => route('storage.file', ['path' => $cleanFile]),
                                            ];
                                        }

                                        return null;
                                    })
                                    ->nullable(),
                            ]),

                        Forms\Components\Textarea::make('sinopsis')
                            ->label('Deskripsi / Sinopsis Buku (Opsional)')
                            ->placeholder('Boleh dikosongkan atau tulis ringkasan isi buku di sini...')
                            ->rows(4)
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Buku Perpustakaan Jurusan Akuntansi')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\ImageEntry::make('cover')
                                    ->getStateUsing(fn (Book $record): ?string => $record->cover_url ?? self::resolveCoverUrl($record->cover))
                                    ->placeholder('Belum ada cover')
                                    ->label('Cover Buku')
                                    ->columnSpan(1),
                                Infolists\Components\Grid::make(2)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('judul')
                                            ->label('Judul Buku')
                                            ->weight('bold')
                                            ->columnSpanFull(),
                                        Infolists\Components\TextEntry::make('book_code')
                                            ->label('Kode Buku')
                                            ->placeholder('-'),
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
                                            ->formatStateUsing(fn (int $state): string => $state > 0 ? "Tersedia ({$state})" : 'Habis (0)')
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
                            ->label('Deskripsi / Sinopsis Buku')
                            ->placeholder('Belum ada deskripsi untuk buku ini.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')
                    ->getStateUsing(fn (Book $record): ?string => $record->cover_url ?? self::resolveCoverUrl($record->cover))
                    ->placeholder('-')
                    ->label('Cover')
                    ->square()
                    ->size(48),
                Tables\Columns\TextColumn::make('book_code')
                    ->label('Kode Buku')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
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
                    ->formatStateUsing(fn (int $state): string => $state > 0 ? "Tersedia ({$state})" : 'Habis (0)')
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
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
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