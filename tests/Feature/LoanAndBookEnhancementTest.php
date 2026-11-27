<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LoanAndBookEnhancementTest extends TestCase
{
    use RefreshDatabase;

    public function test_multiple_books_can_have_null_isbn_for_skripsi_or_thesis(): void
    {
        $category = Category::factory()->create();

        $book1 = Book::create([
            'category_id' => $category->id,
            'judul' => 'Analisis Pengaruh Penerapan PSAK 73 terhadap Kinerja Keuangan',
            'penulis' => 'Ahmad Fadhil',
            'penerbit' => 'Jurusan Akuntansi Politeknik',
            'tahun_terbit' => 2024,
            'isbn' => null,
            'stok' => 3,
        ]);

        $book2 = Book::create([
            'category_id' => $category->id,
            'judul' => 'Perancangan Sistem Informasi Akuntansi Kas Berbasis Web',
            'penulis' => 'Nareswari Putri',
            'penerbit' => 'Jurusan Akuntansi Politeknik',
            'tahun_terbit' => 2024,
            'isbn' => null,
            'stok' => 2,
        ]);

        $this->assertDatabaseHas('books', ['id' => $book1->id, 'isbn' => null]);
        $this->assertDatabaseHas('books', ['id' => $book2->id, 'isbn' => null]);
        $this->assertEquals(2, Book::whereNull('isbn')->count());
    }

    public function test_loan_creation_automatically_decrements_book_stock(): void
    {
        $book = Book::factory()->create(['stok' => 5]);
        $member = Member::factory()->create();

        $this->assertEquals(5, $book->stok);

        $loan = Loan::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'tanggal_pinjam' => today(),
            'status' => 'dipinjam',
        ]);

        $this->assertEquals(4, $book->fresh()->stok);
    }

    public function test_returning_book_increments_stock_and_fills_tanggal_kembali(): void
    {
        $book = Book::factory()->create(['stok' => 5]);
        $member = Member::factory()->create();

        $loan = Loan::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'tanggal_pinjam' => today()->subDays(3),
            'status' => 'dipinjam',
        ]);

        $this->assertEquals(4, $book->fresh()->stok);
        $this->assertNull($loan->fresh()->tanggal_kembali);

        // Simulasi aksi pengembalian buku
        $loan->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => today()->toDateString(),
        ]);

        $this->assertEquals(5, $book->fresh()->stok);
        $this->assertEquals('dikembalikan', $loan->fresh()->status);
        $this->assertNotNull($loan->fresh()->tanggal_kembali);
    }

    public function test_loan_overdue_detection_works_correctly(): void
    {
        $book = Book::factory()->create(['stok' => 5]);
        $member = Member::factory()->create();

        // Pinjam 10 hari yang lalu (SOP 7 hari, jadi harusnya terlambat)
        $overdueLoan = Loan::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'tanggal_pinjam' => today()->subDays(10),
            'status' => 'dipinjam',
        ]);

        $this->assertTrue($overdueLoan->isOverdue());

        // Pinjam 2 hari yang lalu (belum lewat 7 hari)
        $activeLoan = Loan::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'tanggal_pinjam' => today()->subDays(2),
            'status' => 'dipinjam',
        ]);

        $this->assertFalse($activeLoan->isOverdue());

        // Pinjam yang sudah dikembalikan tidak pernah dianggap overdue
        $overdueLoan->update(['status' => 'dikembalikan', 'tanggal_kembali' => today()]);
        $this->assertFalse($overdueLoan->fresh()->isOverdue());
    }

    public function test_deleting_active_loan_restores_book_stock(): void
    {
        $book = Book::factory()->create(['stok' => 5]);
        $member = Member::factory()->create();

        $loan = Loan::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'tanggal_pinjam' => today(),
            'status' => 'dipinjam',
        ]);

        $this->assertEquals(4, $book->fresh()->stok);

        $loan->delete();

        $this->assertEquals(5, $book->fresh()->stok);
    }

    private function createActiveStudent(string $nim, string $email): Anggota
    {
        return Anggota::create([
            'nama' => 'Mahasiswa '.$nim,
            'nim_no_anggota' => $nim,
            'kelas' => 'AKT-3A',
            'program_studi' => 'Akuntansi (D3)',
            'tahun_angkatan' => 2024,
            'no_hp' => '081234567890',
            'email' => $email,
            'email_verified_at' => now(),
            'password' => 'SandiKuat8',
            'status' => 'active',
            'alamat' => 'Jl. Prof. Sudarto',
            'kota' => 'Kota Semarang',
        ]);
    }

    public function test_booking_pages_use_standard_seven_day_duration_without_three_day_option(): void
    {
        $student = $this->createActiveStudent('4.41.24.0.01', 'mhs1@mhs.polines.ac.id');
        $book = Book::factory()->create([
            'collection_type' => 'umum',
            'stok' => 2,
        ]);

        $this->withSession(['student_id' => $student->id])
            ->get(route('buku.detail', $book->id))
            ->assertOk()
            ->assertSee('7 hari kerja')
            ->assertDontSee('name="duration" value="3"', false)
            ->assertDontSee('3 Hari');

        $this->get(route('tata-tertib'))
            ->assertOk()
            ->assertSee('7 hari kerja')
            ->assertDontSee('3 atau 7 hari kerja');
    }

    public function test_booking_prevents_double_booking_last_copy_and_frees_expired_booking(): void
    {
        $studentA = $this->createActiveStudent('4.41.24.0.10', 'mhsa@mhs.polines.ac.id');
        $studentB = $this->createActiveStudent('4.41.24.0.11', 'mhsb@mhs.polines.ac.id');
        $book = Book::factory()->create([
            'collection_type' => 'umum',
            'stok' => 1,
        ]);

        $this->withSession(['student_id' => $studentA->id])
            ->post(route('buku.booking', $book->id))
            ->assertRedirect();

        $loanA = Loan::where('anggota_id', $studentA->id)->firstOrFail();
        $this->assertEquals('booked', $loanA->status);

        // Mahasiswa lain tidak bisa mem-booking eksemplar terakhir yang sedang di-booking
        $this->withSession(['student_id' => $studentB->id])
            ->post(route('buku.booking', $book->id))
            ->assertSessionHas('error');

        $this->assertEquals(0, Loan::where('anggota_id', $studentB->id)->count());

        // Simulasikan booking mahasiswa A melewati 24 jam -> otomatis hangus saat mahasiswa B booking
        Loan::whereKey($loanA->id)->update(['created_at' => now()->subHours(25)]);

        $this->withSession(['student_id' => $studentB->id])
            ->post(route('buku.booking', $book->id))
            ->assertRedirect();

        $this->assertEquals('dibatalkan', $loanA->fresh()->status);
        $this->assertDatabaseHas('loans', [
            'anggota_id' => $studentB->id,
            'book_id' => $book->id,
            'status' => 'booked',
        ]);
    }

    public function test_pinjaman_defaults_to_dipinjam_tab_when_siap_is_empty(): void
    {
        $student = $this->createActiveStudent('4.41.24.0.22', 'mhsc@mhs.polines.ac.id');
        $book = Book::factory()->create(['stok' => 3]);

        Loan::create([
            'book_id' => $book->id,
            'anggota_id' => $student->id,
            'member_nim' => $student->nim_no_anggota,
            'card_type' => 'KTM',
            'status' => 'dipinjam',
            'tanggal_pinjam' => today()->toDateString(),
        ]);

        $this->withSession(['student_id' => $student->id])
            ->get(route('pinjaman'))
            ->assertOk()
            ->assertViewHas('activeTab', 'dipinjam');
    }

    public function test_uploaded_public_disk_book_cover_renders_in_user_portal_and_serves_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('covers/test-upload.jpg', 'fake-image-bytes');

        $book = Book::factory()->create([
            'cover' => 'covers/test-upload.jpg',
        ]);

        $expectedUrl = route('storage.file', ['path' => 'covers/test-upload.jpg']);
        $this->assertEquals($expectedUrl, $book->cover_url);

        $this->get(route('katalog'))
            ->assertOk()
            ->assertSee($expectedUrl, false);

        $this->get(route('buku.detail', $book->id))
            ->assertOk()
            ->assertSee($expectedUrl, false);

        $this->get($expectedUrl)
            ->assertOk();
    }
}
