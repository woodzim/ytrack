<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPerjalanan;
use App\Models\SesiAlat;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\RiwayatPerjalananCreated;
use Carbon\Carbon;

class GpsTrackingController extends Controller
{
    /**
     * Submit GPS tracking data
     * POST /v1/submit-gps
     */
    public function submitGps(Request $request)
    {
        // --- 1. CEK AUTHORIZATION (TETAP SAMA) ---
        if (!$request->hasHeader('Authorization')) {
            Log::warning('Mencoba mengakses submit-gps tanpa Authorization header.', [
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);
            return response()->json(['status_code' => 401, 'message' => 'Unauthorized: Tidak ada header Authorization.'], 401);
        }

        if ($request->header('Authorization') !== config('gps_tracking.api_key')) {
            Log::warning('Mencoba mengakses submit-gps dengan Authorization header yang tidak valid.', [
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);
            return response()->json(['status_code' => 401, 'message' => 'Unauthorized: Header Authorization tidak valid.'], 401);
        }

        try {
            // --- 2. VALIDASI INPUT ---
            $validated = $request->validate([
                'id_alat'  => 'required|string',
                'lat'      => 'required|string', // String agar presisi terjaga
                'long'     => 'required|string',
                'alt'      => 'nullable|string',
                'sat'      => 'nullable|integer',
                'speed'    => 'nullable|integer',
                'battery'  => 'nullable|string',
                'datetime' => 'nullable|date_format:Y-m-d H:i:s',
            ]);

            // --- 3. CEK DATA MASTER ALAT (WAJIB ADA) ---
            // Kita cari alat berdasarkan ID yang dikirim
            $alat = Alat::where('id', $validated['id_alat']) // Sesuaikan jika kolomnya 'serial_number'
                        ->first();

            if (!$alat) {
                Log::channel('uncomplete_track_histories')->warning('Alat tidak ditemukan di Master Data: ' . $validated['id_alat']);
                return response()->json([
                    'status_code' => 404,
                    'message' => 'ID Alat tidak terdaftar di sistem.'
                ], 404);
            }

            // --- 4. LOGIKA AUTO-CREATE SESI ---
            // Cari sesi yang sedang aktif untuk alat ini
            $activeSesiAlat = SesiAlat::where('id_alat', $alat->id)
                ->where('aktif', true)
                ->first();

            // JIKA TIDAK ADA SESI AKTIF -> BUAT BARU
            if (!$activeSesiAlat) {
                Log::info("Sesi tidak ditemukan untuk Alat {$alat->id}, membuat sesi otomatis.");

                $activeSesiAlat = SesiAlat::create([
                    'id_alat'       => $alat->id,
                    'id_perusahaan' => $alat->id_perusahaan ?? null, // Ambil dari relasi alat jika ada
                    'id_kendaraan'  => $alat->id_kendaraan ?? null,  // Ambil dari relasi alat jika ada
                    'waktu_mulai'   => Carbon::now(),
                    'aktif'         => true,
                    'keterangan'    => 'Sesi Otomatis by System'
                ]);
            }

            // --- 5. SIAPKAN DATA RIWAYAT ---
            // Sekarang $activeSesiAlat PASTI ADA.
            
            $riwayat = RiwayatPerjalanan::create([
                'id_sesi_alat'  => $activeSesiAlat->id,
                'id_perusahaan' => $activeSesiAlat->id_perusahaan,
                'id_kendaraan'  => $activeSesiAlat->id_kendaraan,
                'id_alat'       => $alat->id,
                'lat'           => $validated['lat'],
                'long'          => $validated['long'],
                'alt'           => $validated['alt'] ?? "0",
                'sat'           => $validated['sat'] ?? 0,
                'speed'         => $validated['speed'] ?? 0,
                'battery'       => $validated['battery'] ?? null,
                'waktu_rekam'   => $validated['datetime'] ?? Carbon::now(), // Gunakan waktu server jika kosong
            ]);

            // --- 6. TEMBAK EVENT KE PUSHER ---
            // Karena id_sesi_alat pasti ada, event ini dijamin jalan
            event(new RiwayatPerjalananCreated(
                (int) $activeSesiAlat->id,
                (float) $riwayat->lat,
                (float) $riwayat->long,
                $riwayat->created_at->toDateTimeString(),
                (int) $riwayat->speed,
                (string) $riwayat->alt,
                (string) $riwayat->sat,
                (string) $riwayat->battery
            ));

            return response()->json([
                'status_code' => 201,
                'message' => 'GPS data received & broadcasted successfully',
                'data' => [
                    'id' => $riwayat->id,
                    'sesi_id' => $activeSesiAlat->id
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error("GPS Error: " . $e->getMessage());
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }
}