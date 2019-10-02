<?php include 'header.php'; ?>
    <div class="work-banner-box">
      <img src="assets/images/Our-work-.jpg"> 
      <div class="inner-banner-content">     
        <div class="container">
          
          <div class="title-heading">
            <h1><?php echo $data_work['page_title'];?></h1>
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
            <li>Our Work</li>
          </ul>
          </div>
        </div>
      </div>
    </div>
     <!------last header------------>


       <!------OUR WORK------------>
       <div class="our-works-section">
         <div class="container">
         <?php echo $data_work['page_content'];?>
         </div>
       </div>
       <!--------------SUBSCRIBE TO OUR NEWSLETTER-------------->
<div class="newsletter">
  <div class="container">
    <div class="row">
      <div class=" col-sm-7 col-lg-7 col-md-7 ">
        <div class="newsletter-left">
        <h2>Subscribe to our newsletter</h2>            
              <p>Want CARE India newsletter in your inbox?<br> Sign up for our email updates to receive news, features, and opportunities to make a difference!</p>
        </div>
      </div>
      <div class="col-sm-5 col-lg-5 col-md-5">
          <div class="newsletter-form-box">
           <form>

            <div class="es_textbox">
             <input type="text" name="" value=""  placeholder="Name">
           </div>

          <div class="es_textbox"> 
            <input type="text" name="" value=""  placeholder="Email Address *">
          </div>
          <div class="es_button"> 
            <input type="button" name="" value="Sign up">
          </div> 
        </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
