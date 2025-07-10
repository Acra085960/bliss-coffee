<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use App\Models\PhoneVerificationCode;

class TwilioService
{
    protected $twilio;
    protected $fromPhone;
    protected $fromWhatsApp;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.account_sid'),
            config('services.twilio.auth_token')
        );
        $this->fromPhone = config('services.twilio.from_phone');
        $this->fromWhatsApp = config('services.twilio.from_whatsapp');
    }

    /**
     * Send WhatsApp verification code
     */
    public function sendWhatsAppVerification($phone)
    {
        try {
            // Generate 6-digit verification code
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Format phone number
            $formattedPhone = $this->formatPhoneNumber($phone);
            
            // Send WhatsApp message
            $message = $this->twilio->messages->create(
                'whatsapp:' . $formattedPhone,
                [
                    'from' => $this->fromWhatsApp,
                    'body' => "Your Bliss Coffee verification code is: {$code}\n\nThis code will expire in 10 minutes."
                ]
            );

            // Store verification code in database or cache
            $this->storeVerificationCode($formattedPhone, $code);

            return [
                'success' => true,
                'message' => 'WhatsApp verification code sent successfully',
                'message_sid' => $message->sid
            ];

        } catch (TwilioException $e) {
            return [
                'success' => false,
                'message' => 'Failed to send WhatsApp message: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send SMS verification code
     */
    public function sendSMSVerification($phone)
    {
        try {
            // Generate 6-digit verification code
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Format phone number
            $formattedPhone = $this->formatPhoneNumber($phone);
            
            // Send SMS
            $message = $this->twilio->messages->create(
                $formattedPhone,
                [
                    'from' => $this->fromPhone,
                    'body' => "Your Bliss Coffee verification code is: {$code}\n\nThis code will expire in 10 minutes."
                ]
            );

            // Store verification code
            $this->storeVerificationCode($formattedPhone, $code);

            return [
                'success' => true,
                'message' => 'SMS verification code sent successfully',
                'message_sid' => $message->sid
            ];

        } catch (TwilioException $e) {
            return [
                'success' => false,
                'message' => 'Failed to send SMS: ' . $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify the submitted code
     */
    public function verifyCode($phone, $code)
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($phone);
            
            // Get stored verification code
            $storedCode = $this->getStoredVerificationCode($formattedPhone);
            
            if (!$storedCode) {
                return [
                    'success' => false,
                    'message' => 'Verification code not found or expired'
                ];
            }

            if ($storedCode['code'] === $code) {
                // Mark code as used
                $this->markCodeAsUsed($formattedPhone, $code);
                
                return [
                    'success' => true,
                    'message' => 'Phone verified successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Invalid verification code'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to international format
     */
    public function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert Indonesian number to international format
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return '+' . $phone;
    }

    /**
     * Store verification code in database
     */
    private function storeVerificationCode($phone, $code)
    {
        // Delete any existing codes for this phone
        PhoneVerificationCode::where('phone', $phone)->delete();
        
        // Store new code
        PhoneVerificationCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
            'used' => false
        ]);
    }

    /**
     * Get stored verification code
     */
    private function getStoredVerificationCode($phone)
    {
        $record = PhoneVerificationCode::where('phone', $phone)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        return $record ? ['code' => $record->code, 'id' => $record->id] : null;
    }

    /**
     * Mark verification code as used
     */
    private function markCodeAsUsed($phone, $code)
    {
        PhoneVerificationCode::where('phone', $phone)
            ->where('code', $code)
            ->update(['used' => true]);
    }
}