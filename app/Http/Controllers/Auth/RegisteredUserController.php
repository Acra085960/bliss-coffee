<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwilioService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'verification_method' => ['required', 'in:email,whatsapp'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Format phone number
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'role' => 'pembeli',
        ]);

        // Assign role using Spatie Permission
        $user->assignRole('pembeli');

        // Handle verification based on chosen method
        if ($request->verification_method === 'whatsapp') {
            // Check if Twilio is properly configured
            $twilioSid = config('services.twilio.account_sid');
            $twilioToken = config('services.twilio.auth_token');
            
            if ($twilioSid === 'your_twilio_account_sid_here' || $twilioToken === 'your_twilio_auth_token_here' || 
                empty($twilioSid) || empty($twilioToken)) {
                // Twilio not configured, go directly to phone verification page for demo
                session(['phone_verification_user_id' => $user->id]);
                session(['demo_mode' => true]); // Flag for demo mode
                return redirect()->route('phone.verification.show')
                    ->with('info', 'Demo mode: Enter any 6-digit code to continue (Twilio not configured)');
            }
            
            // Send WhatsApp verification
            try {
                $twilioService = app(TwilioService::class);
                $result = $twilioService->sendWhatsAppVerification($phone);
                
                if ($result['success']) {
                    session(['phone_verification_user_id' => $user->id]);
                    return redirect()->route('phone.verification.show')
                        ->with('success', 'WhatsApp verification code sent to your phone!');
                } else {
                    // Fallback to email if WhatsApp fails
                    event(new Registered($user));
                    return redirect()->route('verification.notice')
                        ->with('warning', 'WhatsApp verification failed. Email verification sent instead.');
                }
            } catch (\Exception $e) {
                // Log the error for debugging
                \Log::error('Twilio WhatsApp verification failed: ' . $e->getMessage());
                
                // Fallback to email verification
                event(new Registered($user));
                return redirect()->route('verification.notice')
                    ->with('warning', 'WhatsApp verification unavailable. Email verification sent.');
            }
        } else {
            // Email verification
            event(new Registered($user));
            return redirect()->route('verification.notice')
                ->with('success', 'Registration successful! Please check your email for verification.');
        }
    }
}
