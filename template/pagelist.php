 <div id="wrapper">

 <?php 
include('includes/sidebar.php');
?>

      <div id="content-wrapper">

        <div class="container-fluid">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th>Page title</th>
                    <!--<th>Page content</th>
                      <th>Meta title</th>
                      <th>Meta description</th>-->
                      <th>status</th>
                      <th>Action</th>
              
                    </tr>
                   </thead>
                  <tfoot>
                    
                  </tfoot>
                  <tbody>
    <?php
	$i=1;
    while($fetchdatapage = mysqli_fetch_array($resultsql)){
	?>
    <tr><td><?php echo  $i;?></td>
        <td><?php echo $fetchdatapage['page_title'];?></td>
       <!-- <td><?php echo $fetchdatapage['page_content'];?></td>
          <td><?php echo $fetchdatapage['meta_title'];?></td>
         <td><?php echo $fetchdatapage['meta_description'];?></td>-->
            <td><?php if($fetchdatapage['status'] == '1'){ echo 'Active';} else { echo 'Inactive';}?></td>
        
          <td><a href="editpage.php?id=<?php echo $fetchdatapage['id'];?>">Edit</a></td>
     
          </tr>
        <?php
		$i++; }
		?>
                  </tbody>
                </table>
              </div>
            </div>
            <!--<div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>-->
          </div>

         