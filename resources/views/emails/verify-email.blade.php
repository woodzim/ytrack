<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Email - YA-Track</title>
</head>

<body
  style="font-family: 'Space Grotesk', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f9f7fc;">
  <div
    style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

            <!-- Header -->
            <div style="background-color: #0F2854; padding: 30px 20px; text-align: center;">
                <img src="{{ $message->embed(public_path('images/long-logo-white.png')) }}" alt="YA-Track Logo" style="height: 50px; display: block; margin: 0 auto;">
            </div>

    <!-- Content -->
    <div style="padding: 40px 30px;">
      <h1 style="color: #0F2854; margin-top: 0; font-size: 24px; text-align: center;">Verifikasi Alamat Email Anda</h1>

      <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">Halo <strong>{{ $user->name }}</strong>,</p>

      <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
        Terima kasih telah mendaftar di YA-Track. Mohon verifikasi alamat email Anda untuk mengaktifkan akun dan mulai
        menggunakan layanan kami.
      </p>

      <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}"
          style="background-color: #0F2854; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; font-size: 16px;">Verifikasi
          Email</a>
      </div>

      <p style="margin-bottom: 20px; color: #5d6576; font-size: 14px;">
        Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:
      </p>

      <p style="margin-bottom: 20px; word-break: break-all; color: #0F2854; font-size: 14px;">
        <a href="{{ $url }}" style="color: #0F2854;">{{ $url }}</a>
      </p>

      <p style="margin-bottom: 0; color: #5d6576; font-size: 14px;">
        Jika Anda tidak merasa mendaftar di YA-Track, abaikan email ini.
      </p>
    </div>

    <!-- Footer -->
    <div style="background-color: #f2f4f5; padding: 20px; text-align: center; color: #949eb7; font-size: 12px;">
      <p style="margin: 0;">&copy; {{ date('Y') }} Y-Track. Designed with <span style="color: red;">&hearts;</span>
        by dumeg.id</p>
    </div>
  </div>
</body>

</html>
