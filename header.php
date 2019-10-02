<?php
include 'dbconfig.php';
$city = "SELECT * FROM `country`";
$result = $conn->query($city);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $meta_title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" > 
   <meta name="keywords" content="<?php echo $pgkeyword ?>">
     <meta name="description" content="<?php echo $pgDesc ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<style type="text/css">
  
   

</style>
<body>
    <!----------header starts---------->
    <div class="header-box">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-lg-4 col-md-4 ">
                  <div class="logo">
                    <a href="#"><img src="assets/images/logo.png"></a>
                  </div>                    
                </div>
                <div class="col-sm-4 col-lg-4 col-md-4 ">
                  <div class="menu-donate">
                     <a href="donate_now.php" class="donatebtn"><i>&nbsp;</i>Donate</a> 
                  </div>                   
                </div>
                <div class="col-sm-4 col-lg-4 col-md-4 ">
                  <div class="menu"> 

                 <label for="menu-toggle">
                  <p>
                    <span>Mane</span>
                    <img src="assets/images/menu-toggle.png" alt="">
                  </p>
                </label>

                   <input type="checkbox" id="menu-toggle"/>
                    <ul id="menu">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about_us.php">About Us</a></li>
                        <li><a href="our_work.php">Our Work</a></li>
                        
                    </ul>
                      </div>              
                </div>
            </div>
        </div>
    </div>
    <!------banner------------>
    