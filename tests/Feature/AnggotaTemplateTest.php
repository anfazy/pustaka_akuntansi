<?php

namespace Tests\Feature;

use App\Services\AnggotaExcelService;
use Tests\TestCase;

class AnggotaTemplateTest extends TestCase
{
    public function test_template_download_route_is_successful(): void
    {
        $response = $this->get(route('admin.anggota.download-template'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_service_generates_template_with_two_sheets_and_headers(): void
    {
        $service = new AnggotaExcelService();
        $spreadsheet = $service->generateTemplate();

        $this->assertEquals(2, $spreadsheet->getSheetCount());

        $sheet1 = $spreadsheet->getSheet(0);
        $this->assertEquals('Template Anggota', $sheet1->getTitle());
        $this->assertEquals('NIM / No. Anggota *', $sheet1->getCell('A1')->getValue());
        $this->assertEquals('Nama Lengkap *', $sheet1->getCell('B1')->getValue());
        $this->assertEquals('Program Studi *', $sheet1->getCell('C1')->getValue());
        $this->assertEquals('Kelas *', $sheet1->getCell('D1')->getValue());
        $this->assertEquals('Tahun Angkatan *', $sheet1->getCell('E1')->getValue());
        $this->assertEquals('No. HP *', $sheet1->getCell('F1')->getValue());
        $this->assertEquals('Email *', $sheet1->getCell('G1')->getValue());
        $this->assertEquals('Alamat *', $sheet1->getCell('H1')->getValue());

        $sheet2 = $spreadsheet->getSheet(1);
        $this->assertEquals('Petunjuk Pengisian', $sheet2->getTitle());
    }
}
