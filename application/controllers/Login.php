<?php
// if (!defined('BASEPATH'))
//     exit('No direct script access allowed');

//     use Illuminate\Http\Request;
//     use Twilio\Rest\Client;
    
// class Login extends Home_Core_Controller{

//     function __construct(){
//         parent::__construct();
//         /*cache control*/
//         $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
//         $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
//         $this->output->set_header('Pragma: no-cache');
//         $this->load->library('session');
//         $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
//     }
	
//     public function index(){
//         redirect(base_url() . 'user/login', 'refresh');
        
//     }
    
// 	function ajax_login(){
// 		$response = array();
		
// 		$username 						= $_POST["username"];
// 		$password 						= md5($_POST["password"]);
// 		$response['submitted_data'] 	= $_POST;		
		
// 		//Validating login
// 		$login_status 					= $this->validate_login( $username ,  $password);
// 		$response['login_status'] 		= $login_status;
// 		if ($login_status == 'success') {
//             if($this->session->userdata('login_type') == 'admin'){
//                 $response['redirect_url']   = base_url().'admin';
//             } else{
//                 $response['redirect_url']   = base_url('user/profile');
//             }
			
// 		}		
// 		echo json_encode($response);
// 	}

//     function do_login($param1='', $param2=''){
        
//         $username  = $this->input->post('username');
//         $password  = $this->input->post('password');              

//         //Validating login
//         $login_status                   = $this->validate_login( $username ,  $password);        
//         if ($login_status == 'success') {
//             if($this->session->userdata('login_type') == 'admin'){
//                 $response['redirect_url']   = base_url().'admin';
//             }else{
//                 $response['redirect_url']   = base_url();
//             }
            
//         }
//     }

//     public function ajax_send_otp() {
//         header('Content-Type: application/json');
        
//         require_once '/Users/chinmay/Downloads/ovoo340nulled/twilio-php-main/src/Twilio/autoload.php';
        
//         $response = ['status' => 'error', 'message' => ''];
//         $this->load->library('session');
        
//         try {
//             // Input validation
//             $phone = preg_replace('/[^0-9]/', '', $this->input->post('phone'));
//             if (empty($phone) || strlen($phone) !== 10) {
//                 throw new Exception('Valid 10-digit phone number required');
//             }
            
//             $user = $this->db->get_where('user', ['phone' => $phone])->row();
//             if (!$user) {
//                 throw new Exception('Phone number not registered');
//             }
    
//             $otp = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
//             $this->session->unset_userdata(['otp_code', 'otp_phone', 'otp_time']);
//             $this->session->set_userdata([

//                  'otp_code' => (string)$otp,
//                  'otp_phone' => (string)preg_replace('/[^0-9]/', '', $phone),
//                  'otp_time' => time()
//             ]);
            
//             log_message('debug', "OTP set in session: $otp for phone: $phone");
//             session_write_close();
    
//             $sid = "AC51170ae350f0401156099878fbad627f";
//             $token = "6587f76a548d45d55159227d4edd457b";
//             $fromNumber = "+18647408453"; 
//             $toNumber = '+91' . $phone; 
            
//             if ($fromNumber === $toNumber) {
//                 throw new Exception('Cannot send to same number as sender');
//             }
    
//             $twilio = new \Twilio\Rest\Client($sid, $token);
            
//             $message = $twilio->messages->create(
//                 $toNumber, 
//                 [
//                     'from' => $fromNumber, 
//                     'body' => "Your OVVO Movie App verification code is: $otp\n\nValid for 10 minutes. Do not share this code with anyone."
//                 ]
//             );
    
//             $response = [
//                 'status' => 'sent',
//                 'message' => 'OTP sent successfully',
//                 'debug' => [
//                     'from' => $fromNumber,
//                     'to' => $toNumber,
//                     'twilio_status' => $message->status
//                 ]
//             ];
    
//         } catch (Exception $e) {
//             $response['message'] = 'Failed to send OTP. Please try again.';
//             $response['debug_error'] = $e->getMessage();
//             error_log('OTP Error: '.$e->getMessage());
//         }
    
//         echo json_encode($response);
//         exit;
//     }

    // public function verify_otp_login() {
    //     header('Content-Type: application/json');
    //     $response = [
    //         'status' => 'error',
    //         'message' => 'Unknown error occurred',
    //         'redirect_url' => base_url('user/profile')
    //     ];
        
    //     try {
    //         $phone = preg_replace('/[^0-9]/', '', $this->input->post('phone', true));
    //         $otp = trim($this->input->post('otp', true));
            
    //         error_log("Received - OTP: $otp | Phone: $phone");
    
    //         $expected_otp = $otp;
    
    //         if ($otp !== $expected_otp) {
    //             $response['message'] = 'Incorrect OTP.';
    //             echo json_encode($response);
    //             return;
    //         }
    
           
    //         $user = $this->db->get_where('user', ['phone' => $phone])->row();
    //         if (!$user) {
    //             $response['message'] = 'User account not found';
    //             echo json_encode($response);
    //             return;
    //         }
    //         if ($user->role == 'admin') {
    //             $this->session->set_userdata('admin_is_login', '1');
    //             $response['redirect_url'] = base_url('admin');
    //         } else {
    //             $this->session->set_userdata('user_is_login', '1');
    //             $response['redirect_url'] = base_url('user/profile');
    //         }
    
    //         // Success response
    //         $response['status'] = 'success';
    //         $response['message'] = 'OTP verified and login successful!';
    
    //     } catch (Exception $e) {
    //         $response['message'] = 'A system error occurred';
    //         error_log('OTP Verification Error: ' . $e->getMessage());
    //     }
    
    //     echo json_encode($response);
    // }
    
//     function validate_login($username	=	'' , $password	 =  ''){	
//         $credential	=	array(	'username' => $username , 'password' => $password );
//         $query = $this->db->get_where('user' , $credential);
//         if ($query->num_rows() > 0) {
//             $this->session->set_userdata('login_status', '1');
//             $row = $query->row();
//             // Replace the company name                     
//             $this->db->where('user_id', $row->user_id);
//             $this->db->update('user', array(
//                 'last_login' => date('Y-m-d H:i:s')
//             )); 
//             if($row->role=='admin'){
// 			  $this->session->set_userdata('admin_is_login', '1');			  	
// 			  $this->session->set_userdata('user_id', $row->user_id);
// 			  $this->session->set_userdata('name', $row->name);
// 			  $this->session->set_userdata('username', $row->username);
// 			  $this->session->set_userdata('login_type', 'admin');
// 			}
// 			if($row->role=='subscriber'){
// 			  $this->session->set_userdata('user_is_login', '1');			  	
// 			  $this->session->set_userdata('user_id', $row->user_id);
// 			  $this->session->set_userdata('name', $row->name);
// 			  $this->session->set_userdata('username', $row->username);
// 			  $this->session->set_userdata('login_type', 'subscriber');
// 			}
// 			if($row->role=='gate_man'){
// 			  $this->session->set_userdata('gate_man_is_login', '1');			  	
// 			  $this->session->set_userdata('user_id', $row->user_id);
// 			  $this->session->set_userdata('name', $row->name);
// 			  $this->session->set_userdata('username', $row->username);
// 			  $this->session->set_userdata('login_type', 'gate_man');
// 			}
// 			  return 'success';
// 		}
		
// 		return 'invalid';		
//     }

//     function logout() {
//         $this->session->unset_userdata('');
//         $this->session->sess_destroy();
//         $this->session->set_flashdata('logout_notification', 'logged_out');
//         redirect(base_url() , 'refresh');
//     }

//     function signup($param1='', $param2='')  {
//         if ($param1 == 'do_signup') {
//             $username               = $this->input->post('username');
//             $email                  = $this->input->post('email');
//             $password               = $this->input->post('password');

//             $data['name']           = 'User';
//             $data['email']          = $email;
//             $data['username']       = $username;
//             $data['password']       = md5($password );
//             $data['role']           = 'subscriber';
//             $user_exist             = $this->common_model->check_email_username($username,$email);
//             if($user_exist){
//                 $this->session->set_flashdata('error', 'Signup fail.username or email is already exist on system');
                
//             }else{
//                 $data['join_date']       = date('Y-m-d H:i:s');
//                 $data['last_login']       = date('Y-m-d H:i:s');
//                 $this->db->insert('user', $data);
//                 $this->load->model('email_model');
//                 $this->email_model->account_opening_email($username, $email, $password);
//                 $this->session->set_flashdata('success', 'Signup successfully.now you can login to system');
//                 $response['login_status']       = $login_status;
//                 redirect(base_url() . 'login', 'refresh');
//             }     
            
            
//         }      
        
//             $data['page_name']      = 'signup';
//             $data['page_title']     = 'Join with us ';            
//             $this->load->view('signup', $data);

//     }

//     function ajax_signup()  {
//         $response = array();    
//         //Ajax username and password request
//         $username                       = $_POST["username"];
//         $email                          = $_POST["email"];
//         $password                       = $_POST["password"];
//         $response['submitted_data']     = $_POST;

//         $data['name']                   = 'User';
//         $data['email']                  = $email;
//         $data['username']               = $username;
//         $data['password']               = md5($password);
//         $data['role']                   = 'subscriber';
//         if($username !='' && $email != '' && $_POST["password"] !='' && $username !=NULL && $email != NULL && $_POST["password"] !=NULL)
//         {
//             $user_exist                     = $this->common_model->check_email_username($username,$email);
//             if($user_exist){
//                 $response['signup_status']  = 'user_exist';            
//             }else{
//                 $data['join_date']          = date('Y-m-d H:i:s');
//                 $data['last_login']         = date('Y-m-d H:i:s');
//                 $this->db->insert('user', $data);
//                 $this->load->model('email_model');
//                 //$this->email_model->account_opening_email($username, $email, $password);
//                 $this->session->set_flashdata('success', 'Signup successfully.now you can login to system');
//                 $response['redirect_url']   = base_url('login');
//                 $response['signup_status']  = 'success';
//             }
//         }
//         else{
//             $response['signup_status']  = 'empty_input';
//         }
//         echo json_encode($response);

//     }


//     function forget_password($param1='', $param2='') {
//         if ($param1 == 'do_reset') {           
//             $email                  = $this->input->post('email');            
//             $user_exist             = $this->common_model->check_email($email);
//             //var_dump($user_exist , $email);
//             if($user_exist){                
//                 $data['token'] = bin2hex(openssl_random_pseudo_bytes(16));
//                 $this->db->where('email',$email);
//                 $this->db->update('user',$data);
//                 $this->session->set_flashdata('success', 'Please Check Your Email to Complete Password Reset.');
//                 redirect(base_url() . 'login', 'refresh');                
//             }else{
//             $this->session->set_flashdata('error', 'Email not found on our system');            
//             redirect(base_url() . 'login', 'refresh');
//             }    
            
            
//         }
//         redirect(base_url() . 'login', 'refresh');

//     }

//     function complete_reset($param1='', $param2='') {
//         if ($param1 == 'save') {
//             $token                      = $this->input->post('token');
//             $password                   = $this->input->post('password');
//             $password2                  = $this->input->post('password2');
//             if($token !='' && $password !='' && $password2 !='' && $password==$password2){
//                 $data['token']      = '';
//                 $data['password']   = md5($password);
//                 $this->db->where('token', $token);
//                 $this->db->update('user', $data);
//                 $this->session->set_flashdata('success', 'Password Changed');
//                 redirect(base_url() . 'login', 'refresh');
//             }

//         }
//             $token                  = $this->input->get('token');
//             if(isset($token) && $token !=''){
//                 $token_exist             = $this->common_model->check_token($token);
//                 if($token_exist){                               
//                 $data['token'] = $token;
//                 $data['page_title']     = 'New Password';            
//                 $this->load->view('new_password', $data);
//                 }else{
//                 $this->session->set_flashdata('error', 'Invalid token..');
//                 redirect(base_url() . 'login/forget_password', 'refresh');
//                 }
//             }else{
//                 $this->session->set_flashdata('error', 'Invalid token..');
//                 redirect(base_url() . 'login/forget_password', 'refresh');
//             }
//             //$this->session->set_flashdata('error', 'Invalid token..');
//             //redirect(base_url() . 'login/forget_password', 'refresh');
            
//         }

//     function subscribe(){
//         $response = array();        
//         //Ajax database name,username and password request
//         $email                   = $_POST["email"];
//         $name                   = $_POST["name"];       
//         $response['submitted_data'] = $_POST;
//         $subscribe_status = $this->add_subscriber($name,$email);
//         $response['subscribe_status'] = $subscribe_status; 
        
//         //Replying ajax request with validation response
//         echo json_encode($response);
//     }

//     function add_subscriber($name="", $email=""){
//     $query = $this->db->get_where('subscriber' , array('email' => $email));
//         if ($query->num_rows() < 1) {
//             $data['name']    = $name;
//             $data['email']    = $email;
//             $data['subscribe_at']    = date('Y-m-d H:i:s');            
//             $this->db->insert('subscriber', $data);
//             $this->load->model('email_model');
//             if($this->email_model->send_confirmation_to_subscriber($email)){
//             return 'success';
//             }else{
//                return 'error'; 
//             }
//         }
//         else if ($query->num_rows() > 0) {
//             return 'exist';
//         }
//         else{
//             return 'error';
//         }
//     }
// }


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class Login extends Home_Core_Controller {

    function __construct() {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->load->library('session');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }
    
    //Default function, redirects to logged in user area
    public function index() {
        redirect(base_url() . 'user/login', 'refresh');
    }

    // public function ajax_send_otp() {
    //     header('Content-Type: application/json');
        
    //     require_once '/Users/chinmay/Downloads/ovoo340nulled/twilio-php-main/src/Twilio/autoload.php';
        
    //     $response = ['status' => 'error', 'message' => ''];
    //     $this->load->library('session');
        
    //     try {
    //         $phone = preg_replace('/[^0-9]/', '', $this->input->post('phone'));
    //         if (empty($phone) || strlen($phone) !== 10) {
    //             throw new Exception('Valid 10-digit phone number required');
    //         }
            
    //         $user = $this->db->get_where('user', ['phone' => $phone])->row();
    //         if (!$user) {
    //             throw new Exception('Phone number not registered. Please sign up first.');
    //         }
    
    //         $otp = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
    //         $this->session->unset_userdata(['otp_code', 'otp_phone', 'otp_time']);
    //         $this->session->set_userdata([
    //              'otp_code' => (string)$otp,
    //              'otp_phone' => (string)preg_replace('/[^0-9]/', '', $phone),
    //              'otp_time' => time()
    //         ]);
            
    //         log_message('debug', "OTP set in session: $otp for phone: $phone");
    //         session_write_close();
    
    //         $sid = "AC51170ae350f0401156099878fbad627f";
    //         $token = "6587f76a548d45d55159227d4edd457b";
    //         $fromNumber = "+18647408453"; 
    //         $toNumber = '+91' . $phone; 
            
    //         if ($fromNumber === $toNumber) {
    //             throw new Exception('Cannot send to same number as sender');
    //         }
    
    //         $twilio = new \Twilio\Rest\Client($sid, $token);
            
    //         $message = $twilio->messages->create(
    //             $toNumber, 
    //             [
    //                 'from' => $fromNumber, 
    //                 'body' => "Your OVVO Movie App verification code is: $otp\n\nValid for 10 minutes. Do not share this code with anyone."
    //             ]
    //         );
    
    //         $response = [
    //             'status' => 'sent',
    //             'message' => 'OTP sent successfully',
    //             'debug' => [
    //                 'from' => $fromNumber,
    //                 'to' => $toNumber,
    //                 'twilio_status' => $message->status
    //             ]
    //         ];
    
    //     } catch (Exception $e) {
    //         $response['message'] = $e->getMessage();
    //         error_log('OTP Error: '.$e->getMessage());
    //     }
    
    //     echo json_encode($response);
    //     exit;
    // }

    
    public function ajax_send_otp() {
        header('Content-Type: application/json');
    
        require_once '/Users/chinmay/Downloads/ovoo340nulled/twilio-php-main/src/Twilio/autoload.php';
    
        $response = ['status' => 'error', 'message' => ''];
        $this->load->library('session');
    
        try {
            // Try reading JSON input
            $input_json = file_get_contents('php://input');
            $input_data = json_decode($input_json, true);
    
            // Log raw input for debugging
            log_message('debug', 'Raw JSON input: ' . $input_json);
    
            // Fallback to POST if JSON fails
            $phone = '';
            if (is_array($input_data) && isset($input_data['phone'])) {
                $phone = $input_data['phone'];
            } else {
                $phone = $this->input->post('phone'); // fallback if sent as form-data
            }
    
            $phone = preg_replace('/[^0-9]/', '', $phone);
    
            if (empty($phone) || strlen($phone) !== 10) {
                throw new Exception('Valid 10-digit phone number required');
            }
    
            $user = $this->db->get_where('user', ['phone' => $phone])->row();
            if (!$user) {
                throw new Exception('Phone number not registered. Please sign up first.');
            }
    
            // Generate OTP
            $otp = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    
            // Save OTP in session
            $this->session->unset_userdata(['otp_code', 'otp_phone', 'otp_time']);
            $this->session->set_userdata([
                'otp_code' => $otp,
                'otp_phone' => $phone,
                'otp_time' => time()
            ]);
    
            session_write_close();
    
            // Twilio SMS
            $sid = "AC51170ae350f0401156099878fbad627f";
            $token = "6587f76a548d45d55159227d4edd457b";
            $fromNumber = "+18647408453";
            $toNumber = '+91' . $phone;
    
            if ($fromNumber === $toNumber) {
                throw new Exception('Cannot send to same number as sender');
            }
    
            $twilio = new \Twilio\Rest\Client($sid, $token);
            $message = $twilio->messages->create(
                $toNumber,
                [
                    'from' => $fromNumber,
                    'body' => "Your OVVO Movie App verification code is: $otp\n\nValid for 10 minutes. Do not share this code with anyone."
                ]
            );
    
            $response = [
                'status' => 'sent',
                'message' => 'OTP sent successfully',
                'debug' => [
                    'from' => $fromNumber,
                    'to' => $toNumber,
                    'twilio_status' => $message->status,
                    'otp' => $otp // just for testing; remove in production
                ]
            ];
    
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            log_message('error', 'OTP Send Error: ' . $e->getMessage());
        }
    
        echo json_encode($response);
        exit;
    }
    public function verify_otp_login() {
        header('Content-Type: application/json');
        $response = [
            'status' => 'error',
            'message' => 'Unknown error occurred',
            'redirect_url' => base_url('user/profile')
        ];
        
        try {
            $phone = preg_replace('/[^0-9]/', '', $this->input->post('phone', true));
            $otp = trim($this->input->post('otp', true));
            
            error_log("Received - OTP: $otp | Phone: $phone");
    
            $session_otp = $this->session->userdata('otp_code');
            $session_phone = $this->session->userdata('otp_phone');
            
            if ($otp !== $session_otp || $phone !== $session_phone) {
                $response['message'] = 'Incorrect OTP or phone number mismatch.';
                echo json_encode($response);
                return;
            }
    
            $otp_time = $this->session->userdata('otp_time');
            if (time() - $otp_time > 600) {
                $response['message'] = 'OTP has expired. Please request a new one.';
                echo json_encode($response);
                return;
            }
    
            $user = $this->db->get_where('user', ['phone' => $phone])->row();
            if (!$user) {
                $response['message'] = 'User account not found';
                echo json_encode($response);
                return;
            }
            
            $this->session->set_userdata([
                'user_id' => $user->user_id,
                'name' => $user->name,
                'phone' => $user->phone,
                'login_type' => $user->role,
                'login_status' => '1',
                'last_login' => date('Y-m-d H:i:s')
            ]);
            
            if ($user->role == 'admin') {
                $this->session->set_userdata('admin_is_login', '1');
                $response['redirect_url'] = base_url('admin');
            } else {
                $this->session->set_userdata('user_is_login', '1');
                $response['redirect_url'] = base_url('user/profile');
            }
    
            $this->session->unset_userdata(['otp_code', 'otp_phone', 'otp_time']);
    
            $this->db->where('user_id', $user->user_id);
            $this->db->update('user', ['last_login' => date('Y-m-d H:i:s')]);
    
            // Success response
            $response['status'] = 'success';
            $response['message'] = 'OTP verified and login successful!';
    
        } catch (Exception $e) {
            $response['message'] = 'A system error occurred: ' . $e->getMessage();
            error_log('OTP Verification Error: ' . $e->getMessage());
        }
    
        echo json_encode($response);
    }
    
    function logout() {
        $this->session->unset_userdata('');
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(base_url(), 'refresh');
    }

    function ajax_signup() {
        header('Content-Type: application/json');
        $response = array();    
        
        $name = $this->input->post('name', true);
        $phone = preg_replace('/[^0-9]/', '', $this->input->post('phone', true));
        
        $response['submitted_data'] = $_POST;

        if(empty($name) || empty($phone) || strlen($phone) !== 10) {
            $response['signup_status'] = 'invalid_input';
            $response['message'] = 'Please provide valid name and 10-digit phone number';
            echo json_encode($response);
            return;
        }

        $user_exist = $this->db->get_where('user', ['phone' => $phone])->row();
        if($user_exist) {
            $response['signup_status'] = 'user_exist'; 
            $response['message'] = 'Phone number already registered';           
        } else {
            $data = [
                'name' => $name,
                'phone' => $phone,
                'role' => 'subscriber',
                'join_date' => date('Y-m-d H:i:s'),
                'last_login' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('user', $data);
            $user_id = $this->db->insert_id();
            
            $this->session->set_userdata([
                'user_id' => $user_id,
                'name' => $name,
                'phone' => $phone,
                'login_type' => 'subscriber',
                'user_is_login' => '1',
                'login_status' => '1'
            ]);
            
            $response['redirect_url'] = base_url('user/profile');
            $response['signup_status'] = 'success';
            $response['message'] = 'Registration successful. You are now logged in.';
        }

        echo json_encode($response);
    }
}
