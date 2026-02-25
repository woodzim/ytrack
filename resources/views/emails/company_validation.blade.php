<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <div style="color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; background-color: #BE1E2D;">
            <img src="{{ url('images/square-logo.png') }}" alt="YA-Track Logo" style="height: 60px; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;">
            <h1 style="margin: 0;">

        @if ($isValidated)
          Akun Anda Telah Divalidasi
        @else
          Update Status Akun Anda
        @endif
      </h1>
    </div>

    <div style="padding: 20px;">
      <p>Halo <strong>{{ $user->name }}</strong>,</p>

      @if ($isValidated)
        <p>Selamat! Akun Anda telah berhasil divalidasi oleh tim admin YA-Track.</p>

        <div
          style="display: inline-block; padding: 8px 15px; border-radius: 20px; font-weight: bold; margin: 10px 0; background-color: #BE1E2D; color: white;">
          AKUN TERVALIDASI</div>

        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #BE1E2D; margin: 20px 0;">
          <h3>Informasi Perusahaan</h3>
          <p><strong>Nama Perusahaan:</strong> {{ $user->perusahaan?->nama ?? 'N/A' }}</p>
          <p><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
        </div>

        <a href="{{ route('login') }}"
          style="display: inline-block; padding: 10px 20px; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; background-color: #BE1E2D;">Login
          Sekarang</a>

        <p>Jika Anda mengalami kesulitan dalam login, silakan hubungi tim support kami.</p>
      @else
        <p>Kami telah menerima informasi tentang perubahan status validasi akun Anda.</p>

        <div
          style="display: inline-block; padding: 8px 15px; border-radius: 20px; font-weight: bold; margin: 10px 0; background-color: #BE1E2D; color: white;">
          AKUN BELUM TERVALIDASI</div>

        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #BE1E2D; margin: 20px 0;">
          <h3>Informasi Akun</h3>
          <p><strong>Nama Perusahaan:</strong> {{ $user->perusahaan?->nama ?? 'N/A' }}</p>
          <p><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</p>
          <p><strong>Status:</strong> Menunggu Verifikasi</p>
        </div>

        <p>Akun Anda sedang dalam tahap verifikasi. Tim admin kami akan melakukan pemeriksaan data pendaftaran Anda.
          Silakan tunggu sampai mendapat notifikasi bahwa akun Anda telah divalidasi.</p>

        <p>Jika Anda memiliki pertanyaan atau ingin memberikan informasi tambahan, silakan hubungi kami melalui email
          atau hubungi tim support kami.</p>
      @endif

      <p>Terima kasih atas perhatian Anda.<br>
        <strong>Tim YA-Track</strong>
      </p>
    </div>

    <div
      style="background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px;">
      <p>&copy; {{ date('Y') }} YA-Track. Semua hak dilindungi.</p>
    </div>
  </div>
</body>

</html>
