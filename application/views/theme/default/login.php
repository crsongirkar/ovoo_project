<?php 
// Get configuration values with null checks
$g_login_enable = $this->db->get_where('config', ['title' => 'google_login_enable'])->row();
$g_login_enable = ($g_login_enable) ? $g_login_enable->value : '0';

$f_login_enable = $this->db->get_where('config', ['title' => 'facebook_login_enable'])->row();
$f_login_enable = ($f_login_enable) ? $f_login_enable->value : '0';

$registration_enable = $this->db->get_where('config', ['title' => 'registration_enable'])->row();
$registration_enable = ($registration_enable) ? $registration_enable->value : '0';

$recaptcha_enable = $this->db->get_where('config', ['title' => 'recaptcha_enable'])->row();
$recaptcha_enable = ($recaptcha_enable) ? $recaptcha_enable->value : '0';
?>
<div id="section-opt" style="padding-top: 75px;">
    <div class="container">
        <div class="row">
            <div class="<?php if($registration_enable != '1'){ echo "col-md-offset-3";} ?> col-md-6 col-xs-12">
                <h2 class="block-title text-center"><?php echo trans('login'); ?><br>
                    <small><?php echo trans('login_with_phone_number'); ?></small>
                </h2>
                <div class="sendus">
                    <?php if($this->session->flashdata('login_success') !=''):?>
                        <div class="alert alert-success">
                           <?php echo $this->session->flashdata('login_success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('login_error') !=''):?>
                        <div class="alert alert-danger">
                          <?php echo $this->session->flashdata('login_error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($g_login_enable == '1' || $f_login_enable == '1'): ?>
                    <div class="movie-heading m-b-20"> 
                        <span><?php echo trans('connect_with_social_profile'); ?></span>
                        <div class="disable-bottom-line"></div>
                    </div><br>                    
                    <?php if($g_login_enable == '1'): ?>
                    <a class="btn btn-gplus btn-sm" href="<?php echo $login_url; ?>"> 
                        <span class="btn-label"><i class="fa fa-google-plus"></i></span>
                        <?php echo trans('connect_with_google'); ?>
                    </a>
                    <?php endif; ?>
                    <?php if($f_login_enable == '1'): ?>
                    <a class="btn btn-fb btn-sm" href="<?php echo $facebook_login_url; ?>"> 
                        <span class="btn-label"><i class="fa fa-facebook"></i></span>
                        <?php echo trans('connect_with_facebook'); ?>
                    </a>
                    <?php endif; ?>
                    <br><br>
                    <div class="text-center">
                        <p><?php echo trans('or_login_with_phone'); ?></p>
                    </div>
                    <br>
                    <?php endif; ?>
                    
                    <!-- Phone Login Form -->
                    <div class="movie-heading m-b-20"> 
                        <span><?php echo trans('enter_your_phone_number'); ?></span>
                        <div class="disable-bottom-line"></div>
                    </div>
                    <div id="contact-form">
                        <form id="phone-login-form">
                            <div class="form-group">
                                <input type="tel" id="phone_number" class="form-control" 
                                    placeholder="<?php echo trans('10_digit_phone_number'); ?>" 
                                    required pattern="[0-9]{10}" maxlength="10">
                                <small id="phoneError" class="text-danger"></small>
                            </div>
                            <button type="button" id="sendOtpBtn" class="btn btn-success btn-block">
                                <i class="fa fa-mobile"></i> <?php echo trans('send_otp'); ?>
                            </button>
                        </form>
                    </div>
                </div>          
            </div>
            
            <?php if($registration_enable == '1'): ?>
            <div class="col-md-6 col-xs-12">
                <h2 class="block-title text-center"><?php echo trans('signup_to_join'); ?><br>
                    <small><?php echo trans('signup_with_phone_number'); ?></small>
                </h2>

                <div class="sendus">
                    <div class="movie-heading m-b-20"> 
                        <span><?php echo trans('enter_your_details'); ?></span>
                        <div class="disable-bottom-line"></div>
                    </div>
                    <?php if($this->session->flashdata('sign_up_success') !=''):?>
                        <div class="alert alert-success">
                           <?php echo $this->session->flashdata('sign_up_success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('sign_up_error') !=''):?>
                        <div class="alert alert-danger">
                          <?php echo $this->session->flashdata('sign_up_error'); ?>
                        </div>
                    <?php endif; ?>
                    <div id="contact-form">
                        <div class="expMessage"></div>
                        <form id="mobile-signup-form">
                            <div class="form-group">
                                <input type="text" name="name" id="signup_name" class="form-control" 
                                    placeholder="<?php echo trans('full_name'); ?>" required minlength="3">
                            </div>
                            <div class="form-group">
                                <input type="tel" name="phone" id="signup_phone" class="form-control" 
                                    placeholder="<?php echo trans('10_digit_phone_number'); ?>" 
                                    required pattern="[0-9]{10}" maxlength="10">
                                <small id="signupPhoneError" class="text-danger"></small>
                            </div>
                            <?php if($recaptcha_enable == '1'): ?>
                                <div class="form-group">
                                    <?php echo $this->recaptcha->create_box(); ?>
                                </div>
                            <?php endif; ?>
                            <button type="button" id="signupBtn" class="btn btn-success btn-block">
                                <i class="fa fa-user-plus"></i> <?php echo trans('signup'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- OTP Verification Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="otpModalLabel"><?php echo trans('verify_otp'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo trans('otp_sent_to_phone'); ?>: <strong id="displayPhone"></strong></p>
                <div class="form-group">
                    <input type="text" id="otpInput" class="form-control" 
                        placeholder="<?php echo trans('enter_otp'); ?>" maxlength="6" required>
                    <small id="otpError" class="text-danger"></small>
                </div>
                <div id="otpTimer" class="text-center mb-3"></div>
                <div id="debugOtp" class="text-center text-muted small" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <?php echo trans('cancel'); ?>
                </button>
                <button type="button" id="verifyOtpBtn" class="btn btn-primary">
                    <?php echo trans('verify'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var base_url = "<?php echo base_url(); ?>";
    var otpTimer;
    var timeLeft = 300; // 5 minutes
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeTo(500, 0).slideUp(500, function() {
            $(this).remove(); 
        });
    }, 5000);

    // Send OTP button click handler
    $('#sendOtpBtn').click(function() {
        let phone = $('#phone_number').val().trim();
        $('#phoneError').text('').removeClass('text-danger text-success');
        
        // Validate phone number
        if (!phone || !/^[0-9]{10}$/.test(phone)) {
            $('#phoneError').addClass('text-danger').text('<?php echo trans("valid_10_digit_phone_required"); ?>');
            return;
        }

        // Disable button and show loading
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo trans("sending"); ?>...');
        
        // Send AJAX request
        $.ajax({
            url: base_url + 'login/ajax_send_otp',
            method: 'POST',
            data: { phone: phone },
            dataType: 'json',
            success: function(response) {
                console.log('OTP Response:', response);
                if (response.status === 'sent' || response.status === 'success') {
                    $('#displayPhone').text('+91 ' + phone);
                    $('#otpModal').modal('show');
                    startOtpTimer();
                    $('#phoneError').addClass('text-success').text('<?php echo trans("otp_sent_successfully"); ?>');
                    
                    // Show debug OTP in development mode
                    if (response.debug && response.debug.otp) {
                        $('#debugOtp').text('DEBUG OTP: ' + response.debug.otp).show();
                    }
                } else {
                    $('#phoneError').addClass('text-danger').text(response.message || '<?php echo trans("failed_to_send_otp"); ?>');
                }
            },
            error: function(xhr) {
                $('#phoneError').addClass('text-danger').text('<?php echo trans("server_error_try_again"); ?>');
                console.error('Error:', xhr.responseText);
            },
            complete: function() {
                $('#sendOtpBtn').prop('disabled', false).html('<i class="fa fa-mobile"></i> <?php echo trans("send_otp"); ?>');
            }
        });
    });

    // Signup button click handler
    $('#signupBtn').click(function() {
        let name = $('#signup_name').val().trim();
        let phone = $('#signup_phone').val().trim();
        $('#signupPhoneError').text('').removeClass('text-danger text-success');
        
        // Validate name
        if (!name || name.length < 3) {
            $('#signupPhoneError').addClass('text-danger').text('<?php echo trans("valid_name_required"); ?>');
            return;
        }
        
        // Validate phone
        if (!phone || !/^[0-9]{10}$/.test(phone)) {
            $('#signupPhoneError').addClass('text-danger').text('<?php echo trans("valid_10_digit_phone_required"); ?>');
            return;
        }

        // Disable button and show loading
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo trans("processing"); ?>...');
        
        // Send AJAX request
        $.ajax({
            url: base_url + 'login/ajax_signup',
            method: 'POST',
            data: { 
                name: name,
                phone: phone
            },
            dataType: 'json',
            success: function(response) {
                if (response.signup_status === 'success') {
                    window.location.href = response.redirect_url;
                } else {
                    $('#signupPhoneError').addClass('text-danger').text(response.message || '<?php echo trans("signup_failed"); ?>');
                }
            },
            error: function(xhr) {
                $('#signupPhoneError').addClass('text-danger').text('<?php echo trans("server_error_try_again"); ?>');
                console.error('Error:', xhr.responseText);
            },
            complete: function() {
                $('#signupBtn').prop('disabled', false).html('<i class="fa fa-user-plus"></i> <?php echo trans("signup"); ?>');
            }
        });
    });

    // Verify OTP button click handler
    $('#verifyOtpBtn').click(function() {
        let otp = $('#otpInput').val().trim();
        $('#otpError').text('').removeClass('text-danger text-success');
        
        // Validate OTP
        if (!otp || !/^[0-9]{4,6}$/.test(otp)) {
            $('#otpError').addClass('text-danger').text('<?php echo trans("valid_otp_required"); ?>');
            return;
        }

        let phone = $('#phone_number').val().trim();
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> <?php echo trans("verifying"); ?>...');
        
        // Send AJAX request
        $.ajax({
            url: base_url + 'login/verify_otp_login',
            method: 'POST',
            data: { 
                otp: otp,
                phone: phone
            },
            dataType: 'json',
            success: function(response) {
                console.log('Verification Response:', response);
                if (response.status === 'success') {
                    $('#otpError').addClass('text-success').text(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 1000);
                } else {
                    $('#otpError').addClass('text-danger').text(response.message || '<?php echo trans("otp_verification_failed"); ?>');
                }
            },
            error: function(xhr) {
                $('#otpError').Class('text-danger').text('<?php echo trans("server_error_try_again"); ?>');
                console.error('Error:', xhr.responseText);
            },
            complete: function() {
                $('#verifyOtpBtn').prop('disabled', false).html('<?php echo trans("verify"); ?>');
            }
        });
    });

    // Start OTP countdown timer
    function startOtpTimer() {
        clearInterval(otpTimer);
        timeLeft = 300; // 5 minutes
        updateOtpTimer();
        otpTimer = setInterval(updateOtpTimer, 1000);
    }

    // Update OTP timer display
    function updateOtpTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        $('#otpTimer').html(`<?php echo trans("otp_expires_in"); ?>: <strong>${minutes}:${seconds < 10 ? '0' : ''}${seconds}</strong>`);
        
        if (timeLeft <= 0) {
            clearInterval(otpTimer);
            $('#otpTimer').html('<strong class="text-danger"><?php echo trans("otp_expired"); ?></strong>');
            $('#verifyOtpBtn').prop('disabled', true);
        }
        timeLeft--;
    }

    // Reset timer when modal is shown
    $('#otpModal').on('shown.bs.modal', function() {
        startOtpTimer();
    });

    // Clear timer when modal is hidden
    $('#otpModal').on('hidden.bs.modal', function() {
        clearInterval(otpTimer);
        $('#otpInput').val('');
        $('#otpError').text('');
        $('#otpTimer').html('');
        $('#debugOtp').hide().text('');
    });
});
</script>

<style>
    .alert {
        margin-bottom: 20px;
        transition: all 0.5s ease;
    }
    #otpTimer {
        font-size: 1.1em;
        margin: 15px 0;
    }
    .text-danger {
        color: #dc3545;
    }
    .text-success {
        color: #28a745;
    }
    .btn-block {
        margin-top: 15px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .btn-gplus {
        background-color: #dd4b39;
        color: white;
    }
    .btn-fb {
        background-color: #3b5998;
        color: white;
    }
    .disable-bottom-line {
        border-bottom: 1px solid #eee;
        margin-top: 5px;
    }
    .movie-heading {
        margin-bottom: 20px;
    }
</style>