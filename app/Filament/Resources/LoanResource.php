<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Peminjaman & Pengembalian';

    protected static ?string $modelLabel = 'Peminjaman';

    protected static ?string $pluralModelLabel = 'Peminjaman & Pengembalian Buku';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('status', ['dipinjam', 'terlambat'])->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overdueThreshold = today()->subDays(7)->toDateString();

        $hasOverdue = static::getModel()::where('status', 'terlambat')
            ->orWhere(function ($query) use ($overdueThreshold) {
                $query->where('status', 'dipinjam')
                    ->where('tanggal_pinjam', '<', $overdueThreshold);
            })
            ->exists();

        return $hasOverdue ? 'danger' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Peminjam (NIM & Kartu)')
                    ->description('Peminjaman berbasis NIM dengan kartu identitas resmi (KTM / Kartu Perpustakaan / Asuransi). Maksimal 2 buku aktif per NIM.')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Cari Data Mahasiswa')
                            ->relationship('member', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} (NIM: {$record->nis_nip} • {$record->kelas_jabatan})")
                            ->searchable(['nama', 'nis_nip'])
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                if ($state) {
                                    $member = Member::find($state);
                                    if ($member) {
                                        $set('member_nim', $member->nis_nip);
                                    }
                                }
                            })
                            ->required(),

                        Forms\Components\TextInput::make('member_nim')
                            ->label('NIM Mahasiswa')
                            ->helperText('Nomor Induk Mahasiswa sebagai ID peminjam utama.')
                            ->required()
                            ->live()
                            ->rules([
                                fn (?Loan $record): \Closure => function (string $attribute, $value, \Closure $fail) use ($record) {
                                    if (! $value) {
                                        return;
                                    }
                                    $activeCount = Loan::where('member_nim', trim($value))
                                        ->whereIn('status', ['dipinjam', 'terlambat'])
                                        ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                                        ->count();

                                    if ($activeCount >= 2) {
                                        $fail("Mahasiswa dengan NIM {$value} sudah memiliki {$activeCount} buku aktif yang sedang dipinjam. SOP perpustakaan: maksimal 2 buku per kartu.");
                                    }
                                },
                            ]),

                        Forms\Components\Select::make('card_type')
                            ->label('Jenis Kartu Identitas yang Dijaminkan')
                            ->options([
                                'KTM' => 'KTM (Kartu Tanda Mahasiswa)',
                                'Kartu Perpustakaan' => 'Kartu Perpustakaan',
                                'Kartu Asuransi' => 'Kartu Asuransi',
                            ])
                            ->default('KTM')
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Detail Buku & Masa Peminjaman')
                    ->description('Batas waktu otomatis dihitung hari kerja (Senin - Jumat): TA/Skripsi 14 hari kerja, Buku Umum, Proposal TA/PKL dan Laporan PKL 7 hari kerja.')
                    ->schema([
                        Forms\Components\Select::make('book_id')
                            ->label('Buku yang Dipinjam')
                            ->relationship('book', 'judul', fn ($query) => $query->orderBy('judul'))
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                $kode = $record->book_code ? "[{$record->book_code}] " : '';
                                $tipe = $record->collection_type_label;

                                return "{$kode}{$record->judul} ({$tipe} • Sisa Stok: {$record->stok})";
                            })
                            ->searchable(['judul', 'isbn', 'book_code'])
                            ->preload()
                            ->live()
                            ->required()
                            ->rules([
                                fn (?Loan $record): \Closure => function (string $attribute, $value, \Closure $fail) use ($record) {
                                    if (! $value) {
                                        return;
                                    }
                                    $book = Book::find($value);
                                    if (! $book) {
                                        $fail('Buku tidak ditemukan.');

                                        return;
                                    }
                                    $isNew = ! $record;
                                    $bookChanged = $record && (int) $record->book_id !== (int) $value;
                                    if (($isNew || $bookChanged) && $book->stok <= 0) {
                                        $fail("Stok koleksi '{$book->judul}' sedang kosong (0) dan tidak dapat dipinjam.");
                                    }
                                },
                            ]),

                        Forms\Components\DatePicker::make('tanggal_pinjam')
                            ->label('Tanggal Pinjam')
                            ->default(today())
                            ->native(false)
                            ->live()
                            ->required(),

                        Forms\Components\Placeholder::make('info_jatuh_tempo')
                            ->label('Perhitungan Jatuh Tempo (Hari Kerja)')
                            ->content(function (Forms\Get $get): HtmlString {
                                $bookId = $get('book_id');
                                $tglPinjam = $get('tanggal_pinjam') ? Carbon::parse($get('tanggal_pinjam')) : today();

                                if (! $bookId) {
                                    return new HtmlString('<span class="text-gray-500 italic">Pilih buku terlebih dahulu untuk melihat tenggat waktu.</span>');
                                }

                                $book = Book::find($bookId);
                                $days = ($book?->collection_type === 'ta_skripsi') ? 14 : 7;
                                $jatuhTempo = $tglPinjam->copy()->addWeekdays($days);

                                return new HtmlString("
                                    <div class='text-sm text-blue-700 dark:text-blue-300 font-medium'>
                                        Tenggat: <strong>{$jatuhTempo->translatedFormat('l, d F Y')}</strong>
                                        <div class='text-xs text-gray-500 font-normal'>Durasi: {$days} hari kerja (Senin - Jumat) &bull; {$book?->collection_type_label}</div>
                                    </div>
                                ");
                            }),

                        Forms\Components\DatePicker::make('tanggal_kembali')
                            ->label('Tanggal Pengembalian (Aktual)')
                            ->helperText('Kosongkan saat buku masih dipinjam. Terisi otomatis tanggal hari ini ketika tombol "Kembalikan / Selesaikan" ditekan.')
                            ->nullable()
                            ->native(false),

                        Forms\Components\Select::make('status')
                            ->label('Status Transaksi')
                            ->options([
                                'dipinjam' => 'Dipinjam',
                                'dikembalikan' => 'Dikembalikan',
                                'terlambat' => 'Terlambat',
                            ])
                            ->default('dipinjam')
                            ->required(),

                        Forms\Components\TextInput::make('fine_amount')
                            ->label('Nominal Denda (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->helperText('Denda keterlambatan atau penggantian kehilangan buku.'),

                        Forms\Components\Toggle::make('is_lost')
                            ->label('Status Buku Hilang')
                            ->helperText('Aktifkan jika buku hilang (stok tidak akan dikembalikan ke rak).'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.nama')
                    ->label('Peminjam (NIM)')
                    ->description(fn (Loan $record): string => 'NIM: ' . ($record->member_nim ?? $record->member?->nis_nip ?? '-') . ' • ' . ($record->card_type ?? 'KTM'))
                    ->searchable(['members.nama', 'member_nim'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('book.judul')
                    ->label('Koleksi Buku')
                    ->description(function (Loan $record): string {
                        $book = $record->book;
                        $kode = $book?->book_code ? "[{$book->book_code}] " : '';

                        return $kode . ($book?->collection_type_label ?? 'Buku Umum');
                    })
                    ->weight('bold')
                    ->searchable(['books.judul', 'books.book_code'])
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tgl Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->state(fn (Loan $record): ?string => $record->jatuh_tempo?->translatedFormat('d M Y'))
                    ->description(function (Loan $record): ?string {
                        if ($record->status === 'dikembalikan' || ! $record->jatuh_tempo) {
                            return null;
                        }

                        $overdueDays = $record->calculateOverdueWeekdays(today());
                        if ($overdueDays > 0) {
                            return "Lewat {$overdueDays} hari kerja";
                        }
                        if (today()->equalTo($record->jatuh_tempo->startOfDay())) {
                            return 'Hari ini jatuh tempo';
                        }

                        return 'Sisa ' . today()->diffInDays($record->jatuh_tempo->startOfDay()) . ' hari';
                    })
                    ->color(fn (Loan $record): ?string => $record->isOverdue() ? 'danger' : 'gray')
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('tanggal_pinjam', $direction)),

                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Tgl Kembali')
                    ->date('d M Y')
                    ->placeholder('Masih Dipinjam')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function (string $state, Loan $record): string {
                        if ($record->is_lost) {
                            return 'Buku Hilang';
                        }
                        if ($state === 'dikembalikan') {
                            return 'Dikembalikan';
                        }
                        if ($record->isOverdue()) {
                            return 'Terlambat';
                        }

                        return 'Dipinjam';
                    })
                    ->color(function (string $state, Loan $record): string {
                        if ($record->is_lost) {
                            return 'danger';
                        }
                        if ($state === 'dikembalikan') {
                            return 'success';
                        }
                        if ($record->isOverdue()) {
                            return 'danger';
                        }

                        return 'warning';
                    })
                    ->icon(function (string $state, Loan $record): string {
                        if ($record->is_lost) {
                            return 'heroicon-m-x-circle';
                        }
                        if ($state === 'dikembalikan') {
                            return 'heroicon-m-check-circle';
                        }
                        if ($record->isOverdue()) {
                            return 'heroicon-m-exclamation-triangle';
                        }

                        return 'heroicon-m-clock';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('fine_amount')
                    ->label('Denda')
                    ->money('IDR', locale: 'id')
                    ->color(fn (int $state): string => $state > 0 ? 'danger' : 'gray')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'terlambat' => 'Terlambat',
                    ]),
                Tables\Filters\TernaryFilter::make('is_lost')
                    ->label('Status Kehilangan')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya Buku Hilang')
                    ->falseLabel('Buku Tidak Hilang'),
            ])
            ->actions([
                // Tombol Aksi Cepat "Kembalikan / Selesaikan"
                Tables\Actions\Action::make('kembalikanBuku')
                    ->label('Kembalikan / Selesaikan')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->visible(fn (Loan $record): bool => $record->status !== 'dikembalikan' && ! $record->is_lost)
                    ->form([
                        Forms\Components\Placeholder::make('detail_transaksi')
                            ->label('Detail Peminjaman')
                            ->content(function (Loan $record): HtmlString {
                                $book = $record->book;
                                $due = $record->jatuh_tempo;
                                $overdueDays = $record->calculateOverdueWeekdays(today());
                                $lateFine = $record->calculateLateFine(today());
                                $lostFine = $record->calculateLostFine();

                                $overdueHtml = $overdueDays > 0
                                    ? "<span class='text-red-600 font-bold'>Terlambat {$overdueDays} hari kerja (Denda: Rp " . number_format($lateFine, 0, ',', '.') . ')</span>'
                                    : "<span class='text-green-600 font-semibold'>Tepat Waktu (Tidak ada denda terlambat)</span>";

                                return new HtmlString("
                                    <div class='p-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm space-y-1.5 border border-gray-200 dark:border-gray-700'>
                                        <div><strong>Peminjam:</strong> {$record->member?->nama} (NIM: {$record->member_nim})</div>
                                        <div><strong>Jaminan Kartu:</strong> {$record->card_type}</div>
                                        <div><strong>Koleksi:</strong> {$book?->judul} <span class='text-xs px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'>{$book?->collection_type_label}</span></div>
                                        <div><strong>Tgl Pinjam:</strong> " . ($record->tanggal_pinjam?->format('d M Y') ?? '-') . ' &bull; <strong>Jatuh Tempo:</strong> ' . ($due?->format('d M Y') ?? '-') . "</div>
                                        <div><strong>Status Keterlambatan:</strong> {$overdueHtml}</div>
                                    </div>
                                ");
                            }),

                        Forms\Components\Checkbox::make('is_lost')
                            ->label('Buku Hilang / Dihilangkan oleh Peminjam')
                            ->helperText('Jika dicentang, denda kehilangan akan diberlakukan dan stok buku TIDAK akan bertambah ke rak.')
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, bool $state, Loan $record) {
                                $lateFine = $record->calculateLateFine(today());
                                if ($state) {
                                    $lostFine = $record->calculateLostFine();
                                    $set('fine_amount', $lostFine > 0 ? ($lostFine + $lateFine) : $lateFine);
                                } else {
                                    $set('fine_amount', $lateFine);
                                }
                            }),

                        Forms\Components\TextInput::make('fine_amount')
                            ->label('Total Nominal Denda (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(fn (Loan $record): int => $record->calculateLateFine(today()))
                            ->helperText(function (Forms\Get $get, Loan $record): string {
                                if ($get('is_lost')) {
                                    if (in_array($record->book?->collection_type, ['ta_skripsi', 'proposal_laporan_pkl'])) {
                                        return 'Denda kehilangan TA/Skripsi/PKL ditetapkan flat Rp 200.000.';
                                    }

                                    return 'Buku Umum: Peminjam wajib mengganti buku yang sama persis atau membayar nominal seharga buku.';
                                }
                                $days = $record->calculateOverdueWeekdays(today());
                                $rate = ($record->book?->collection_type === 'ta_skripsi') ? 300 : 200;

                                return "Denda keterlambatan: {$days} hari kerja x Rp {$rate}/hari.";
                            })
                            ->required(),

                        Forms\Components\Placeholder::make('info_kehilangan_umum')
                            ->visible(fn (Forms\Get $get, Loan $record): bool => (bool) $get('is_lost') && $record->book?->collection_type === 'umum')
                            ->content(new HtmlString("
                                <div class='p-2.5 rounded bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700 text-xs text-amber-900 dark:text-amber-200'>
                                    <strong>Ketentuan Kehilangan Buku Umum:</strong> Peminjam wajib mengganti buku yang sama atau seharga buku yang dihilangkan. Jika diganti dengan uang tunai, masukkan harga buku pada input Total Denda di atas.
                                </div>
                            ")),
                    ])
                    ->modalHeading('Pengembalian & Penyelesaian Peminjaman')
                    ->modalSubmitActionLabel('Selesaikan Pengembalian')
                    ->modalWidth('lg')
                    ->action(function (Loan $record, array $data): void {
                        $isLost = (bool) ($data['is_lost'] ?? false);
                        $fineAmount = (int) ($data['fine_amount'] ?? 0);

                        $record->update([
                            'status' => 'dikembalikan',
                            'tanggal_kembali' => now()->toDateString(),
                            'fine_amount' => $fineAmount,
                            'is_lost' => $isLost,
                        ]);

                        $statusMsg = $isLost
                            ? "Buku ditandai hilang. Denda sebesar Rp " . number_format($fineAmount, 0, ',', '.') . " telah dicatat (stok tidak ditambah)."
                            : "Buku berhasil dikembalikan ke rak (stok bertambah 1). Denda: Rp " . number_format($fineAmount, 0, ',', '.') . ".";

                        Notification::make()
                            ->title('Pengembalian Berhasil Diproses')
                            ->body($statusMsg)
                            ->success()
                            ->send();
                    }),

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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
