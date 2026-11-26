<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Anggota</title>
</head>
<body style="margin:0; padding:0; background-color:#f0f7f4; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:560px; background-color:#ffffff; border-radius:24px; overflow:hidden; border:1px solid #d1fae5; box-shadow:0 12px 32px rgba(6, 78, 59, 0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg, #064e3b 0%, #059669 100%); padding:28px 32px; text-align:center;">
                            <div style="font-size:12px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#a7f3d0; margin-bottom:6px;">
                                Ruang Baca Jurusan Polines
                            </div>
                            <div style="font-size:20px; font-weight:700; color:#ffffff; line-height:1.3;">
                                Perpustakaan Jurusan Akuntansi
                            </div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 6px; font-size:15px; color:#64748b;">Selamat Datang,</p>
                            <h1 style="margin:0 0 24px; font-size:22px; font-weight:700; color:#065f46;">
                                {{ $anggota->nama }}
                            </h1>

                            <div style="text-align:center; margin:28px 0;">
                                <a href="{{ $verificationUrl }}"
                                   style="display:inline-block; background:linear-gradient(135deg, #059669 0%, #047857 100%); color:#ffffff; text-decoration:none; font-size:15px; font-weight:600; padding:14px 36px; border-radius:9999px; box-shadow:0 6px 18px rgba(5, 150, 105, 0.28);">
                                    Verifikasi Email
                                </a>
                            </div>

                            <p style="margin:0 0 14px; font-size:14px; line-height:1.65; color:#334155;">
                                Dalam rangka kelengkapan registrasi anggota Perpustakaan Jurusan Akuntansi Polines, kami meminta Anda untuk melakukan verifikasi email. Silakan klik tombol di atas sebagai bentuk konfirmasi alamat email Anda.
                            </p>

                            <p style="margin:0 0 20px; font-size:14px; line-height:1.65; color:#334155;">
                                Tautan verifikasi ini berlaku selama <strong>24 jam</strong>. Setelah terverifikasi, Anda dapat langsung masuk untuk mencari buku, memesan peminjaman (booking), dan memantau riwayat sirkulasi.
                            </p>

                            <!-- Fallback URL -->
                            <div style="background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:12px 14px; margin-bottom:24px;">
                                <p style="margin:0 0 6px; font-size:12px; font-weight:600; color:#64748b;">
                                    Jika tombol tidak berfungsi, salin tautan berikut ke browser Anda:
                                </p>
                                <p style="margin:0; font-size:11px; color:#047857; word-break:break-all; line-height:1.5;">
                                    {{ $verificationUrl }}
                                </p>
                            </div>

                            <!-- Security Warning (inspired by iPusnas) -->
                            <div style="background-color:#f0fdf4; border-left:4px solid #10b981; border-radius:8px; padding:14px 16px;">
                                <p style="margin:0; font-size:12px; line-height:1.6; color:#334155; font-style:italic;">
                                    Hati-hati terhadap pihak yang mengaku dari Perpustakaan Jurusan Akuntansi Polines yang bertujuan membagikan hadiah atau meminta data pribadi. Kami tidak pernah meminta kata sandi melalui email, pesan pribadi, atau saluran lainnya.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color:#f8fafc; padding:20px 32px; text-align:center; border-top:1px solid #e2e8f0;">
                            <p style="margin:0 0 6px; font-size:12px; color:#64748b;">
                                &copy; {{ date('Y') }} Perpustakaan Jurusan Akuntansi Polines.
                            </p>
                            <p style="margin:0; font-size:11px; color:#94a3b8;">
                                Jangan membalas email ini, karena email ini dikirim otomatis oleh sistem.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
