<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Messaging;

class Firebase {
    protected $messaging;

    public function __construct() {
        $factory = (new Factory)
            ->withServiceAccount(APPPATH.'../firebase/ovoo-9f385-firebase-adminsdk.json');
        
        $this->messaging = $factory->createMessaging();
    }

    public function sendOtpNotification($token, $otp) {
        $message = [
            'token' => $token,
            'notification' => [
                'title' => 'Your OTP Code',
                'body' => 'Your verification code is: '.$otp
            ],
            'data' => [
                'otp' => $otp,
                'type' => 'otp_verification'
            ]
        ];

        return $this->messaging->send($message);
    }
}