<?php

namespace App\Http\Controllers;

use App\Services\AnggotaExcelService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnggotaTemplateController extends Controller
{
    /**
     * Download the Anggota import template Excel spreadsheet.
     */
    public function download(AnggotaExcelService $service): StreamedResponse
    {
        return $service->downloadTemplate();
    }
}
