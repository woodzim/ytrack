<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Validasi Akun - YA-Track</title>
</head>

<body
  style="font-family: 'Space Grotesk', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f9f7fc;">
  <div
    style="max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

    <!-- Header -->
    <div style="background-color: #0F2854; padding: 30px 20px; text-align: center;">
      <img src="{{ $message->embed(public_path('images/long-logo-white.png')) }}" alt="YA-Track Logo"
        style="height: 50px; display: block; margin: 0 auto;">
    </div>

    <!-- Content -->
    <div style="padding: 40px 30px;">
      <h1 style="color: #0F2854; margin-top: 0; font-size: 24px; text-align: center;">
        @if ($isValidated)
          Akun Anda Telah Divalidasi
        @else
          Update Status Akun Anda
        @endif
      </h1>

      <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">Halo <strong>{{ $user->name }}</strong>,</p>

      @if ($isValidated)
        <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
          Selamat! Akun Anda telah berhasil divalidasi oleh tim admin YA-Track.
        </p>

        <div
          style="background-color: #e8f5e9; padding: 15px; border-left: 4px solid #2ecc71; margin: 20px 0; border-radius: 4px;">
          <h3 style="margin-top: 0; color: #2ecc71; font-size: 18px;">Status: AKUN TERVALIDASI</h3>
          <p style="margin: 5px 0; color: #5d6576;"><strong>Nama Perusahaan:</strong>
            {{ $user->perusahaan?->nama ?? 'N/A' }}</p>
          <p style="margin: 5px 0; color: #5d6576;"><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
        </div>

        <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
          Anda sekarang dapat login dan mengakses fitur lengkap YA-Track.
        </p>

        <div style="text-align: center; margin: 30px 0;">
          <a href="{{ route('login') }}"
            style="display: inline-block; padding: 12px 24px; background-color: #0F2854; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; transition: background-color 0.3s;">
            Login Sekarang
          </a>
        </div>

        <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
          Jika Anda mengalami kesulitan dalam login, silakan hubungi tim support kami.
        </p>
      @else
        <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
          Kami ingin menginformasikan mengenai status akun Anda.
        </p>

        <div
          style="background-color: #fff3e0; padding: 15px; border-left: 4px solid #e67e22; margin: 20px 0; border-radius: 4px;">
          <h3 style="margin-top: 0; color: #e67e22; font-size: 18px;">Status: BELUM TERVALIDASI</h3>
          <p style="margin: 5px 0; color: #5d6576;"><strong>Nama Perusahaan:</strong>
            {{ $user->perusahaan?->nama ?? 'N/A' }}</p>
          <p style="margin: 5px 0; color: #5d6576;"><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
        </div>

        <p style="margin-bottom: 20px; color: #5d6576; font-size: 16px;">
          Saat ini akun Anda sedang ditinjau ulang atau ditangguhkan validasinya oleh admin.
          Silakan hubungi kami jika Anda merasa ini adalah kesalahan.
        </p>
      @endif

      <p style="margin-bottom: 0; margin-top: 30px; color: #5d6576; font-size: 16px;">
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
