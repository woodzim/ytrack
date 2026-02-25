<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <div style="background-color: #BE1E2D; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;">
            <img src="{{ url('images/square-logo.png') }}" alt="YA-Track Logo" style="height: 60px; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;">
            <h1 style="margin: 0;">Notifikasi Pendaftaran Perusahaan Baru</h1>
        </div>

        <div style="padding: 20px;">
            <p>Halo Admin,</p>

            <p>Ada pendaftaran perusahaan baru yang memerlukan validasi dari Anda.</p>

            <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #BE1E2D; margin: 20px 0;">
                <h3>Detail Pendaftaran Perusahaan</h3>
                <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Nama Perusahaan</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $user->perusahaan?->nama ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Alamat Perusahaan</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $user->perusahaan?->alamat ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Nama Pengguna</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Email</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Tanggal Pendaftaran</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $user->created_at?->translatedFormat('d-m-Y H:i:s') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Status</strong></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">Menunggu Validasi</td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px;">
            <p>&copy; {{ date('Y') }} YA-Track. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>

