<?php include 'header.php'; ?>

<?php
if (isset($_POST['donatesubmit'])) {
    $amount = $_POST['amount'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $date = $_POST['date'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $pan = $_POST['pan'];
    $term = $_POST['term'];
   $res1 = "select * from country where  name = '$_POST[country]'";
   $resultc = mysqli_fetch_array(mysqli_query($conn,$res1)); 
   $currency = $resultc['currency_code'];
 
    $add_query = "INSERT INTO `donate_details` (`amount`, `full_name`, `email`, `phone`,`address`, `country`,`currency`,`date`, `month`,`year`, `pan`, `term`) VALUES ('$amount', '$full_name', '$email', '$phone','$address', '$country','$currency', '$date', '$month','$year', '$pan', '$term');";
    if ($conn->query($add_query) === TRUE) {
        $last_id = $conn->insert_id;?>
       <script type="text/javascript">
            //alert('Data added successfully');
            window.location.href = "confirmation_donate.php?ID=<?= $last_id ?>";
        </script><?php
    } else {
        echo "Error: " . $add_query . "<br>" . $conn->error;
    }?>
        <?php
}
?> 
<div class="form-box-spacer">
    <div class="container">
        <?php
if(isset($_GET['error'])){
    ?>
 <div class="alert alert-danger alert-dismissible">
    <a href="https://littlepray.org/donate_now.php" class="close"  >&times;</a>
    <strong>Please</strong>  Try again..
</div>
    <?php
}
?>
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-md-12 box-spacer-center">
                  <div class="impact-main-box">
                    <div class="impact-heading">
                        <h3>Yes, I want to Support a Child!</h3>
                    </div>
                    <form method="post" action="" id="Donate1st" enctype="multipart/form-data">
                        <div class="form-box">
                            <div class="child-foem-box">
                                <label class="redio"><input type="radio" name="amount" value="30000" checked></label>
                                <p>Rs. 30,000/- (for the education of 5 children for 1 year)</p>
                            </div>

                            <div class="child-foem-box">
                                <label class="redio"><input type="radio" name="amount" value="18000" checked></label>
                                <p>Rs. 18,000/- (for the education of 3 children for 1 year)</p>
                            </div>

                            <div class="child-foem-box">
                                <label class="redio"><input type="radio" name="amount" value="6000" checked></label>
                                <p>Rs. 6,000/- (for the education of 1 child for 1 year)</p>
                            </div>
                            <div class="child-foem-box">
                                <label >Name :</label>
                                <input type="name" name="full_name" id="full_name">
                            </div>

                            <div class="child-foem-box">
                                <label >Email :</label>
                                <input type="email" name="email" id="email">
                            </div>

                            <div class="child-foem-box">
                                <label >Phone :</label>
                                <input type="text" name="phone">
                            </div>

                            <div class="child-foem-box">
                                <label >Add :</label>
                                <input type="text" name="address">
                            </div>

                            <div class="child-foem-box">
                                <label >Country :</label>
                                <select name="country">
                                    <option value="" disabled="disabled" selected="selected">Country</option>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                            <div class="child-foem-box">
                                <label >D.O.B :</label>
                                <ul>
                                    <li>
                                        <select name="date" id="date">
                                            <option value="" disabled="disabled" selected="selected">Date</option>
                                            <?php
                                            $date = 31;
                                            $add = 1;
                                            for ($i = $date; $i >= $add; $i--) {
                                                echo '<option value=' . $i . '>' . $i . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </li>
                                    <li>
                                        <select name="month">
                                            <option value="" disabled="disabled" selected="selected">Month</option>
                                            <?php
                                            $months = array("Jan", "Feb", "Mar", "April", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec");
                                            foreach ($months as $month) {
                                                echo "<option value=\"" . $month . "\">" . $month . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </li>
                                    <li>
                                        <select name="year" id="year">
                                            <option value="" disabled="disabled" selected="selected">Year</option>
                                            <?php
                                            $year = 2012;
                                            $add = 1920;
                                            for ($i = $year; $i >= $add; $i--) {
                                                echo '<option value=' . $i . '>' . $i . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </li>
                                </ul>
                            </div>

                            <div class="child-foem-box">
                                <label >PAN :<span>Only for India:</span></label>
                                <input type="text" name="pan">
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-lg-12 col-md-12">
                                <div class="check-box">
                                <input type="checkbox" name="term" id="term" value="term">I give my consent for authorized representatives of Smile Foundation to contact me occasionally by mobile and email for informing on the latest developments and updated offerings. 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-lg-12 col-md-12">
                                <div class=" btn-details4">
                                <input type="submit" value="Donate Now" name="donatesubmit">
                                </div>
                            </div>
                        </div>
                    </form>


                    <div class="impact-story">
                        <p>
                            Please share your personal <span >Email ID, Mobile Number, Address</span> and <span >PAN No</span>. so that we can send you the reciept certificate &amp; share updates on 
                            our programmes with you.<br>
                            <span >To know more please sms SF to 56161<br>(Registration No. - 6382)</span>
                        </p>
                    </div>

                    <div class="row">
                        <div class=" col-sm-12 col-lg-12 col-md-12 ">
                            <div class="contact-box">
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
</div>
<?php include 'footer.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/additional-methods.js"></script>
<script src="assets/validetor/jquery.validate.min.js"></script>
<script>
    jQuery.validator.addMethod("lettersonly", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, "Letters only please");
    $('#Donate1st').validate({
        rules: {
            term: "required",
            full_name: {
                required: true,
                lettersonly: true
            },
            email: {
                required: true,
                email: true
            },
        },
        messages: {
            full_name: "Please enter name(only alphabetical characters)",
            email: "Please enter valid email id",
            term: "please check if you agree",
        },

    });

</script>
</body>
</html>