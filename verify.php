<?php
include 'header.php';
require('config.php');
require('razorpay-php/Razorpay.php');
?>
<div class="verify-form-box-spacer">
    <div class="container">
<?php
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
//echo session_save_path() ;
//print_r($_SESSION['razorpay_order_id']);
$success = true;

$error = "Payment Failed";

if (empty($_POST['razorpay_payment_id']) === false)
{
    $api = new Api($keyId, $keySecret);

    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $_SESSION['razorpay_order_id'],
            'razorpay_payment_id' => $_POST['razorpay_payment_id'],
            'razorpay_signature' => $_POST['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

if ($success === true)
{
$to = $_POST['email'];
$subject = "Chayanika Little Pray Payment details";

$message = "
<p>Payment Details</p>
<table>
<tr>
<td>Payment Id : </td>
<td>".$_POST['razorpay_payment_id']."</td>
</tr>
<tr>
<td>Payment Date : </td>
<td>".date('d-M-Y')."</td>
</tr>
<tr>
<td>User Name : </td>
<td>".$_POST['user_name']."</td>
</tr>
<tr>
<td>Ammount : </td>
<td>₹".number_format($_POST['ammount'],2)."</td>
</tr>
<tr>

</table>
";
//echo $message;
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <support@littlepray.org>' . "\r\n";
$headers .= 'Cc: debmicrofinet@gmail.com' . "\r\n";

mail($to,$subject,$message,$headers);
?>

	<div class="row text-center">
        <div class="col-sm-6 col-sm-offset-3">
        <br><br> <h2 style="color:#0fad00">Success</h2>
        <img src="assets/images/correct.svg" style="width:50%;">
        <h3>Dear, <?=$_POST['user_name'];?></h3>
        <p style="font-size:20px;color:#5C5C5C;">Your payment was successful.<br>Payment ID: <?=$_POST['razorpay_payment_id']; ?></p>
    <br><br>
        </div>
        
	</div>

<?php

}
else
{
?>
<div class="row text-center">
        <div class="col-sm-6 col-sm-offset-3">
        <br><br> <h2 style="color:#ad0100;">Failed</h2>
        <img src="assets/images/x-mark.svg" style="width:50%;">
        <h3>Your payment failed</h3>
        <p style="font-size:20px;color:#5C5C5C;"><?=$error; ?></p>
    <br><br>
        </div>
        
	</div>

<?php
}
?>
</div>
</div>
<?php include 'footer.php';  ?>
</body>
</html>