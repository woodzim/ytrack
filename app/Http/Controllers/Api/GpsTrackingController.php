<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPerjalanan;
use App\Models\SesiAlat;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\RiwayatPerjalananCreated;

class GpsTrackingController extends Controller
{
    /**
     * Submit GPS tracking data
     * POST /v1/submit-gps
     */
    public function submitGps(Request $request)
    {
        if (!$request->hasHeader('Authorization')) {
            Log::warning('Mencoba mengakses submit-gps tanpa Authorization header.', [
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);

            return response()->json([
                'status_code' => 401,
                'message' => 'Unauthorized: Tidak ada header Authorization.'
            ], 401);
        }

        if ($request->header('Authorization') !== config('gps_tracking.api_key')) {
            Log::warning('Mencoba mengakses submit-gps dengan Authorization header yang tidak valid.', [
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);

            return response()->json([
                'status_code' => 401,
                'message' => 'Unauthorized: Header Authorization tidak valid.'
            ], 401);
        }

        try {
            // Validate request
            $validated = $request->validate([
                'id_alat' => 'required|string',
                'lat' => 'required|string',
                'long' => 'required|string',
                'alt' => 'nullable|string',
                'sat' => 'nullable|integer',
                'speed' => 'nullable|integer',
                'datetime' => 'nullable|date_format:Y-m-d H:i:s',
            ]);

            // Initialize data
            $idSesiAlat = null;
            $idPerusahaan = null;
            $idKendaraan = null;
            $idAlat = null;

            // Step 1: Check if there's an active session using this alat
            $activeSesiAlat = SesiAlat::where('id_alat', $validated['id_alat'])
                ->where('aktif', true)
                ->first();

            if ($activeSesiAlat) {
                // If active session found, get all required data
                $idSesiAlat = $activeSesiAlat->id;
                $idAlat = $activeSesiAlat->id_alat;
                $idPerusahaan = $activeSesiAlat->id_perusahaan;
                $idKendaraan = $activeSesiAlat->id_kendaraan;
            } else {
                Log::channel('uncomplete_track_histories')->warning('Tidak ada sesi alat aktif untuk id_alat: ' . $validated['id_alat'], [
                    'id_alat' => $validated['id_alat'],
                    'lat' => $validated['lat'] ?? null,
                    'long' => $validated['long'] ?? null,
                    'alt' => $validated['alt'] ?? null,
                    'sat' => $validated['sat'] ?? null,
                    'speed' => $validated['speed'] ?? null,
                    'datetime' => $validated['datetime'] ?? null
                ]);

                // Step 2: Check if alat exists
                $alat = Alat::find($validated['id_alat']);

                if ($alat) {
                    // If alat exists, set id_alat but leave others null
                    $idAlat = $alat->id;

                    // id_sesi_alat, id_perusahaan, id_kendaraan remain null
                } else {
                    Log::channel('uncomplete_track_histories')->warning('Alat tidak ditemukan untuk id_alat: ' . $validated['id_alat'], [
                        'id_alat' => $validated['id_alat'],
                        'lat' => $validated['lat'] ?? null,
                        'long' => $validated['long'] ?? null,
                        'alt' => $validated['alt'] ?? null,
                        'sat' => $validated['sat'] ?? null,
                        'speed' => $validated['speed'] ?? null,
                        'datetime' => $validated['datetime'] ?? null
                    ]);
                }
            }

            // Create riwayat perjalanan record
            $riwayat = RiwayatPerjalanan::create([
                'id_sesi_alat' => $idSesiAlat,
                'id_perusahaan' => $idPerusahaan,
                'id_kendaraan' => $idKendaraan,
                'id_alat' => $idAlat,
                'lat' => $validated['lat'] ?? null,
                'long' => $validated['long'] ?? null,
                'alt' => $validated['alt'] ?? null,
                'sat' => $validated['sat'] ?? null,
                'speed' => $validated['speed'] ?? null,
            ]);

            if ($idSesiAlat) {
                event(new RiwayatPerjalananCreated(
                    $idSesiAlat,
                    $riwayat->lat,
                    $riwayat->long,
                    $riwayat->created_at->toDateTimeString(),
                    $riwayat->speed,
                    $riwayat->alt,
                    $riwayat->sat
                ));
            }

            return response()->json([
                'status_code' => 201,
                'message' => 'GPS data received successfully',
                'data' => [
                    'id' => $riwayat->id,
                    'id_alat' => $riwayat->id_alat,
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
