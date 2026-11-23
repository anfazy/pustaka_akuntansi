<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return $this->renderMockup('dashboard');
    }

    public function buku()
    {
        return $this->renderMockup('buku');
    }

    public function anggota()
    {
        return $this->renderMockup('anggota');
    }

    public function peminjaman()
    {
        return $this->renderMockup('sirkulasi');
    }

    public function kategori()
    {
        return $this->renderMockup('buku', ['openCategoryModal' => true]);
    }

    protected function renderMockup(string $activeTab = 'dashboard', array $extra = [])
    {
        // Pastikan hanya user terotentikasi yang bisa akses admin
        if (! Auth::check()) {
            return redirect()->to('/admin/login');
        }

        $user = Auth::user();

        // 1. Ambil data kategori
        $categories = Category::pluck('nama')->toArray();
        if (empty($categories)) {
            $categories = ['Fiksi', 'Akuntansi', 'Perpajakan', 'TA/Skripsi', 'Laporan PKL'];
        }

        // 2. Ambil data anggota dari tabel anggotas
        $anggotaDb = Anggota::latest()->get()->map(function ($a) {
            return [
                'id' => $a->id,
                'nim' => $a->nim_no_anggota,
                'nama' => $a->nama,
                'prodi' => $a->program_studi ?? 'Komputerisasi Akuntansi (S. Tr)',
                'kelas' => $a->kelas ?? '-',
                'angkatan' => $a->angkatan ?? (int) ('20'.substr($a->nim_no_anggota ?? '24', 5, 2)),
                'hp' => $a->no_hp ?? '-',
                'email' => $a->email ?? '-',
                'alamat' => $a->alamat ?? '-',
            ];
        })->toArray();

        // 3. Ambil data buku dari tabel books
        $booksDb = Book::with('category')
            ->withCount(['loans as dipinjam_count' => function ($q) {
                $q->whereIn('status', ['dipinjam', 'terlambat']);
            }])
            ->latest()
            ->get()
            ->map(function ($b) {
                $catName = $b->category?->nama ?? 'Akuntansi';
                $dipinjamCount = (int) ($b->dipinjam_count ?? 0);

                $coverUrl = '';
                if ($b->cover) {
                    if (str_starts_with($b->cover, 'http')) {
                        $coverUrl = $b->cover;
                    } else {
                        $coverUrl = asset(ltrim($b->cover, '/'));
                    }
                }

                $catSlug = strtolower($b->category?->slug ?? '');
                $catNameLower = strtolower($catName);
                $isTA = ($b->collection_type === 'ta_skripsi')
                    || ($catSlug === 'ta-skripsi')
                    || str_contains($catNameLower, 'skripsi')
                    || str_contains($catNameLower, 'tugas akhir')
                    || str_starts_with($catNameLower, 'ta/')
                    || str_starts_with($catNameLower, 'ta-')
                    || $catNameLower === 'ta';
                $dendaPerHari = $isTA ? 300 : 200;

                return [
                    'id' => $b->id,
                    'cover' => $coverUrl,
                    'kode' => $b->book_code ?? ('AKT-'.str_pad($b->id, 3, '0', STR_PAD_LEFT)),
                    'judul' => $b->judul,
                    'kategori' => $catName,
                    'penulis' => $b->penulis,
                    'tahun' => (int) ($b->tahun_terbit ?? 2024),
                    'stok' => (int) $b->stok,
                    'dipinjam' => $dipinjamCount,
                    'isTA' => $isTA,
                    'dendaPerHari' => $dendaPerHari,
                    'isbn' => $b->isbn ?? '-',
                    'penerbit' => $b->penerbit ?? '-',
                    'rak' => $b->rak ?? 'Rak Utama',
                    'sinopsis' => $b->sinopsis ?? '',
                ];
            })->toArray();

        // 4. Ambil data sirkulasi / peminjaman dari tabel loans
        $sirkulasiDb = Loan::with(['book', 'anggota', 'member'])
            ->where('status', '!=', 'dibatalkan')
            ->latest('id')
            ->get()
            ->map(function ($l) {
                $anggota = $l->anggota ?? $l->member;
                $nama = $anggota?->nama ?? $l->member_nim ?? 'Mahasiswa';
                $nim = $l->member_nim ?? $anggota?->nim_no_anggota ?? $anggota?->nis_nip ?? '-';
                $kelas = $anggota?->kelas ?? $anggota?->kelas_jabatan ?? '-';
                $hp = $anggota?->no_hp ?? '-';

                $tglPinjam = $l->tanggal_pinjam ? $l->tanggal_pinjam->translatedFormat('d M Y') : '-';
                $dueDate = $l->jatuh_tempo ?? $l->tanggal_kembali ?? ($l->tanggal_pinjam ? $l->tanggal_pinjam->copy()->addDays(7) : null);
                $jatuhTempo = $dueDate ? $dueDate->translatedFormat('d M Y') : '-';

                $isOverdue = $l->isOverdue();
                $hariTelat = 0;
                $catatanTempo = 'Masa peminjaman aktif';
                if ($isOverdue && $dueDate) {
                    $hariTelat = $l->calculateOverdueWeekdays(today());
                    if ($hariTelat === 0) {
                        $hariTelat = max(1, $l->calculateOverdueDays(today()));
                    }
                    $catatanTempo = 'Lewat '.$hariTelat.' hari';
                } elseif ($dueDate && $dueDate->isToday()) {
                    $catatanTempo = 'Jatuh tempo hari ini';
                }

                $isBooking = $l->status === 'booked';
                if ($isBooking) {
                    $statusText = 'Booking';
                    $tglPinjam = $l->created_at ? $l->created_at->translatedFormat('d M Y') : today()->translatedFormat('d M Y');
                    $jatuhTempo = $l->created_at ? $l->created_at->addHours(24)->translatedFormat('d M Y, H:i').' WIB' : '1×24 Jam';
                    $catatanTempo = 'Batas ambil di perpus';
                    $hariTelat = 0;
                } else {
                    $statusText = match ($l->status) {
                        'dikembalikan' => 'Dikembalikan',
                        'dibatalkan' => 'Dibatalkan',
                        default => ($isOverdue ? 'Terlambat' : 'Sedang Dipinjam'),
                    };
                }

                $rate = $l->book?->getDendaPerHari() ?? 200;
                $calculatedFine = $l->calculateLateFine(today());
                if ($isOverdue && $statusText === 'Terlambat' && $hariTelat > 0) {
                    $calculatedFine = $hariTelat * $rate;
                }
                $dendaFinal = ($l->fine_amount && $l->fine_amount > 0 && $l->status === 'dikembalikan') ? (int) $l->fine_amount : $calculatedFine;

                return [
                    'id' => $l->id,
                    'kodeBooking' => $l->booking_code ?? ('AKT-'.strtoupper(substr(md5($l->id), 0, 4))),
                    'rawStatus' => $l->status,
                    'isBooking' => $isBooking,
                    'nama' => $nama,
                    'nim' => $nim,
                    'kelas' => $kelas,
                    'hp' => $hp,
                    'jaminan' => $l->jaminan ?? 'KTM',
                    'judulBuku' => $l->book?->judul ?? 'Buku Perpustakaan',
                    'kategoriBuku' => $l->book?->category?->nama ?? 'Akuntansi',
                    'rak' => $l->book?->rak ?? 'Rak Utama',
                    'tglPinjam' => $tglPinjam,
                    'jatuhTempo' => $jatuhTempo,
                    'catatanTempo' => $catatanTempo,
                    'hariTelat' => $hariTelat,
                    'status' => $statusText,
                    'waType' => $isOverdue ? 'WA Terlambat' : 'Ingatkan WA',
                    'denda' => $dendaFinal,
                    'dendaPerHari' => $l->book?->getDendaPerHari() ?? 200,
                ];
            })->toArray();

        // 5. Booking web catalog queue
        $webCatalogBookings = Loan::with(['book', 'anggota', 'member'])
            ->where('status', 'booked')
            ->latest()
            ->get()
            ->map(function ($l) use ($anggotaDb, $booksDb) {
                $anggota = $l->anggota ?? $l->member;
                $memberNim = $anggota?->nim_no_anggota ?? $l->member_nim;
                $memberIdx = 0;
                foreach ($anggotaDb as $idx => $a) {
                    if ($a['nim'] === $memberNim) {
                        $memberIdx = $idx;
                        break;
                    }
                }
                $bookIdx = 0;
                foreach ($booksDb as $idx => $b) {
                    if ($b['id'] == $l->book_id) {
                        $bookIdx = $idx;
                        break;
                    }
                }

                return [
                    'kode' => $l->booking_code ?? ('AKT-'.strtoupper(substr(md5($l->id), 0, 4))),
                    'memberIdx' => $memberIdx,
                    'bookIdx' => $bookIdx,
                    'jaminan' => $l->jaminan ?? 'KTM',
                    'waktuPesan' => $l->created_at ? $l->created_at->diffForHumans() : 'Hari ini',
                ];
            })->toArray();

        if (empty($webCatalogBookings)) {
            $webCatalogBookings = [
                ['kode' => 'AKT-8X2P', 'memberIdx' => 0, 'bookIdx' => 0, 'jaminan' => 'KTM', 'waktuPesan' => 'Hari ini, 09:15 WIB'],
            ];
        }

        // Tanggal dan Sapaan
        $now = Carbon::now(config('app.timezone', 'Asia/Jakarta'))->locale('id');
        $hour = (int) $now->format('H');
        $greeting = match (true) {
            $hour >= 5 && $hour < 11 => 'Selamat Pagi',
            $hour >= 11 && $hour < 15 => 'Selamat Siang',
            $hour >= 15 && $hour < 18 => 'Selamat Sore',
            default => 'Selamat Malam',
        };
        $formattedDate = $now->isoFormat('dddd, D MMMM Y');

        return view('admin.mockup', array_merge([
            'activeTab' => $activeTab,
            'user' => $user,
            'greeting' => $greeting,
            'formattedDate' => $formattedDate,
            'categories' => $categories,
            'anggotaDb' => $anggotaDb,
            'booksDb' => $booksDb,
            'sirkulasiDb' => $sirkulasiDb,
            'webCatalogBookings' => $webCatalogBookings,
        ], $extra));
    }

    // =========================================================================
    // API PERSISTENSI KE DATABASE
    // =========================================================================

    public function saveAnggota(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string',
            'nama' => 'required|string',
            'prodi' => 'required|string',
            'kelas' => 'nullable|string',
            'angkatan' => 'nullable|numeric',
            'hp' => 'nullable|string',
            'email' => 'nullable|email',
            'alamat' => 'nullable|string',
        ]);

        $anggota = Anggota::updateOrCreate(
            ['nim_no_anggota' => $validated['nim']],
            [
                'nama' => $validated['nama'],
                'program_studi' => $validated['prodi'],
                'kelas' => $validated['kelas'] ?? '-',
                'angkatan' => $validated['angkatan'] ?? 2024,
                'no_hp' => $validated['hp'] ?? null,
                'email' => $validated['email'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]
        );

        return response()->json(['success' => true, 'id' => $anggota->id, 'data' => $anggota]);
    }

    public function deleteAnggota($id)
    {
        Anggota::where('id', $id)->delete();

        return response()->json(['success' => true]);
    }

    public function saveBook(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string',
            'kategori' => 'required|string',
            'penulis' => 'required|string',
            'stok' => 'required|numeric|min:0',
            'tahun' => 'nullable|numeric',
            'kode' => 'nullable|string',
            'isbn' => 'nullable|string',
            'penerbit' => 'nullable|string',
            'rak' => 'nullable|string',
            'sinopsis' => 'nullable|string',
            'cover_file' => 'nullable|image|max:3072',
        ]);

        $category = Category::firstOrCreate(
            ['nama' => $validated['kategori']],
            ['slug' => Str::slug($validated['kategori'])]
        );

        $coverPath = null;
        if ($request->hasFile('cover_file')) {
            $file = $request->file('cover_file');
            $fileName = 'cover_'.time().'_'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('covers'), $fileName);
            $coverPath = '/covers/'.$fileName;
        }

        $catLower = strtolower($validated['kategori']);
        $isTA = str_contains($catLower, 'skripsi')
            || str_contains($catLower, 'tugas akhir')
            || str_starts_with($catLower, 'ta/')
            || str_starts_with($catLower, 'ta-')
            || $catLower === 'ta';
        $collectionType = $isTA ? 'ta_skripsi' : 'umum';

        if ($request->id) {
            $book = Book::find($request->id);
            if ($book) {
                $updateData = [
                    'category_id' => $category->id,
                    'collection_type' => $collectionType,
                    'judul' => $validated['judul'],
                    'penulis' => $validated['penulis'],
                    'book_code' => $validated['kode'] ?? $book->book_code,
                    'tahun_terbit' => $validated['tahun'] ?? $book->tahun_terbit,
                    'stok' => $validated['stok'],
                    'isbn' => $validated['isbn'] ?? $book->isbn,
                    'penerbit' => $validated['penerbit'] ?? $book->penerbit,
                    'rak' => $validated['rak'] ?? $book->rak,
                    'sinopsis' => $validated['sinopsis'] ?? $book->sinopsis,
                ];
                if ($coverPath) {
                    $updateData['cover'] = $coverPath;
                }
                $book->update($updateData);

                $coverUrl = $book->cover ? (str_starts_with($book->cover, 'http') ? $book->cover : asset(ltrim($book->cover, '/'))) : '';

                return response()->json([
                    'success' => true,
                    'id' => $book->id,
                    'data' => $book,
                    'cover_url' => $coverUrl,
                ]);
            }
        }

        $book = Book::updateOrCreate(
            ['judul' => $validated['judul'], 'penulis' => $validated['penulis']],
            [
                'category_id' => $category->id,
                'collection_type' => $collectionType,
                'book_code' => $validated['kode'] ?? ('AKT-'.strtoupper(Str::random(4))),
                'tahun_terbit' => $validated['tahun'] ?? 2024,
                'stok' => $validated['stok'],
                'isbn' => $validated['isbn'] ?? null,
                'penerbit' => $validated['penerbit'] ?? 'Pustaka Akuntansi',
                'rak' => $validated['rak'] ?? 'Rak Utama',
                'sinopsis' => $validated['sinopsis'] ?? null,
                'cover' => $coverPath ?? null,
            ]
        );

        $coverUrl = $book->cover ? (str_starts_with($book->cover, 'http') ? $book->cover : asset(ltrim($book->cover, '/'))) : '';

        return response()->json([
            'success' => true,
            'id' => $book->id,
            'data' => $book,
            'cover_url' => $coverUrl,
        ]);
    }

    public function deleteBook($id)
    {
        Book::where('id', $id)->delete();

        return response()->json(['success' => true]);
    }

    public function saveLoan(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string',
            'buku_id' => 'required',
            'jaminan' => 'nullable|string',
        ]);

        $anggota = Anggota::where('nim_no_anggota', $validated['nim'])->first();
        $book = is_numeric($validated['buku_id'])
            ? Book::find($validated['buku_id'])
            : Book::where('judul', $validated['buku_id'])->first();

        if (! $book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan'], 404);
        }

        $loan = Loan::create([
            'anggota_id' => $anggota?->id,
            'member_nim' => $validated['nim'],
            'book_id' => $book->id,
            'booking_code' => 'AKT-'.strtoupper(Str::random(4)),
            'tanggal_pinjam' => today(),
            'tanggal_kembali' => today()->addDays(7),
            'status' => 'dipinjam',
            'card_type' => $validated['jaminan'] ?? 'KTM',
        ]);

        return response()->json(['success' => true, 'id' => $loan->id, 'data' => $loan]);
    }

    public function returnLoan($id)
    {
        $loan = Loan::find($id);
        if ($loan) {
            $loan->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => today(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function confirmHandoverLoan(Request $request, $id)
    {
        $loan = Loan::with(['book', 'anggota'])->findOrFail($id);

        if ($loan->status !== 'booked') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi ini bukan berstatus booking aktif.',
            ], 422);
        }

        $jaminan = $request->input('jaminan', 'KTM');

        $loan->status = 'dipinjam';
        $loan->tanggal_pinjam = today();
        $loan->jaminan = $jaminan;
        $loan->save();

        return response()->json([
            'success' => true,
            'message' => "Buku \"{$loan->book?->judul}\" berhasil diserahkan kepada ".($loan->anggota?->nama ?? 'mahasiswa').'. Peminjaman kini aktif.',
            'loan' => [
                'id' => $loan->id,
                'status' => 'Sedang Dipinjam',
                'tglPinjam' => $loan->tanggal_pinjam ? $loan->tanggal_pinjam->translatedFormat('d M Y') : today()->translatedFormat('d M Y'),
                'jatuhTempo' => $loan->jatuh_tempo ? $loan->jatuh_tempo->translatedFormat('d M Y') : '-',
                'catatanTempo' => 'Masa peminjaman aktif',
                'jaminan' => $loan->jaminan,
            ],
        ]);
    }

    public function cancelBookingAdmin($id)
    {
        $loan = Loan::findOrFail($id);
        if ($loan->status !== 'booked') {
            return response()->json(['success' => false, 'message' => 'Hanya booking aktif yang dapat dibatalkan.'], 422);
        }

        $loan->update(['status' => 'dibatalkan']);

        return response()->json(['success' => true, 'message' => 'Booking berhasil dibatalkan.']);
    }

    public function lookupBooking(Request $request)
    {
        $code = trim((string) $request->input('code', ''));
        if ($code === '') {
            return response()->json(['success' => false, 'message' => 'Masukkan kode booking.'], 422);
        }

        $loan = Loan::with(['book.category', 'anggota'])
            ->where('booking_code', 'like', "%{$code}%")
            ->latest()
            ->first();

        if (! $loan) {
            return response()->json(['success' => false, 'message' => "Kode booking \"{$code}\" tidak ditemukan."], 404);
        }

        if ($loan->status === 'dibatalkan') {
            return response()->json([
                'success' => false,
                'message' => "Kode booking \"{$loan->booking_code}\" telah DIBATALKAN oleh peminjam.",
            ], 422);
        }

        if ($loan->status === 'dikembalikan') {
            return response()->json([
                'success' => false,
                'message' => "Peminjaman dengan kode \"{$loan->booking_code}\" sudah selesai dan buku telah dikembalikan.",
            ], 422);
        }

        $anggota = $loan->anggota;
        $book = $loan->book;

        return response()->json([
            'success' => true,
            'loan' => [
                'id' => $loan->id,
                'kodeBooking' => $loan->booking_code,
                'status' => $loan->status === 'booked' ? 'Booking' : ($loan->status === 'dipinjam' ? 'Sedang Dipinjam' : ucfirst($loan->status)),
                'isBooked' => $loan->status === 'booked',
                'nama' => $anggota?->nama ?? $loan->member_nim ?? 'Mahasiswa',
                'nim' => $anggota?->nim_no_anggota ?? $loan->member_nim ?? '-',
                'kelas' => $anggota?->kelas ?? '-',
                'hp' => $anggota?->no_hp ?? '-',
                'jaminan' => $loan->jaminan ?? 'KTM',
                'judulBuku' => $book?->judul ?? '-',
                'kategoriBuku' => $book?->category?->nama ?? 'Akuntansi',
                'rak' => $book?->rak ?? 'Rak Utama',
                'stok' => $book?->stok ?? 0,
                'batasAmbil' => $loan->created_at ? $loan->created_at->addHours(24)->translatedFormat('d M Y, H:i').' WIB' : '1×24 Jam',
            ],
        ]);
    }
}
