<?php

namespace Tests\Feature;

use App\Mail\VerifyAnggotaEmail;
use App\Models\Anggota;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class StudentRegistrationAndVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'nama' => 'Budi Santoso',
            'nim_no_anggota' => '4.41.24.0.99',
            'kelas' => 'AKT-3A',
            'program_studi' => 'Akuntansi (D3)',
            'tahun_angkatan' => '2024',
            'no_hp' => '0812-3456-7890',
            'email' => 'Budi.Santoso@mhs.polines.ac.id',
            'alamat' => 'Jl. Prof. Sudarto No. 12, Tembalang',
            'kota' => 'Kota Semarang',
            'password' => 'SandiKuat8',
            'password_confirmation' => 'SandiKuat8',
        ], $overrides);
    }

    public function test_student_can_register_and_receives_verification_email(): void
    {
        Mail::fake();

        $response = $this->post(route('daftar.proses'), $this->validPayload());

        $response->assertRedirect(route('verifikasi.notice'));
        $this->assertNull(session('student_id'));

        $this->assertDatabaseHas('anggotas', [
            'nim_no_anggota' => '4.41.24.0.99',
            'email' => 'budi.santoso@mhs.polines.ac.id',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Prof. Sudarto No. 12, Tembalang',
            'kota' => 'Kota Semarang',
            'email_verified_at' => null,
        ]);

        Mail::assertSent(VerifyAnggotaEmail::class, function (VerifyAnggotaEmail $mail): bool {
            return $mail->hasTo('budi.santoso@mhs.polines.ac.id')
                && str_contains($mail->verificationUrl, '/verifikasi-email/');
        });
    }

    public function test_registration_rejects_non_polines_email_domain(): void
    {
        Mail::fake();

        $response = $this->post(route('daftar.proses'), $this->validPayload([
            'email' => 'budi@gmail.com',
        ]));

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseCount('anggotas', 0);
        Mail::assertNothingSent();
    }

    public function test_registration_rejects_invalid_whatsapp_format(): void
    {
        Mail::fake();

        $response = $this->post(route('daftar.proses'), $this->validPayload([
            'no_hp' => '0215551234',
        ]));

        $response->assertSessionHasErrors(['no_hp']);
        $this->assertDatabaseCount('anggotas', 0);
    }

    public function test_registration_requires_8_char_password_confirmation_alamat_and_kota(): void
    {
        Mail::fake();

        $response = $this->post(route('daftar.proses'), $this->validPayload([
            'password' => '123456',
            'password_confirmation' => '123456',
            'alamat' => '',
            'kota' => '',
        ]));

        $response->assertSessionHasErrors(['password', 'alamat', 'kota']);
        $this->assertDatabaseCount('anggotas', 0);
    }

    public function test_unverified_student_cannot_login_and_is_redirected_to_verification_notice(): void
    {
        Anggota::create([
            'nama' => 'Siti Aminah',
            'nim_no_anggota' => '4.41.24.0.88',
            'kelas' => 'AKT-3B',
            'program_studi' => 'Akuntansi (D3)',
            'tahun_angkatan' => 2024,
            'no_hp' => '081234567891',
            'email' => 'siti@mhs.polines.ac.id',
            'email_verified_at' => null,
            'password' => Hash::make('SandiKuat8'),
            'status' => 'active',
            'alamat' => 'Tembalang',
            'kota' => 'Kota Semarang',
        ]);

        $response = $this->post(route('masuk.proses'), [
            'nim' => '4.41.24.0.88',
            'password' => 'SandiKuat8',
        ]);

        $response->assertRedirect(route('verifikasi.notice'));
        $this->assertNull(session('student_id'));
    }

    public function test_student_can_verify_email_via_signed_link_and_resend_verification(): void
    {
        Mail::fake();

        $anggota = Anggota::create([
            'nama' => 'Rina Putri',
            'nim_no_anggota' => '4.41.24.0.77',
            'kelas' => 'KA-3A',
            'program_studi' => 'Komputerisasi Akuntansi (S. Tr)',
            'tahun_angkatan' => 2024,
            'no_hp' => '+6281234567892',
            'email' => 'rina@mhs.polines.ac.id',
            'email_verified_at' => null,
            'password' => Hash::make('SandiKuat8'),
            'status' => 'active',
            'alamat' => 'Banyumanik',
            'kota' => 'Kota Semarang',
        ]);

        $resendResponse = $this->post(route('verifikasi.resend'), [
            'email' => 'rina@mhs.polines.ac.id',
        ]);
        $resendResponse->assertSessionHas('success');
        Mail::assertSent(VerifyAnggotaEmail::class, 1);

        $signedUrl = URL::temporarySignedRoute(
            'verifikasi.email',
            now()->addHours(24),
            ['id' => $anggota->id, 'hash' => sha1($anggota->email)]
        );

        $verifyResponse = $this->get($signedUrl);

        $verifyResponse->assertRedirect(route('beranda'));
        $this->assertNotNull($anggota->fresh()->email_verified_at);
        $this->assertEquals($anggota->id, session('student_id'));
    }
}
