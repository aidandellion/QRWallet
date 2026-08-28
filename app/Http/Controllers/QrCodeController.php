<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\PaymentPlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    /**
     * Show the dashboard with the user's QR codes.
     */
    public function index(Request $request)
    {
        $query = QrCode::where('user_id', Auth::id())->with('paymentPlatform');

        if ($request->filled('search')) {
            $query->where('label', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('platform')) {
            $query->where('platform_id', $request->platform);
        }

        $qrCodes = $query->latest()->get();

        $platforms = PaymentPlatform::orderBy('platform_name')->get();

        $totalQrCodes = QrCode::where('user_id', Auth::id())->count();
        $totalPlatformsUsed = QrCode::where('user_id', Auth::id())
            ->distinct('platform_id')
            ->count('platform_id');

        return view('dashboard', compact('qrCodes', 'platforms', 'totalQrCodes', 'totalPlatformsUsed'));
    }

    /**
     * Store a newly added QR code.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'platform_id' => ['required', 'exists:payment_platforms,id'],
            'qr_image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
        ]);

        $path = $request->file('qr_image')->store('qr_codes', 'public');

        QrCode::create([
            'user_id' => Auth::id(),
            'platform_id' => $validated['platform_id'],
            'label' => $validated['label'],
            'qr_image' => $path,
        ]);

        return redirect()->route('dashboard')->with('success', 'QR Code added successfully.');
    }

    /**
     * Update an existing QR code.
     */
    public function update(Request $request, QrCode $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'platform_id' => ['required', 'exists:payment_platforms,id'],
            'qr_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
        ]);

        if ($request->hasFile('qr_image')) {
            if ($qrCode->qr_image) {
                Storage::disk('public')->delete($qrCode->qr_image);
            }
            $validated['qr_image'] = $request->file('qr_image')->store('qr_codes', 'public');
        }

        $qrCode->update($validated);

        return redirect()->route('dashboard')->with('success', 'QR Code updated successfully.');
    }

    /**
     * Delete a QR code.
     */
    public function destroy(QrCode $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) {
            abort(403);
        }

        if ($qrCode->qr_image) {
            Storage::disk('public')->delete($qrCode->qr_image);
        }

        $qrCode->delete();

        return redirect()->route('dashboard')->with('success', 'QR Code deleted successfully.');
    }

    /**
     * Download a QR code image.
     */
    public function download(QrCode $qrCode)
    {
        if ($qrCode->user_id !== Auth::id()) {
            abort(403);
        }

        $path = storage_path('app/public/' . $qrCode->qr_image);
        $filename = 'QRW_' . str_replace(' ', '_', $qrCode->label) . '_' . str_replace(' ', '_', $qrCode->paymentPlatform->platform_name) . '.png';

        return response()->download($path, $filename);
    }
}