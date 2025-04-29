<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

require_once APPPATH.'third_party/twilio-php-main/src/Twilio/autoload.php';
use Twilio\Rest\Client;

class Twilio {
    protected $client;
    
    public function __construct() {
        $CI =& get_instance();
        $CI->config->load('twilio');
        
        $this->client = new Client(
            $CI->config->item('twilio_account_sid'),
            $CI->config->item('twilio_auth_token')
        );
    }
    
    public function sendSMS($to, $from, $body) {
        return $this->client->messages->create(
            $to,
            [
                'from' => $from,
                'body' => $body
            ]
        );
    }
}