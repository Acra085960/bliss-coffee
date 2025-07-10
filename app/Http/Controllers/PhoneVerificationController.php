<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PhoneVerificationCode;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PhoneVerificationController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    /**
     * Show phone verification form
     */
    public function show()
    {
        // Check if user has session for phone verification
        if (!session('phone_verification_user_id')) {
            return redirect()->route('login')
                ->with('error', 'Phone verification session expired. Please register again.');
        }

        return view('auth.verify-phone');
    }

    /**
     * Send WhatsApp verification code
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10',
        ]);

        // Format phone number
        $phone = $this->formatPhoneNumber($request->phone);

        // Send verification code
        $result = $this->twilioService->sendWhatsAppVerification($phone);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Verification code sent successfully!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to send verification code'
            ], 400);
        }
    }

    /**
     * Verify the phone verification code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        $userId = session('phone_verification_user_id');
        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Verification session expired. Please register again.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found. Please register again.');
        }

        // Check if in demo mode (Twilio not configured)
        if (session('demo_mode')) {
            // In demo mode, accept any 6-digit code
            $code = $request->verification_code;
            if (strlen($code) === 6 && is_numeric($code)) {
                // Mark phone as verified
                $user->update([
                    'phone_verified_at' => now(),
                ]);

                // Clear sessions
                session()->forget(['phone_verification_user_id', 'demo_mode']);

                // Log the user in
                Auth::login($user);

                return redirect()->route('customer.dashboard')
                    ->with('success', 'Phone verified successfully! Welcome to Bliss Coffee!');
            } else {
                return back()->with('error', 'Please enter a valid 6-digit code');
            }
        }

        // Normal mode - verify with Twilio
        $result = $this->twilioService->verifyCode($user->phone, $request->verification_code);

        if ($result['success']) {
            // Mark phone as verified
            $user->update([
                'phone_verified_at' => now(),
            ]);

            // Clear session
            session()->forget('phone_verification_user_id');

            // Log the user in
            Auth::login($user);

            return redirect()->route('customer.dashboard')
                ->with('success', 'Phone verified successfully! Welcome to Bliss Coffee!');
        } else {
            return back()->with('error', $result['message'] ?? 'Invalid verification code');
        }
    }

    /**
     * Resend verification code
     */
    public function resend(Request $request)
    {
        $userId = session('phone_verification_user_id');
        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Verification session expired. Please register again.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found. Please register again.');
        }

        // Check if in demo mode
        if (session('demo_mode')) {
            return back()->with('info', 'Demo mode: Enter any 6-digit code to continue');
        }

        // Send new verification code
        $result = $this->twilioService->sendWhatsAppVerification($user->phone);

        if ($result['success']) {
            return back()->with('success', 'New verification code sent to your WhatsApp!');
        } else {
            return back()->with('error', $result['message'] ?? 'Failed to resend verification code');
        }
    }

    /**
     * Format phone number to international format
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert to international format
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return '+' . $phone;
    }
}
