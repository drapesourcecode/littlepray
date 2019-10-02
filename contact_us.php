<?php include 'header.php'; ?>
<?php
//$headerszz = 'From: '.$email_from."\r\n".
//'Reply-To: '."support@littlepray.org"."\r\n" .
//'X-Mailer: PHP/' . phpversion();
// mail("mohammedfazlehaque@gmail.com", "nnjnj", 'nnnn',$headerszz);
$error = '';
$from = "support@littlepray.org";
if (isset($_POST['contactmail'])) {

    $to = "mohammedfazlehaque@gmail.com";
    if (empty($_POST['fname'])) {
        $error = "Please enter full name";
    } else if (empty($_POST['mobile'])) {
        $error = "Please enter your mobile no";
    } else if (empty($_POST['email'])) {
        $error = "Please enter email";
    } else if (empty($_POST['city'])) {
        $error = "Please enter city";
    } else if (empty($_POST['comments'])) {
        $error = "Please enter comments";
    } else {

        $to = "mohammedfazlehaque@gmail.com";
        $subject = "New Contact Email From Littlepray";
//echo $_POST['messagecom'];exit;
        // print_r($_POST);exit;
        $message = " 
<html>
<head>
<title>New Contact Email From </title>
</head>
<body>
<p>This email contain All user info</p>
<table width='600'>
<tr>
<th align='left'>Full Name</th>
<th align='left'>" . $_POST['fname'] . "</th>
</tr>
<tr>
<th align='left'>Mobile</th>
<th align='left'>" . $_POST['mobile'] . "</th>
</tr>
<tr>
<th align='left'>Email Address</td>
<th align='left'>" . $_POST['email'] . "</th>
</tr>
<tr>
<th align='left'>City</th>
<th align='left'>" . $_POST['city'] . "</th>
</tr>

<tr>
<th align='left'>Comments</th>
<th align='left'>" . $_POST['comments'] . "</td>
</tr>

</table>
</body>
</html>
";

// Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
        //$headers .= 'From: <info@wanderersolutions.com>' . "\r\n";
        $headers .= 'From: <' . $from . '>' . "\r\n";

        $email = mail($to, $subject, $message, $headers);
        if ($email) {
            $msg = "Message send successfully";
            ?>
            <script>
                setTimeout(function () {
                    window.location.replace("http://littlepray.org/newlittlepray/contact_us.php");
                }, 5000);
            </script>
            <?php
        }
    }
}
?>
     <div class="about-banner-box">
      <img src="assets/images/Professionals_Contact-Us-a.jpg"> 
      <div class="inner-banner-content">     
        <div class="container">
          
          <div class="title-heading">
            <h1>CONTACT US</h1>
          </div>
        </div> 
         </div>   
    </div>
            <div class="form-box-contact">

                <div class="container">
<?php if (!empty($msg)) { ?>
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-md-12">

            <div class="alert alert-success" role="alert">
                <?php echo $msg; ?>
            </div>
        </div>
    </div>
<?php } ?>
<div class="contactus-form-bax">
    <h2>CONTACT US</h2>
<form class="contact-form"method="post" action="" name="contactus" id="contactus" enctype="multipart/form-data">
  <!---  <table width="450px">
        <tr>
            <td valign="top">
                <label for="fname">First Name *</label>
            </td>
            <td valign="top">
                <input type="text" placeholder="Full name" name="fname" id="fname" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td valign="top">
                <label for="mobile">mobile *</label>
            </td>
            <td valign="top">
                <input type="text" placeholder="Mobile No" name="mobile" id="mobile" autocomplete="off" maxlength="10">
            </td>
        </tr>
        <tr>
            <td valign="top">
                <label for="email">Email Address *</label>
            </td>
            <td valign="top">
                <input type="email" placeholder="Email ID" name="email" id="email" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td valign="top">
                <label for="City">City</label>
            </td>
            <td valign="top">
                <input type="text" placeholder="City" name="city" id="city" autocomplete="off">
            </td>
        </tr>
        <tr>
            <td valign="top">
                <label for="comments">Comments *</label>
            </td>
            <td valign="top">
                <textarea placeholder="Comments" name="comments" id="comments"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center">
                <button type="submit" name="contactmail">Send</button> 
            </td>
        </tr>
    </table>--->

            <div class="row">
                <div class="col-sm-6 col-lg-6 col-md-6">
                   <input type="text" placeholder="Full name" name="fname" id="fname" autocomplete="off"> 
                </div>
                <div class="col-sm-6 col-lg-6 col-md-6">
                    <input type="email" placeholder="Email ID" name="email" id="email" autocomplete="off">
                </div>
                <div class="col-sm-6 col-lg-6 col-md-6">
                  <input type="text" placeholder="Mobile No" name="mobile" id="mobile" autocomplete="off" maxlength="10">  
                </div>
                <div class="col-sm-6 col-lg-6 col-md-6">
                  <input type="text" placeholder="City" name="city" id="city" autocomplete="off">  
                </div>
                <div class="col-sm-12 col-lg-12 col-md-12">
                    
                </div>
                <div class="col-sm-12 col-lg-12 col-md-12">
                     <textarea placeholder="Comments" name="comments" id="comments"></textarea>
                </div>  
                <div class="col-sm-12 col-lg-12 col-md-12">
                      <button type="submit" name="contactmail">Send</button> 
                    
                </div> 
            </div>
     
</form>
</div>
                </div>
            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/additional-methods.js"></script>
<script src="assets/validetor/jquery.validate.min.js"></script>
<script>
                $('#contactus').validate({
                    // Specify validation rules
                    rules: {
                        // The key name on the left side is the name attribute
                        // of an input field. Validation rules are defined
                        // on the right side
                        fname: "required",
                        city: "required",
                        comments: "required",
                        mobile: {
                            phoneCUS: true,
                            required: true
                        },
                        email: {
                            required: true,
                            // Specify that email should be validated
                            // by the built-in "email" rule
                            email: true
                        },
                    },
                    // Specify validation error messages
                    messages: {
                        fname: "Please enter your full name",
                        mobile: "Please enter your mobile no",
                        email: "Please enter a valid email address",
                        city: "Please enter your city name",
                        city: "Please enter your city name",
                        comments: "Please enter your Comments"
                    },
                });
                jQuery.validator.addMethod("phoneCUS", function (mobile, element) {
                    mobile = mobile.replace(/\s+/g, "");
                    return this.optional(element) || mobile.length > 9 &&
                            mobile.match(/^(\+?1-?)?(\([6-9]\d{2}\)|[6-9]\d{2})-?[0-9]\d{2}-?\d{4}$/);
                }, "Please specify a valid mobile number");
</script>

<?php include 'footer.php'; ?>
