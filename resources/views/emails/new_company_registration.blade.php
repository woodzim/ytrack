<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selamat Datang - YA-Track</title>
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
      <h1 style="color: #0F2854; margin-top: 0; font-size: 24px; text-align: center;">Selamat Datang di YA-Track</h1>

      <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">Halo <strong>{{ $user->name }}</strong>,</p>

      <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
        Terima kasih telah mendaftar di YA-Track! Pendaftaran perusahaan Anda telah kami terima dengan baik.
      </p>

      <div style="background-color: #f9f9f9; padding: 20px; border-left: 4px solid #0F2854; margin: 20px 0; border-radius: 4px;">
        <h3 style="margin-top: 0; color: #0F2854; font-size: 18px;">Informasi Pendaftaran</h3>
        <p style="margin: 5px 0; color: #5d6576;"><strong>Nama Perusahaan:</strong> {{ $user->perusahaan->nama }}</p>
        <p style="margin: 5px 0; color: #5d6576;"><strong>Email:</strong> {{ $user->email }}</p>
        <p style="margin: 5px 0; color: #5d6576;"><strong>Alamat:</strong> {{ $user->perusahaan->alamat }}</p>
      </div>

      <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
        <strong>Status Akun:</strong> <span style="color: #e67e22; font-weight: bold;">Menunggu verifikasi dari admin</span>
      </p>

      <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
        Tim admin kami akan melakukan validasi akun Anda. Setelah akun Anda diverifikasi, Anda dapat langsung login dan
        mulai menggunakan sistem YA-Track kami.
      </p>

      <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
        Jika ada pertanyaan atau memerlukan bantuan, jangan ragu untuk menghubungi kami.
      </p>

      <p style="margin-bottom: 0; color: #5d6576; font-size: 16px;">
        Salam,<br>
        <strong>Tim YA-Track</strong>
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

