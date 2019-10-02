<div id="wrapper">
   <?php

 include('includes/sidebar.php');
   ?>
   
   <div id="content-wrapper">

        <div class="container-fluid">

        
          <h1>EDIT PAGE</h1>
         
          <?php if(!empty($_GET['mess'])){?>
          <div class="alert alert-success">
  <strong>Success!</strong> Page updated Successfuly.
</div>
<?php } ?>
     <form action="#" method="post" name="form1" id="form1">
     
        <div class="form-group">
              <div class="form-row">
                <div class="col-md-6">
                  <div class="form-label-group">
                  <input type="hidden"  name="id"  value="<?php echo $resultsql['id'];?>">
              <input type="text" id="page_title" name="page_title" class="form-control"  value="<?php echo $resultsql['page_title'];?>" >  
                    <label for="page_title">Page Title</label>
                  </div>
                </div>
                 <div class="col-md-6">
                  <div class="form-label-group">
                    <select name="status" class="form-control"  value="<?php echo $resultsql['status'];?>">
  <option value="1" <?php if($resultsql['status']=="1"){ echo 'selected="selected"';}?> >Active</option>
  <option value="0"<?php if($resultsql['status']=="0"){ echo 'selected="selected"';}?> >Inactive</option>

</select>
 
                  </div>
                </div>
                 </div>
                 
            </div>
              <div class="form-group">
              <div class="form-row">
              <div class="col-md-12">
               <label for="page_content">Page Content</label>
                  <div class="form-label-group">
                 
                    <textarea type="text" id="editor1" name="page_content" class="form-control" ><?php echo $resultsql['page_content'];?> </textarea>
                    
                    
                  </div>
                </div>
              
              </div>
              </div>
              <div class="form-group">
               <div class="form-row">
                <div class="col-md-6">
                <div class="form-label-group">
                           
                <input type="text" id="meta_title" name="meta_title" class="form-control"  value="<?php echo $resultsql['meta_title'];?> ">  
               <label for="meta_title">Meta Title</label>
              </div>
                </div>
                </div>
                </div>
           <div class="form-group">
  
        <label for="meta_description">Meta Description</label>
              
       <textarea  class="form-control"   name="meta_desc" id="" class="form_control"  ><?php echo $resultsql['meta_description'];?>  </textarea>
      
        <script>
				   CKEDITOR.replace( 'editor1' );
				  </script>
         
              </div>
            
                            
          

         <div class="text-center col-sm-2" style="width:150px; margin:0 auto;"><input type="submit" name="submit" value="submit"class="btn btn-primary btn-block"></div>


        
 </form>
        </div>
       </div>
        <!-- /.container-fluid -->
   