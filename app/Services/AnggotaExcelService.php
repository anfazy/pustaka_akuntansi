<?php

namespace App\Services;

use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnggotaExcelService
{
    /**
     * Generate the spreadsheet template for importing members.
     */
    public function generateTemplate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();

        // ---------------------------------------------------------
        // SHEET 1: Template Data Anggota
        // ---------------------------------------------------------
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Anggota');

        // Column Headers
        $headers = [
            'A1' => 'NIM / No. Anggota *',
            'B1' => 'Nama Lengkap *',
            'C1' => 'Program Studi *',
            'D1' => 'Kelas *',
            'E1' => 'Tahun Angkatan *',
            'F1' => 'No. HP *',
            'G1' => 'Email *',
            'H1' => 'Alamat *',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        // Header Styling
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF'], // Tailwind Blue-800
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '94A3B8'],
                ],
            ],
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Example Rows
        $examples = [
            [
                'nim' => '202401001',
                'nama' => 'Ahmad Pratama',
                'prodi' => 'Komputerisasi Akuntansi (S. Tr)',
                'kelas' => '4A',
                'angkatan' => '2024',
                'hp' => '081234567890',
                'email' => 'ahmad.pratama@example.com',
                'alamat' => 'Jl. Melati No. 12, Jakarta',
            ],
            [
                'nim' => '202401002',
                'nama' => 'Siti Rahmawati',
                'prodi' => 'Perbankan Syariah (S. Tr)',
                'kelas' => '4B',
                'angkatan' => '2024',
                'hp' => '082198765432',
                'email' => 'siti.rahmawati@example.com',
                'alamat' => 'Jl. Mawar No. 45, Bandung',
            ],
        ];

        $dataBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        $rowNum = 2;
        foreach ($examples as $item) {
            $sheet->setCellValueExplicit('A' . $rowNum, $item['nim'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $rowNum, $item['nama'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $rowNum, $item['prodi'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $rowNum, $item['kelas'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $rowNum, $item['angkatan'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $rowNum, $item['hp'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $rowNum, $item['email'], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H' . $rowNum, $item['alamat'], DataType::TYPE_STRING);

            $sheet->getStyle('A' . $rowNum . ':H' . $rowNum)->applyFromArray($dataBorder);
            $sheet->getRowDimension($rowNum)->setRowHeight(24);
            $rowNum++;
        }

        // Center align specific columns
        $sheet->getStyle('A2:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D2:E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F2:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Auto-size columns with sensible padding
        $columnWidths = [
            'A' => 22, // NIM
            'B' => 26, // Nama
            'C' => 32, // Prodi
            'D' => 14, // Kelas
            'E' => 18, // Angkatan
            'F' => 20, // No HP
            'G' => 30, // Email
            'H' => 35, // Alamat
        ];
        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Freeze top header row
        $sheet->freezePane('A2');

        // ---------------------------------------------------------
        // SHEET 2: Petunjuk Pengisian
        // ---------------------------------------------------------
        $guideSheet = $spreadsheet->createSheet();
        $guideSheet->setTitle('Petunjuk Pengisian');

        $guideSheet->setCellValue('A1', 'PETUNJUK PENGISIAN TEMPLATE EXCEL DATA ANGGOTA');
        $guideSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('1E40AF');
        $guideSheet->getRowDimension(1)->setRowHeight(28);

        $instructions = [
            ['No', 'Nama Kolom', 'Keterangan', 'Status', 'Contoh Nilai'],
            ['1', 'NIM / No. Anggota', 'Nomor Induk Mahasiswa atau No. Anggota (harus unik)', 'Wajib', '202401001'],
            ['2', 'Nama Lengkap', 'Nama lengkap anggota perpustakaan', 'Wajib', 'Ahmad Pratama'],
            ['3', 'Program Studi', 'Pilih salah satu program studi terdaftar', 'Wajib', 'Komputerisasi Akuntansi (S. Tr)'],
            ['4', 'Kelas', 'Kode atau nama kelas anggota', 'Wajib', '4A / 4B / 4C'],
            ['5', 'Tahun Angkatan', 'Tahun angkatan masuk (4 digit angka)', 'Wajib', '2024'],
            ['6', 'No. HP', 'Nomor handphone / WhatsApp aktif', 'Wajib', '081234567890'],
            ['7', 'Email', 'Alamat email aktif (harus unik)', 'Wajib', 'ahmad@example.com'],
            ['8', 'Alamat', 'Alamat domisili anggota', 'Wajib', 'Jl. Melati No. 12, Jakarta'],
        ];

        $gRow = 3;
        foreach ($instructions as $idx => $row) {
            $colLetter = 'A';
            foreach ($row as $val) {
                $cell = $colLetter . $gRow;
                $guideSheet->setCellValue($cell, $val);
                $colLetter++;
            }

            if ($idx === 0) {
                $guideSheet->getStyle('A' . $gRow . ':E' . $gRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '334155'], // Slate-700
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
            } else {
                $guideSheet->getStyle('A' . $gRow . ':E' . $gRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
                ]);
                $guideSheet->getStyle('A' . $gRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $guideSheet->getStyle('D' . $gRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            $guideSheet->getRowDimension($gRow)->setRowHeight(22);
            $gRow++;
        }

        $gRow += 2;
        $guideSheet->setCellValue('A' . $gRow, 'DAFTAR PROGRAM STUDI YANG TERSEDIA:');
        $guideSheet->getStyle('A' . $gRow)->getFont()->setBold(true)->getColor()->setRGB('1E40AF');
        $gRow++;

        $prodiList = [
            '1. Komputerisasi Akuntansi (S. Tr)',
            '2. Perbankan Syariah (S. Tr)',
            '3. Analisis Keuangan (S. Tr)',
            '4. Akuntansi Manajerial (S. Tr)',
            '5. Akuntansi (D3)',
            '6. Keuangan dan Perbankan (D3)',
        ];

        foreach ($prodiList as $prodi) {
            $guideSheet->setCellValue('A' . $gRow, $prodi);
            $gRow++;
        }

        $gRow++;
        $guideSheet->setCellValue('A' . $gRow, 'CATATAN PENTING:');
        $guideSheet->getStyle('A' . $gRow)->getFont()->setBold(true)->getColor()->setRGB('DC2626');
        $gRow++;
        $guideSheet->setCellValue('A' . $gRow, '- Hapus atau ganti baris contoh (baris 2 dan 3 di sheet "Template Anggota") sebelum melakukan impor.');
        $gRow++;
        $guideSheet->setCellValue('A' . $gRow, '- Pastikan NIM dan Email tidak ada yang sama dengan data yang sudah pernah diinput sebelumnya.');
        $gRow++;
        $guideSheet->setCellValue('A' . $gRow, '- Format No. HP dan NIM disarankan disimpan sebagai Teks agar angka nol (0) di depan tidak hilang.');

        $guideSheet->getColumnDimension('A')->setWidth(10);
        $guideSheet->getColumnDimension('B')->setWidth(24);
        $guideSheet->getColumnDimension('C')->setWidth(40);
        $guideSheet->getColumnDimension('D')->setWidth(14);
        $guideSheet->getColumnDimension('E')->setWidth(35);

        // Switch active sheet back to Sheet 1 so admin lands on the template directly
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Download the template as an HTTP StreamedResponse.
     */
    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = $this->generateTemplate();
        $fileName = 'template_import_anggota.xlsx';

        return response()->streamDownload(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
        ]);
    }

    /**
     * Import members from an uploaded Excel or CSV file.
     *
     * @return array{
     *     imported: int,
     *     skipped: int,
     *     duplicates: array<int, string>,
     *     errors: array<int, string>
     * }
     */
    public function import(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (empty($rows) || count($rows) <= 1) {
            return [
                'imported' => 0,
                'skipped' => 0,
                'duplicates' => [],
                'errors' => ['File Excel kosong atau hanya berisi baris judul.'],
            ];
        }

        // Determine column mapping based on the header row (row index 0)
        $headerRow = $rows[0];
        $columnMap = $this->detectColumns($headerRow);

        $importedCount = 0;
        $skippedCount = 0;
        $duplicates = [];
        $errors = [];

        // Track in-file duplicates to avoid SQL constraint violations
        $seenNims = [];
        $seenEmails = [];

        // Sample rows that should be ignored if left intact
        $sampleSignatures = [
            '202401001|ahmad pratama',
            '202401002|siti rahmawati',
        ];

        DB::beginTransaction();

        try {
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                // Skip completely empty row
                $nonEmptyValues = array_filter($row, fn ($val) => $val !== null && trim((string) $val) !== '');
                if (empty($nonEmptyValues)) {
                    continue;
                }

                $nim = trim((string) ($row[$columnMap['nim']] ?? ''));
                $nama = trim((string) ($row[$columnMap['nama']] ?? ''));
                $prodi = trim((string) ($row[$columnMap['prodi']] ?? ''));
                $kelas = trim((string) ($row[$columnMap['kelas']] ?? ''));
                $tahun = trim((string) ($row[$columnMap['tahun']] ?? ''));
                $noHp = trim((string) ($row[$columnMap['no_hp']] ?? ''));
                $email = trim((string) ($row[$columnMap['email']] ?? ''));
                $alamat = trim((string) ($row[$columnMap['alamat']] ?? ''));

                $rowNumber = $i + 1; // 1-indexed for human readability in Excel

                // Skip template sample rows
                $signature = strtolower($nim . '|' . $nama);
                if (in_array($signature, $sampleSignatures, true)) {
                    continue;
                }

                // Check required fields
                if ($nim === '' || $nama === '') {
                    $errors[] = "Baris {$rowNumber}: NIM dan Nama wajib diisi.";
                    $skippedCount++;
                    continue;
                }

                if ($email === '') {
                    $errors[] = "Baris {$rowNumber} (NIM {$nim}): Email wajib diisi.";
                    $skippedCount++;
                    continue;
                }

                // Check in-file duplicate NIM
                if (in_array(strtolower($nim), $seenNims, true)) {
                    $duplicates[] = "NIM {$nim} (duplikat di dalam file Excel)";
                    $skippedCount++;
                    continue;
                }

                // Check in-file duplicate Email
                if (in_array(strtolower($email), $seenEmails, true)) {
                    $duplicates[] = "Email {$email} (duplikat di dalam file Excel)";
                    $skippedCount++;
                    continue;
                }

                // Check database duplicate NIM
                if (Anggota::where('nim_no_anggota', $nim)->exists()) {
                    $duplicates[] = "NIM {$nim} (sudah terdaftar)";
                    $skippedCount++;
                    continue;
                }

                // Check database duplicate Email
                if (Anggota::where('email', $email)->exists()) {
                    $duplicates[] = "Email {$email} (sudah terdaftar)";
                    $skippedCount++;
                    continue;
                }

                // Parse tahun angkatan
                $tahunInt = is_numeric($tahun) ? (int) $tahun : (int) date('Y');

                // Save member record
                Anggota::create([
                    'nim_no_anggota' => $nim,
                    'nama' => $nama,
                    'program_studi' => $prodi ?: 'Umum',
                    'kelas' => $kelas ?: '-',
                    'tahun_angkatan' => $tahunInt,
                    'no_hp' => $noHp ?: null,
                    'email' => $email,
                    'alamat' => $alamat ?: null,
                ]);

                $seenNims[] = strtolower($nim);
                $seenEmails[] = strtolower($email);
                $importedCount++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return [
                'imported' => 0,
                'skipped' => $skippedCount,
                'duplicates' => $duplicates,
                'errors' => ['Terjadi kesalahan database: ' . $e->getMessage()],
            ];
        }

        return [
            'imported' => $importedCount,
            'skipped' => $skippedCount,
            'duplicates' => $duplicates,
            'errors' => $errors,
        ];
    }

    /**
     * Detect column index by analyzing the header row.
     * Falls back to standard sequential order if headers are unrecognized.
     *
     * @param  array<int, mixed>  $headerRow
     * @return array<string, int>
     */
    protected function detectColumns(array $headerRow): array
    {
        $map = [
            'nim' => 0,
            'nama' => 1,
            'prodi' => 2,
            'kelas' => 3,
            'tahun' => 4,
            'no_hp' => 5,
            'email' => 6,
            'alamat' => 7,
        ];

        foreach ($headerRow as $idx => $headerText) {
            if (! is_string($headerText)) {
                continue;
            }

            $clean = strtolower(trim($headerText));

            if (str_contains($clean, 'nim') || str_contains($clean, 'anggota')) {
                $map['nim'] = $idx;
            } elseif (str_contains($clean, 'nama')) {
                $map['nama'] = $idx;
            } elseif (str_contains($clean, 'prodi') || str_contains($clean, 'studi') || str_contains($clean, 'jurusan')) {
                $map['prodi'] = $idx;
            } elseif (str_contains($clean, 'kelas')) {
                $map['kelas'] = $idx;
            } elseif (str_contains($clean, 'angkatan') || str_contains($clean, 'tahun')) {
                $map['tahun'] = $idx;
            } elseif (str_contains($clean, 'hp') || str_contains($clean, 'telepon') || str_contains($clean, 'wa') || str_contains($clean, 'phone')) {
                $map['no_hp'] = $idx;
            } elseif (str_contains($clean, 'email') || str_contains($clean, 'surel') || str_contains($clean, 'mail')) {
                $map['email'] = $idx;
            } elseif (str_contains($clean, 'alamat') || str_contains($clean, 'domisili')) {
                $map['alamat'] = $idx;
            }
        }

        return $map;
    }
}
