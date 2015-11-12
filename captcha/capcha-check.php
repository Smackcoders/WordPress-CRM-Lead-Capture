<?php
require_once('recaptchalib.php');
$publickey = "6Ldi69ESAAAAAIijH1t2um6ULYt0HTAFbN9nMA9T";
$privatekey = "6Ldi69ESAAAAAPZ6H0lWtwmnxdII9t6iDw4Vykve";

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;
$resp = recaptcha_check_answer ($privatekey,
                              "127.0.0.1",
                              $_POST["recaptcha_challenge_field"],
                              $_POST["recaptcha_response_field"]);


//print($_SERVER["REMOTE_ADDR"]."<br/>".$_POST["recaptcha_challenge_field"]."<br/>".$_POST["recaptcha_response_field"]);
if (!$resp->is_valid) {
  // What happens when the CAPTCHA was entered incorrectly
  //die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .      "(reCAPTCHA said: " . $resp->error . ")");
	echo "captcha failed";
} else {
  // Your code here to handle a successful verification
	echo "captha success";
}
?>
