<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <div style="background-color: #BE1E2D; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
            <img src="{{ url('images/square-logo.png') }}" alt="YA-Track Logo" style="height: 60px; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;">
            <h1 style="margin: 0;">Selamat Datang di YA-Track</h1>
        </div>

        <div style="padding: 20px;">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>

            <p>Terima kasih telah mendaftar di YA-Track! Pendaftaran perusahaan Anda telah kami terima dengan baik.</p>

            <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #BE1E2D; margin: 20px 0;">
                <h3>Informasi Pendaftaran</h3>
                <p><strong>Nama Perusahaan:</strong> {{ $user->perusahaan->nama }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Alamat:</strong> {{ $user->perusahaan->alamat }}</p>
            </div>

            <p><strong>Status Akun:</strong> Menunggu verifikasi dari admin</p>

            <p>Tim admin kami akan melakukan validasi akun Anda. Setelah akun Anda diverifikasi, Anda dapat langsung login dan mulai menggunakan sistem YA-Track kami.</p>

            <p>Jika ada pertanyaan atau memerlukan bantuan, jangan ragu untuk menghubungi kami.</p>

            <p>Salam,<br>
            <strong>Tim YA-Track</strong></p>
        </div>

        <div style="background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px;">
            <p>&copy; {{ date('Y') }} YA-Track. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
