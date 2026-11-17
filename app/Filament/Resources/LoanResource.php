<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Peminjaman';

    protected static ?string $modelLabel = 'Peminjaman';

    protected static ?string $pluralModelLabel = 'Sirkulasi Peminjaman';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereIn('status', ['dipinjam', 'terlambat'])->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $today = now()->timezone('Asia/Jakarta')->toDateString();

        $hasOverdue = static::getModel()::where('status', 'terlambat')
            ->orWhere(function ($query) use ($today) {
                $query->where('status', 'dipinjam')
                    ->whereNotNull('tanggal_kembali')
                    ->where('tanggal_kembali', '<', $today);
            })
            ->exists();

        return $hasOverdue ? 'danger' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transaksi Peminjaman')
                    ->description('Catat transaksi peminjaman buku untuk civitas akademika Jurusan Akuntansi.')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Mahasiswa / Peminjam')
                            ->relationship('member', 'nama')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama} ({$record->nis_nip} • {$record->kelas_jabatan})")
                            ->searchable(['nama', 'nis_nip'])
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('book_id')
                            ->label('Buku yang Dipinjam')
                            ->relationship('book', 'judul')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->judul} (Stok: {$record->stok})")
                            ->searchable(['judul', 'isbn'])
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_pinjam')
                            ->label('Tanggal Pinjam')
                            ->default(today())
                            ->native(false)
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_kembali')
                            ->label('Tenggat / Jatuh Tempo')
                            ->helperText('SOP Ruang Baca: batas peminjaman standar 7 hari kalender.')
                            ->default(today()->addDays(7))
                            ->native(false)
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Peminjaman')
                            ->options([
                                'dipinjam' => 'Dipinjam',
                                'dikembalikan' => 'Dikembalikan',
                                'terlambat' => 'Terlambat',
                            ])
                            ->default('dipinjam')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('book.judul')
                    ->label('Judul Buku')
                    ->weight('bold')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('member.nama')
                    ->label('Peminjam')
                    ->description(fn (Loan $record): string => trim(($record->member?->nis_nip ?? '-') . ' • ' . ($record->member?->kelas_jabatan ?? '-')))
                    ->searchable(['nama', 'nis_nip'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pinjam')
                    ->label('Tgl Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_kembali')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->description(function (Loan $record): ?string {
                        if ($record->status === 'dikembalikan' || ! $record->tanggal_kembali) {
                            return null;
                        }
                        if ($record->tanggal_kembali->startOfDay()->lt(today())) {
                            return 'Lewat ' . $record->tanggal_kembali->diffForHumans();
                        }

                        return 'Sisa ' . $record->tanggal_kembali->diffForHumans();
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function (string $state, Loan $record): string {
                        $isOverdue = $state === 'terlambat' ||
                            ($state === 'dipinjam' && $record->tanggal_kembali && $record->tanggal_kembali->startOfDay()->lt(today()));

                        if ($isOverdue) {
                            return 'Terlambat / Overdue';
                        }

                        return match ($state) {
                            'dipinjam' => 'Dipinjam',
                            'dikembalikan' => 'Dikembalikan',
                            default => ucfirst($state),
                        };
                    })
                    ->color(function (string $state, Loan $record): string {
                        $isOverdue = $state === 'terlambat' ||
                            ($state === 'dipinjam' && $record->tanggal_kembali && $record->tanggal_kembali->startOfDay()->lt(today()));

                        if ($isOverdue) {
                            return 'danger';
                        }

                        return match ($state) {
                            'dikembalikan' => 'success',
                            'dipinjam' => 'warning',
                            default => 'gray',
                        };
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Peminjaman')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'terlambat' => 'Terlambat',
                    ]),
            ])
            ->actions([
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
