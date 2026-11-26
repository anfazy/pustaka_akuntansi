<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnggotaResource\Pages;
use App\Models\Anggota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnggotaResource extends Resource
{
    protected static ?string $model = Anggota::class;

    protected static ?string $slug = 'anggota';

    // Mengatur nama menu dan label menjadi "Anggota"
    protected static ?string $navigationLabel = 'Anggota';

    protected static ?string $pluralModelLabel = 'Anggota';

    protected static ?string $modelLabel = 'Anggota';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 1;

    // Form Input / Edit Data Anggota (Semua kolom wajib diisi)
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nim_no_anggota')
                    ->label('NIM / No. Anggota')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\Select::make('program_studi')
                    ->label('Program Studi')
                    ->options([
                        'Komputerisasi Akuntansi (S. Tr)' => 'Komputerisasi Akuntansi (S. Tr)',
                        'Perbankan Syariah (S. Tr)' => 'Perbankan Syariah (S. Tr)',
                        'Analisis Keuangan (S. Tr)' => 'Analisis Keuangan (S. Tr)',
                        'Akuntansi Manajerial (S. Tr)' => 'Akuntansi Manajerial (S. Tr)',
                        'Akuntansi (D3)' => 'Akuntansi (D3)',
                        'Keuangan dan Perbankan (D3)' => 'Keuangan dan Perbankan (D3)',
                    ])
                    ->required()
                    ->searchable()
                // Agar bisa diketik/dicari jika pilihannya banyak
                    ->native(false),
                Forms\Components\TextInput::make('kelas')
                    ->label('Kelas')
                    ->required(),
                Forms\Components\TextInput::make('tahun_angkatan')
                    ->label('Tahun Angkatan')
                    ->numeric()
                    ->required(),
                // Diubah menjadi wajib diisi (required)
                Forms\Components\TextInput::make('no_hp')
                    ->label('No. HP')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                // Diubah menjadi wajib diisi (required)
                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('kota')
                    ->label('Kota / Kabupaten')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    // Tabel, Pencarian, & Filter Data
    public static function table(Table $table): Table
    {
        return $table
            // Menerjemahkan placeholder pencarian dan teks saat data kosong
            ->searchPlaceholder('Cari (NIM / Nama...)')
            ->emptyStateHeading('Belum ada data anggota')
            ->emptyStateDescription('Silakan tambah anggota baru atau impor dari Excel.')
            ->columns([
                Tables\Columns\TextColumn::make('nim_no_anggota')
                    ->label('NIM / No. Anggota')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program_studi')
                    ->label('Program Studi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_angkatan')
                    ->label('Angkatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Terverifikasi')
                    ->boolean(),
                Tables\Columns\TextColumn::make('kota')
                    ->label('Kota')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(35)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter berdasarkan Kelas, Prodi, dan Angkatan
                Tables\Filters\SelectFilter::make('kelas')
                    ->label('Filter Kelas')
                    ->options([
                        'KA-A' => 'KA-A',
                        'KA-B' => 'KA-B',
                        'KA-C' => 'KA-C',
                        'KA-D' => 'KA-D',
                        'KA-IUP' => 'KA-IUP',
                        'PS-A' => 'PS-A',
                        'PS-B' => 'PS-B',
                        'PS-C' => 'PS-C',
                        'PS-D' => 'PS-D',
                        'KU-A' => 'KU-A',
                        'KU-B' => 'KU-B',
                        'KU-C' => 'KU-C',
                        'KU-D' => 'KU-D',
                        'AM-A' => 'AM-A',
                        'AM-B' => 'AM-B',
                        'AM-C' => 'AM-C',
                        'AM-D' => 'AM-D',
                        'AK-A' => 'AK-A',
                        'AK-A' => 'AK-A',
                        'AK-B' => 'AK-B',
                        'AK-C' => 'AK-C',
                        'AK-D' => 'AK-D',
                        'AK-E' => 'AK-E',
                        'AK-F' => 'AK-F',
                        'KP-A' => 'KP-A',
                        'KP-B' => 'KP-A',
                        'KP-C' => 'KP-B',
                        'KP-D' => 'KP-D',
                    ]),
                Tables\Filters\SelectFilter::make('program_studi')
                    ->label('Filter Program Studi')
                    ->options([
                        'Komputerisasi Akuntansi (S. Tr)' => 'Komputerisasi Akuntansi (S. Tr)',
                        'Perbankan Syariah (S. Tr)' => 'Perbankan Syariah (S. Tr)',
                        'Analisis Keuangan (S. Tr)' => 'Analisis Keuangan (S. Tr)',
                        'Akuntansi Manajerial (S. Tr)' => 'Akuntansi Manajerial (S. Tr)',
                        'Akuntansi (D3)' => 'Akuntansi (D3)',
                        'Keuangan dan Perbankan (D3)' => 'Keuangan dan Perbankan (D3)',
                    ]),
                Tables\Filters\SelectFilter::make('tahun_angkatan')
                    ->label('Filter Tahun Angkatan')
                    ->options([
                        '2023' => '2023',
                        '2024' => '2024',
                        '2025' => '2025',
                        '2026' => '2026',
                    ]),
            ])
            ->actions([
                // Mengubah tombol Edit dan Hapus beserta peringatannya ke Bahasa Indonesia
                Tables\Actions\EditAction::make()
                    ->label('Ubah'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Anggota')
                    ->modalDescription('Apakah kamu yakin ingin menghapus data anggota ini?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->modalHeading('Hapus Anggota Terpilih')
                        ->modalDescription('Apakah kamu yakin ingin menghapus data anggota yang dipilih?')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
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
            'index' => Pages\ListAnggota::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }
}
