<?php
include 'header.php';

require('config.php');
require('razorpay-php/Razorpay.php');


if (isset($_GET["ID"])) {
    $lastid = $_GET["ID"];
    $confirmdata = "SELECT * FROM `donate_details` WHERE ID=" . $lastid;
    $result = $conn->query($confirmdata);
    $row = $result->fetch_assoc();
    //print_r($row);
}else{
 header("Location: donate_now.php?error=".md5(rand()));
}



// Create the Razorpay Order

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//
$orderData = [
    'receipt' => rand(1111,9999),
    'amount' => $row['amount'] * 100, // 2000 rupees in paise
    'currency' => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

$data = [
    "key" => $keyId,
    "amount" => $amount,
    "name" => "littlepray.org",
    "description" => "NGO Donate",
    "image" => "assets/images/logo.png",
    "prefill" => [
        "name" => $row['full_name'],
        "email" => $row['email'],
        "contact" => $row['phone'],
    ],
    "notes" => [
        "address" => $row['address'],
        "merchant_order_id" => $row['email'].'_'.$_GET["ID"],
    ],
    "theme" => [
        "color" => "#F37254"
    ],
    "order_id" => $razorpayOrderId,
];

$json = json_encode($data);
?>
<!--  The entire list of Checkout fields is available at
 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->




<div class="form-box-spacer">
    <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-md-12">
                    <div class="impact-main-box">
                    <div class="impact-heading">
                        <h3>Yes, I want to Support a Child!</h3>
                    </div>
                        <?php
                        if ($result->num_rows > 0) {
                            // output data of each row
                            
                                ?>


                                <div class="form-box">
                                    <div class="child-foem-box">
                                        <label >Name :</label>
                                        <div class="value-fild"><?php echo $row['full_name']; ?></div>
                                    </div>

                                    <div class="child-foem-box">
                                        <label >Email :</label>
                                        <div class="value-fild"><?php echo $row['email']; ?></div>
                                    </div>

                                    <div class="child-foem-box">
                                        <label >Phone :</label>
                                        <div class="value-fild"><?php echo $row['phone']; ?></div>
                                    </div>

                                    <div class="child-foem-box">
                                        <label >Add :</label>
                                        <div class="value-fild"><?php echo $row['address']; ?></div>
                                    </div>

                                    <div class="child-foem-box">
                                        <label >Country :</label>
                                        <div class="value-fild"><?php echo $row['country']; ?></div>
                                    </div>
                                    <div class="child-foem-box">
                                        <label >D.O.B :</label>
                                        <div class="value-fild"><?php echo $row['date']; ?>/<?php echo $row['month']; ?>/<?php echo $row['year']; ?></div>
                                    </div>

                                    <div class="child-foem-box">
                                        <label >PAN :<span>Only for India:</span></label>
                                        <div class="value-fild"><?php echo $row['pan']; ?></div>
                                    </div>
                                </div>
                                <?php
                            
                        }
                        ?>

                        <div class="row">
                            <div class="col-sm-12 col-lg-12 col-md-12 btn-details4">
                                 <a href="index.php"> <i class="fa fa-chevron-circle-left" aria-hidden="true"> </i>Back</a>
                                <button id="rzp-button1">Continue for Donation</button>
                               
                            </div>
                        </div>
                    


                    <div class="impact-story">
                        <p>
                            Please share your personal <span >Email ID, Mobile Number, Address</span> and <span >PAN No</span>. so that we can send you the reciept certificate &amp; share updates on 
                            our programmes with you.<br>
                            <span >To know more please sms SF to 56161<br>(Registration No. - 6382)</span>
                        </p>
                    </div>

                    <div class="row">
                        <div class=" col-sm-12 col-lg-12 col-md-12 contact">
                            <p>
                                <span >For more information contact:</span><br>

                                <span>Email:</span> <a href="#">trtfsghjfsghfsg@xxxxxx.org</a><br>

                                <span ><strong>Phone: +91-11234556677, Mob: +91 234456678</strong></span><br><br>

                                <span >For more information on our <a href="#"><strong>Educational Programme</strong></a></span>
                            </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="verify.php" method="POST">
<input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
<input type="hidden" name="razorpay_signature" id="razorpay_signature" >
<input type="hidden" name="email" value="<?=$row['email'];?>" >
<input type="hidden" name="user_name" value="<?=$row['full_name'];?>" >
<input type="hidden" name="ammount" value="<?=$row['amount'];?>" >
</form>
<script>
// Checkout details as a json
var options = <?php echo $json?>;

/**
* The entire list of Checkout fields is available at
* https://docs.razorpay.com/docs/checkout-form#checkout-fields
*/
options.handler = function (response){
    //alert(response.razorpay_payment_id);
    //alert(response.razorpay_signature);
document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
document.getElementById('razorpay_signature').value = response.razorpay_signature;
document.razorpayform.submit();
};

// Boolean whether to show image inside a white frame. (default: true)
options.theme.image_padding = false;

options.modal = {
ondismiss: function() {
console.log("This code runs when the popup is closed");
},
// Boolean indicating whether pressing escape key 
// should close the checkout form. (default: true)
escape: true,
// Boolean indicating whether clicking translucent blank
// space outside checkout form should close the form. (default: false)
backdropclose: false
};

var rzp = new Razorpay(options);

document.getElementById('rzp-button1').onclick = function(e){
rzp.open();
e.preventDefault();
}
</script>

<?php include 'footer.php'; ?>
</body>
</html>