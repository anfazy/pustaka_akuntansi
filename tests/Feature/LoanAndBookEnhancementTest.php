<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
