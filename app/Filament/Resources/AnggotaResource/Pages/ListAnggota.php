<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use App\Services\AnggotaExcelService;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ListAnggota extends ListRecords
{
    protected static string $resource = AnggotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Anggota'),

            Actions\Action::make('downloadTemplate')
                ->label('Unduh Template Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->tooltip('Unduh template Excel (.xlsx) untuk format pengisian data anggota')
                ->action(function (AnggotaExcelService $service) {
                    return $service->downloadTemplate();
                }),

            Actions\Action::make('importExcel')
                ->label('Impor Excel')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalHeading('Impor Data Anggota dari Excel')
                ->modalDescription('Unggah file Excel (.xlsx / .xls / .csv) berisi data anggota sesuai format template.')
                ->modalSubmitActionLabel('Mulai Impor')
                ->modalCancelActionLabel('Batal')
                ->form([
                    Forms\Components\Placeholder::make('template_info')
                        ->label('')
                        ->content(new HtmlString('
                            <div class="rounded-xl border border-blue-200 bg-blue-50/70 p-4 dark:border-blue-900/60 dark:bg-blue-950/40">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white shadow-sm">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                                            Belum Memiliki Format Template?
                                        </h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">
                                            Unduh template Excel di bawah ini, isi data anggota perpustakaan, lalu unggah kembali file tersebut.
                                        </p>
                                        <div class="pt-2">
                                            <a href="' . route('admin.anggota.download-template') . '"
                                               target="_blank"
                                               class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                Unduh Template Excel (.xlsx)
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ')),

                    FileUpload::make('file_excel')
                        ->label('Upload File Excel (.xlsx / .xls / .csv)')
                        ->disk('public')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                            'text/plain',
                            'application/csv',
                        ])
                        ->helperText('Mendukung format .xlsx, .xls, atau .csv (maksimal 5 MB).')
                        ->maxSize(5120)
                        ->required(),
                ])
                ->action(function (array $data, AnggotaExcelService $service): void {
                    $filePath = Storage::disk('public')->path($data['file_excel']);

                    try {
                        $result = $service->import($filePath);

                        // Hapus file sementara setelah diproses
                        if (Storage::disk('public')->exists($data['file_excel'])) {
                            Storage::disk('public')->delete($data['file_excel']);
                        }

                        $imported = $result['imported'];
                        $skipped = $result['skipped'];
                        $duplicates = $result['duplicates'];
                        $errors = $result['errors'];

                        if ($imported > 0 && empty($duplicates) && empty($errors)) {
                            Notification::make()
                                ->title('Berhasil Mengimpor!')
                                ->body("Sebanyak {$imported} data anggota berhasil dimasukkan ke database.")
                                ->success()
                                ->send();
                        } elseif ($imported > 0) {
                            $notes = [];
                            if (! empty($duplicates)) {
                                $notes[] = count($duplicates) . ' data duplikat dilewati (' . implode(', ', array_slice($duplicates, 0, 5)) . (count($duplicates) > 5 ? ', ...' : '') . ')';
                            }
                            if (! empty($errors)) {
                                $notes[] = count($errors) . ' baris tidak valid (' . implode(', ', array_slice($errors, 0, 3)) . (count($errors) > 3 ? ', ...' : '') . ')';
                            }

                            Notification::make()
                                ->title('Impor Selesai dengan Catatan')
                                ->body("{$imported} data berhasil diimpor, {$skipped} data dilewati.\n" . implode("\n", $notes))
                                ->warning()
                                ->send();
                        } else {
                            $errorMessage = 'Tidak ada data baru yang diimpor.';
                            if (! empty($duplicates)) {
                                $errorMessage .= ' Duplikat: ' . implode(', ', array_slice($duplicates, 0, 5));
                            }
                            if (! empty($errors)) {
                                $errorMessage .= ' ' . implode(', ', array_slice($errors, 0, 3));
                            }

                            Notification::make()
                                ->title('Impor Ditolak')
                                ->body($errorMessage)
                                ->danger()
                                ->send();
                        }
                    } catch (\Throwable $e) {
                        if (Storage::disk('public')->exists($data['file_excel'])) {
                            Storage::disk('public')->delete($data['file_excel']);
                        }

                        Notification::make()
                            ->title('Gagal Mengimpor File')
                            ->body('Terjadi kesalahan saat memproses file: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
