<?php

namespace App\Http\Controllers;

use App\Mail\VerifyAnggotaEmail;
use App\Models\Anggota;
use App\Models\Book;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserPortalController extends Controller
{
    /**
     * Get currently logged-in student (from session).
     */
    protected function getStudent(): ?Anggota
    {
        $id = session('student_id');
        if (! $id) {
            return null;
        }

        return Anggota::find($id);
    }

    /**
     * Sinkronisasi status peminjaman siswa agar selalu selaras dengan aturan Panel Admin:
     * - Booking > 24 jam otomatis dibatalkan.
     * - Status 'dipinjam' <-> 'terlambat' diselaraskan dengan jatuh tempo hari kerja.
     */
    protected function syncStudentLoansStatus(Anggota $student): void
    {
        Loan::where('status', 'booked')
            ->where(function ($q) use ($student) {
                $q->where('member_nim', $student->nim_no_anggota)
                    ->orWhere('anggota_id', $student->id);
            })
            ->where('created_at', '<', now()->subHours(24))
            ->update(['status' => 'dibatalkan']);

        Loan::with('book')
            ->where(function ($q) use ($student) {
                $q->where('member_nim', $student->nim_no_anggota)
                    ->orWhere('anggota_id', $student->id);
            })
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->where('is_lost', false)
            ->get()
            ->each(function (Loan $loan): void {
                if ($loan->isOverdue() && $loan->status !== 'terlambat') {
                    $loan->updateQuietly(['status' => 'terlambat']);
                } elseif (! $loan->isOverdue() && $loan->status === 'terlambat') {
                    $loan->updateQuietly(['status' => 'dipinjam']);
                }
            });
    }

    /**
     * Beranda (Home page).
     * Accessible by both guests and authenticated students.
     */
    public function beranda(Request $request): View
    {
        $student = $this->getStudent();

        // Books data for Beranda showcase shelves
        $popularBooks = Book::with('category')
            ->orderBy('stok', 'asc') // more borrowed = less stock
            ->latest()
            ->take(8)
            ->get();

        $popularIds = $popularBooks->take(4)->pluck('id')->toArray();

        $newestBooks = Book::with('category')
            ->whereNotIn('id', $popularIds)
            ->latest()
            ->take(8)
            ->get();

        $spotlightBooks = Book::with('category')
            ->orderByDesc('stok')
            ->latest()
            ->take(5)
            ->get();

        $accountingBooks = Book::with('category')
            ->whereHas('category', function ($q): void {
                $q->whereIn('slug', ['akuntansi', 'perpajakan'])
                    ->orWhere('nama', 'like', '%Akuntansi%')
                    ->orWhere('nama', 'like', '%Perpajakan%');
            })
            ->latest()
            ->take(8)
            ->get();

        $thesisBooks = Book::with('category')
            ->where(function ($q): void {
                $q->whereIn('collection_type', ['ta_skripsi', 'proposal_laporan_pkl'])
                    ->orWhereHas('category', function ($catQ): void {
                        $catQ->whereIn('slug', ['ta-skripsi', 'laporan-pkl']);
                    });
            })
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('books')
            ->orderByDesc('books_count')
            ->orderBy('nama')
            ->take(6)
            ->get();

        $bookStats = Book::selectRaw('COUNT(*) as total_titles, COALESCE(SUM(stok), 0) as available_copies')->first();

        $librarySummary = [
            'total_titles' => (int) ($bookStats?->total_titles ?? 0),
            'available_copies' => (int) ($bookStats?->available_copies ?? 0),
            'active_loans' => (int) Loan::whereIn('status', ['dipinjam', 'terlambat'])->count(),
        ];

        // Student statistics if logged in
        $stats = [
            'borrowed_count' => 0,
            'booked_count' => 0,
            'latest_booking_code' => null,
            'active_count' => 0,
            'max_quota' => 2,
            'fine_amount' => 0,
            'next_due_loan' => null,
            'is_overdue' => false,
            'days_diff' => 0,
        ];

        if ($student) {
            $this->syncStudentLoansStatus($student);

            $studentLoans = Loan::with('book')
                ->where(function ($q) use ($student) {
                    $q->where('member_nim', $student->nim_no_anggota)
                        ->orWhere('anggota_id', $student->id);
                })
                ->get();

            $borrowed = $studentLoans->whereIn('status', ['dipinjam', 'terlambat']);
            $booked = $studentLoans->where('status', 'booked');

            $stats['borrowed_count'] = $borrowed->count();
            $stats['booked_count'] = $booked->count();
            $stats['latest_booking_code'] = $booked->first()?->booking_code;
            $stats['active_count'] = $stats['borrowed_count'] + $stats['booked_count'];

            // Calculate active fines
            $fineSum = 0;
            foreach ($borrowed as $loan) {
                $fineSum += $loan->calculateLateFine(today());
            }
            $stats['fine_amount'] = $fineSum;

            // Find nearest due or overdue
            $nearestLoan = $borrowed->sortBy(function (Loan $loan) {
                return $loan->jatuh_tempo ? $loan->jatuh_tempo->timestamp : PHP_INT_MAX;
            })->first();

            if ($nearestLoan && $nearestLoan->jatuh_tempo) {
                $today = today();
                $due = $nearestLoan->jatuh_tempo->copy()->startOfDay();
                $stats['next_due_loan'] = $nearestLoan;

                if ($today->gt($due)) {
                    $stats['is_overdue'] = true;
                    $stats['days_diff'] = abs((int) $due->diffInDays($today));
                } else {
                    $stats['is_overdue'] = false;
                    $stats['days_diff'] = abs((int) $today->diffInDays($due));
                }
            }
        }

        return view('front.beranda', [
            'student' => $student,
            'stats' => $stats,
            'popularBooks' => $popularBooks,
            'newestBooks' => $newestBooks,
            'spotlightBooks' => $spotlightBooks,
            'accountingBooks' => $accountingBooks,
            'thesisBooks' => $thesisBooks,
            'categories' => $categories,
            'librarySummary' => $librarySummary,
        ]);
    }

    /**
     * Katalog Buku (Browse / Search).
     */
    public function katalog(Request $request): View
    {
        $student = $this->getStudent();

        $query = Book::with('category');

        $search = trim((string) $request->get('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('book_code', 'like', "%{$search}%");
            });
        }

        $kategoriSlug = $request->get('kategori');
        if ($kategoriSlug && $kategoriSlug !== 'semua') {
            $query->whereHas('category', function ($q) use ($kategoriSlug) {
                $q->where('slug', $kategoriSlug)->orWhere('nama', $kategoriSlug);
            });
        }

        $tipe = $request->get('tipe');
        if ($tipe && in_array($tipe, ['umum', 'ta_skripsi', 'proposal_laporan_pkl'])) {
            $query->where('collection_type', $tipe);
        }

        $tersedia = $request->get('tersedia');
        if ($tersedia === '1') {
            $query->where('stok', '>', 0);
        }

        $books = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::withCount('books')->orderBy('nama')->get();

        return view('front.katalog', [
            'student' => $student,
            'books' => $books,
            'categories' => $categories,
            'currentSearch' => $search,
            'currentKategori' => $kategoriSlug ?? 'semua',
            'currentTipe' => $tipe ?? 'semua',
        ]);
    }

    /**
     * Detail Buku.
     */
    public function bukuDetail(int $id): View
    {
        $student = $this->getStudent();
        $book = Book::with('category')->findOrFail($id);

        // Hanguskan booking buku ini yang sudah melewati batas 1x24 jam agar stok booking akurat
        Loan::where('book_id', $book->id)
            ->where('status', 'booked')
            ->where('created_at', '<', now()->subHours(24))
            ->update(['status' => 'dibatalkan']);

        $activeBookingsCount = Loan::where('book_id', $book->id)
            ->where('status', 'booked')
            ->count();

        $relatedBooks = Book::with('category')
            ->where('id', '!=', $book->id)
            ->where('category_id', $book->category_id)
            ->take(4)
            ->get();

        if ($relatedBooks->isEmpty()) {
            $relatedBooks = Book::with('category')
                ->where('id', '!=', $book->id)
                ->take(4)
                ->get();
        }

        return view('front.buku', [
            'student' => $student,
            'book' => $book,
            'activeBookingsCount' => $activeBookingsCount,
            'relatedBooks' => $relatedBooks,
        ]);
    }

    /**
     * Proses Pemesanan / Booking Buku.
     */
    public function booking(Request $request, int $id): RedirectResponse
    {
        $student = $this->getStudent();
        if (! $student) {
            return redirect()->route('masuk')->with('error', 'Silakan masuk dengan NIM Anda terlebih dahulu untuk meminjam buku.');
        }

        if ($student->status !== 'active') {
            return back()->with('error', 'Status keanggotaan Anda belum aktif untuk melakukan peminjaman.');
        }

        $this->syncStudentLoansStatus($student);

        $result = DB::transaction(function () use ($student, $id): array {
            $book = Book::whereKey($id)->lockForUpdate()->firstOrFail();

            // Hanguskan booking > 24 jam untuk buku ini agar perhitungan kuota booking akurat
            Loan::where('book_id', $book->id)
                ->where('status', 'booked')
                ->where('created_at', '<', now()->subHours(24))
                ->update(['status' => 'dibatalkan']);

            if ($book->stok <= 0) {
                return ['error' => 'Maaf, stok eksemplar buku ini sedang habis.'];
            }

            $activeBookingsForBook = Loan::where('book_id', $book->id)
                ->where('status', 'booked')
                ->count();

            if (($book->stok - $activeBookingsForBook) <= 0) {
                return ['error' => 'Maaf, seluruh eksemplar buku ini sedang di-booking oleh mahasiswa lain.'];
            }

            // Check active loans count (max 2 books)
            $activeLoansCount = Loan::where(function ($q) use ($student) {
                $q->where('member_nim', $student->nim_no_anggota)
                    ->orWhere('anggota_id', $student->id);
            })
                ->whereIn('status', ['booked', 'dipinjam', 'terlambat'])
                ->count();

            if ($activeLoansCount >= 2) {
                return ['error' => 'Anda telah mencapai kuota maksimal peminjaman (2 buku). Selesaikan pinjaman sebelumnya terlebih dahulu.'];
            }

            // Check if already booked / borrowed this exact book
            $alreadyHasBook = Loan::where(function ($q) use ($student) {
                $q->where('member_nim', $student->nim_no_anggota)
                    ->orWhere('anggota_id', $student->id);
            })
                ->where('book_id', $book->id)
                ->whereIn('status', ['booked', 'dipinjam', 'terlambat'])
                ->exists();

            if ($alreadyHasBook) {
                return ['error' => 'Anda sudah memiliki transaksi aktif (booking atau pinjam) untuk buku ini.'];
            }

            $loan = Loan::create([
                'book_id' => $book->id,
                'anggota_id' => $student->id,
                'member_nim' => $student->nim_no_anggota,
                'card_type' => 'KTM',
                'status' => 'booked',
                'tanggal_pinjam' => today()->toDateString(),
            ]);

            return ['loan' => $loan];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        /** @var Loan $loan */
        $loan = $result['loan'];

        return redirect()->route('pinjaman', ['tab' => 'siap', 'success_booking' => $loan->booking_code])
            ->with('success', "Booking berhasil dibuat dengan kode {$loan->booking_code}!");
    }

    /**
     * Halaman Pinjaman Saya.
     */
    public function pinjaman(Request $request): View|RedirectResponse
    {
        $student = $this->getStudent();
        if (! $student) {
            return redirect()->route('masuk')->with('warning', 'Silakan masuk untuk melihat pinjaman Anda.');
        }

        $this->syncStudentLoansStatus($student);

        $loans = Loan::with('book.category')
            ->where(function ($q) use ($student) {
                $q->where('member_nim', $student->nim_no_anggota)
                    ->orWhere('anggota_id', $student->id);
            })
            ->latest('created_at')
            ->get();

        $siapDiambil = $loans->where('status', 'booked');
        $sedangDipinjam = $loans->whereIn('status', ['dipinjam', 'terlambat']);
        $terlambat = $loans->filter(function (Loan $loan) {
            return $loan->isOverdue();
        });

        $activeTab = $request->get('tab');
        if (! in_array($activeTab, ['siap', 'dipinjam', 'denda'])) {
            $activeTab = $siapDiambil->isNotEmpty()
                ? 'siap'
                : ($sedangDipinjam->isNotEmpty()
                    ? 'dipinjam'
                    : ($terlambat->isNotEmpty() ? 'denda' : 'siap'));
        }

        return view('front.pinjaman', [
            'student' => $student,
            'siapDiambil' => $siapDiambil,
            'sedangDipinjam' => $sedangDipinjam,
            'terlambat' => $terlambat,
            'activeTab' => $activeTab,
            'successBookingCode' => $request->get('success_booking'),
        ]);
    }

    /**
     * Batalkan Booking Buku.
     */
    public function cancelBooking(int $id): RedirectResponse
    {
        $student = $this->getStudent();
        if (! $student) {
            return redirect()->route('masuk');
        }

        $loan = Loan::where('id', $id)
            ->where('status', 'booked')
            ->where(function ($q) use ($student) {
                $q->where('member_nim', $student->nim_no_anggota)
                    ->orWhere('anggota_id', $student->id);
            })
            ->firstOrFail();

        $loan->update(['status' => 'dibatalkan']);

        return back()->with('success', "Booking dengan kode {$loan->booking_code} berhasil dibatalkan.");
    }

    /**
     * Perpanjang Peminjaman Buku (+3 Hari).
     */
    public function extendLoan(int $id): RedirectResponse
    {
        $student = $this->getStudent();
        if (! $student) {
            return redirect()->route('masuk');
        }

        $loan = Loan::where('id', $id)
            ->where('status', 'dipinjam')
            ->where(function ($q) use ($student) {
                $q->where('member_nim', $student->nim_no_anggota)
                    ->orWhere('anggota_id', $student->id);
            })
            ->firstOrFail();

        if ($loan->is_extended) {
            return back()->with('error', 'Buku ini sudah pernah diperpanjang sebelumnya (maksimal 1x perpanjangan).');
        }

        if ($loan->isOverdue()) {
            return back()->with('error', 'Buku yang sudah melewati tanggal jatuh tempo tidak dapat diperpanjang.');
        }

        $loan->update(['is_extended' => true]);

        return back()->with('success', 'Peminjaman berhasil diperpanjang +3 hari kerja!');
    }

    /**
     * Riwayat Peminjaman Buku.
     */
    public function riwayat(Request $request): View|RedirectResponse
    {
        $student = $this->getStudent();
        if (! $student) {
            return redirect()->route('masuk')->with('warning', 'Silakan masuk untuk melihat riwayat peminjaman Anda.');
        }

        $filter = $request->get('status', 'semua'); // semua, tepat, terlambat
        $search = trim((string) $request->get('q', ''));

        $query = Loan::with('book.category')
            ->where('status', 'dikembalikan')
            ->where(function ($q) use ($student) {
                $q->where('member_nim', $student->nim_no_anggota)
                    ->orWhere('anggota_id', $student->id);
            });

        if ($search !== '') {
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('penulis', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('book_code', 'like', "%{$search}%");
            });
        }

        if ($filter === 'tepat') {
            $query->where('fine_amount', '<=', 0);
        } elseif ($filter === 'terlambat') {
            $query->where('fine_amount', '>', 0);
        }

        $historyLoans = $query->latest('updated_at')->paginate(10)->withQueryString();

        return view('front.riwayat', [
            'student' => $student,
            'historyLoans' => $historyLoans,
            'currentFilter' => $filter,
            'currentSearch' => $search,
        ]);
    }

    /**
     * Halaman Masuk (Login).
     */
    public function masuk(): View|RedirectResponse
    {
        if ($this->getStudent()) {
            return redirect()->route('beranda');
        }

        $demoStudents = Anggota::take(4)->get();

        return view('front.masuk', [
            'demoStudents' => $demoStudents,
        ]);
    }

    /**
     * Proses Masuk Mahasiswa.
     */
    public function prosesMasuk(Request $request): RedirectResponse
    {
        $request->validate([
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);

        $nim = trim($request->input('nim'));
        $password = $request->input('password');

        $student = Anggota::where('nim_no_anggota', $nim)->first();

        if (! $student) {
            return back()->withInput()->with('error', 'NIM tidak terdaftar sebagai anggota perpustakaan.');
        }

        // Verify password
        $isValidPassword = false;
        if (! empty($student->password)) {
            $isValidPassword = Hash::check($password, $student->password);
        }

        // Fallback for demo / development convenience if password is 'password123' or equals NIM
        if (! $isValidPassword && ($password === 'password123' || $password === $nim)) {
            $isValidPassword = true;
            $student->password = Hash::make($password);
            $student->save();
        }

        if (! $isValidPassword) {
            return back()->withInput()->with('error', 'Kata sandi salah. Silakan coba kembali.');
        }

        if ($student->status === 'pending') {
            return back()->withInput()->with('warning', 'Pendaftaran Anda sedang Menunggu Verifikasi Pustakawan (Maks. 1x24 Jam).');
        }

        if ($student->status === 'rejected') {
            return back()->withInput()->with('error', 'Pendaftaran anggota Anda ditolak. Silakan hubungi meja sirkulasi perpustakaan.');
        }

        if (! $student->hasVerifiedEmail()) {
            session([
                'pending_verification_email' => $student->email,
                'pending_verification_name' => $student->nama,
            ]);

            return redirect()->route('verifikasi.notice')
                ->with('warning', 'Email Anda belum diverifikasi. Silakan cek kotak masuk atau folder spam email Anda.');
        }

        session(['student_id' => $student->id]);

        return redirect()->intended(route('beranda'))->with('success', "Selamat datang kembali, {$student->nama}!");
    }

    /**
     * Halaman Pendaftaran Anggota Baru.
     */
    public function daftar(): View|RedirectResponse
    {
        if ($this->getStudent()) {
            return redirect()->route('beranda');
        }

        return view('front.daftar');
    }

    /**
     * Proses Pendaftaran Anggota Baru.
     */
    public function prosesDaftar(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
            'no_hp' => preg_replace('/[\s.\-()]+/', '', trim((string) $request->input('no_hp'))),
        ]);

        $request->validate([
            'nama' => 'required|string|max:100',
            'nim_no_anggota' => 'required|string|unique:anggotas,nim_no_anggota|max:30',
            'kelas' => 'required|string|max:20',
            'program_studi' => 'required|string|max:80',
            'tahun_angkatan' => 'required|digits:4',
            'no_hp' => ['required', 'string', 'regex:/^(?:\+62|62|0)8[1-9][0-9]{7,11}$/'],
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@mhs\.polines\.ac\.id$/i', 'unique:anggotas,email', 'max:100'],
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nim_no_anggota.unique' => 'NIM ini sudah terdaftar sebagai anggota.',
            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
            'no_hp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan awalan 08, 628, atau +628 (10–14 digit).',
            'email.regex' => 'Email harus menggunakan domain kampus @mhs.polines.ac.id.',
            'email.unique' => 'Email kampus ini sudah terdaftar.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
            'kota.required' => 'Kota / Kabupaten wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $anggota = Anggota::create([
            'nama' => $request->input('nama'),
            'nim_no_anggota' => $request->input('nim_no_anggota'),
            'kelas' => $request->input('kelas'),
            'program_studi' => $request->input('program_studi'),
            'tahun_angkatan' => (int) $request->input('tahun_angkatan'),
            'no_hp' => $request->input('no_hp'),
            'email' => $request->input('email'),
            'email_verified_at' => null,
            'password' => Hash::make($request->input('password')),
            'status' => 'active',
            'alamat' => $request->input('alamat'),
            'kota' => $request->input('kota'),
        ]);

        $mailWarning = null;
        try {
            $this->sendVerificationEmail($anggota);
        } catch (\Throwable $e) {
            report($e);
            $mailWarning = $this->formatMailFailureWarning($e, 'Pendaftaran berhasil, namun pengiriman email verifikasi gagal');
        }

        session([
            'pending_verification_email' => $anggota->email,
            'pending_verification_name' => $anggota->nama,
        ]);

        if ($mailWarning !== null) {
            return redirect()->route('verifikasi.notice')
                ->with('warning', $mailWarning);
        }

        return redirect()->route('verifikasi.notice')
            ->with('success', 'Pendaftaran berhasil! Tautan verifikasi telah dikirim ke email kampus Anda.');
    }

    /**
     * Halaman pemberitahuan untuk cek email verifikasi.
     */
    public function verifikasiNotice(): View|RedirectResponse
    {
        if ($this->getStudent()) {
            return redirect()->route('beranda');
        }

        return view('front.verifikasi-email', [
            'email' => session('pending_verification_email'),
            'nama' => session('pending_verification_name'),
        ]);
    }

    /**
     * Proses verifikasi email dari tautan bertanda tangan.
     */
    public function verifikasiEmail(Request $request, int $id, string $hash): RedirectResponse
    {
        $anggota = Anggota::findOrFail($id);

        if (! hash_equals(sha1($anggota->email), $hash)) {
            abort(403, 'Tautan verifikasi tidak valid.');
        }

        if (! $anggota->hasVerifiedEmail()) {
            $anggota->email_verified_at = now();
            $anggota->save();
        }

        session()->forget(['pending_verification_email', 'pending_verification_name']);
        session(['student_id' => $anggota->id]);

        return redirect()->route('beranda')
            ->with('success', "Email berhasil diverifikasi! Selamat datang di Perpustakaan Jurusan Akuntansi, {$anggota->nama}.");
    }

    /**
     * Kirim ulang email verifikasi anggota.
     */
    public function kirimUlangVerifikasi(Request $request): RedirectResponse
    {
        $email = strtolower(trim((string) ($request->input('email') ?: session('pending_verification_email'))));

        if ($email === '') {
            return redirect()->route('masuk')
                ->with('error', 'Alamat email tidak ditemukan. Silakan masuk kembali.');
        }

        $anggota = Anggota::where('email', $email)->first();

        if (! $anggota) {
            return back()->with('error', 'Akun dengan email tersebut tidak ditemukan.');
        }

        if ($anggota->hasVerifiedEmail()) {
            return redirect()->route('masuk')
                ->with('success', 'Email Anda sudah terverifikasi. Silakan masuk.');
        }

        session([
            'pending_verification_email' => $anggota->email,
            'pending_verification_name' => $anggota->nama,
        ]);

        try {
            $this->sendVerificationEmail($anggota);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('warning', $this->formatMailFailureWarning($e, 'Pengiriman ulang email verifikasi gagal'));
        }

        return back()->with('success', 'Email verifikasi baru telah dikirim. Silakan cek kotak masuk atau folder spam Anda.');
    }

    /**
     * Format a clear diagnostic warning message without exposing sensitive credentials or full URLs.
     */
    protected function formatMailFailureWarning(\Throwable $e, string $prefix): string
    {
        if (filled(config('services.mail_webhook.url'))) {
            return "{$prefix} via Mail Webhook (".Str::limit($e->getMessage(), 120).').';
        }

        return "{$prefix} (port SMTP diblokir server dan MAIL_WEBHOOK_URL belum terpasang di Railway).";
    }

    /**
     * Kirim email verifikasi ke alamat kampus anggota.
     */
    protected function sendVerificationEmail(Anggota $anggota): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verifikasi.email',
            now()->addHours(24),
            ['id' => $anggota->id, 'hash' => sha1($anggota->email)]
        );

        $webhookUrl = config('services.mail_webhook.url');
        if (! empty($webhookUrl)) {
            $html = view('emails.verify-anggota', [
                'anggota' => $anggota,
                'verificationUrl' => $verificationUrl,
            ])->render();

            $response = Http::timeout(15)
                ->acceptJson()
                ->asJson()
                ->post($webhookUrl, [
                    'secret' => config('services.mail_webhook.secret'),
                    'to' => $anggota->email,
                    'subject' => 'Verifikasi Email Anggota — Perpustakaan Jurusan Akuntansi Polines',
                    'html' => $html,
                ])
                ->throw();

            $body = trim($response->body());
            if ($body === 'Unauthorized') {
                throw new \RuntimeException('Secret webhook tidak cocok dengan Apps Script.');
            }
            if (str_starts_with(strtolower($body), '<!doctype') || str_starts_with(strtolower($body), '<html')) {
                throw new \RuntimeException('Apps Script mengembalikan halaman login/error HTML. Pastikan akses Web App disetel ke Anyone.');
            }

            return;
        }

        Mail::to($anggota->email)->send(new VerifyAnggotaEmail($anggota, $verificationUrl));
    }

    /**
     * Simpan / Hapus Buku dari Favorit.
     */
    public function toggleFavorite(Request $request, int $id)
    {
        $student = $this->getStudent();
        if (! $student) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Silakan masuk terlebih dahulu.'], 401);
            }

            return redirect()->route('masuk')->with('error', 'Silakan masuk untuk menyimpan buku favorit.');
        }

        $book = Book::findOrFail($id);

        $fav = Favorite::where('anggota_id', $student->id)->where('book_id', $book->id)->first();
        if ($fav) {
            $fav->delete();
            $isFavorited = false;
            $msg = 'Buku dihapus dari favorit.';
        } else {
            Favorite::create([
                'anggota_id' => $student->id,
                'book_id' => $book->id,
            ]);
            $isFavorited = true;
            $msg = 'Buku berhasil disimpan ke favorit.';
        }

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Rekapan Buku Favorit Mahasiswa.
     */
    public function favorit(Request $request): View|RedirectResponse
    {
        $student = $this->getStudent();
        if (! $student) {
            return redirect()->route('masuk')->with('warning', 'Silakan masuk untuk melihat rekapan buku favorit Anda.');
        }

        $favoriteBooks = $student->favoriteBooks()->with('category')->latest('favorites.created_at')->get();

        return view('front.favorit', [
            'student' => $student,
            'favoriteBooks' => $favoriteBooks,
        ]);
    }

    /**
     * Halaman Tata Tertib & Ketentuan Layanan Perpustakaan.
     */
    public function tataTertib(): View
    {
        $student = $this->getStudent();

        return view('front.tata-tertib', [
            'student' => $student,
        ]);
    }

    /**
     * Keluar (Logout).
     */
    public function keluar(Request $request): RedirectResponse
    {
        session()->forget('student_id');

        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('beranda')->with('info', 'Anda telah keluar.');
    }
}
