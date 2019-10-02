<?php include 'header.php'; ?>

     <div class="about-banner-box">
      <img src="assets/images/CARE-India-presence.jpg"> 
      <div class="inner-banner-content">     
        <div class="container">
          
          <div class="title-heading">
            <h1><?php echo $data_about['page_title'];?></h1>
          </div>
        </div> 
         </div>   
    </div>
    <!------last header------------>
    <div class="header-last">
      <div class="container">
        <div class="row">
          <div class="col-sm-12 col-lg-12 col-md-12">
          <ul>
            <li><a href="#">home</a></li>
            <li>Who we are</li>
          </ul>
          </div>
        </div>
      </div>
    </div>
     <!------last header------------>

<!------WHO WE ARE------------>
     
<div class="about-who-are-you-box">
  <div class="container">
    <div class="row">

      <?php echo $data_about['page_content'];?>
    </div>
  </div>
</div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<?php include 'footer.php'; ?>