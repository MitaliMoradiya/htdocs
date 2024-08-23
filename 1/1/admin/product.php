<?php
require('top.inc.php');

$condition='';
$condition1='';
if($_SESSION['ADMIN_ROLE']==1){
	$condition=" and product.added_by='".$_SESSION['ADMIN_ID']."'";
	$condition=" and 1";
	$condition1=" and added_by='".$_SESSION['ADMIN_ID']."'";
}

if(isset($_GET['type']) && $_GET['type']!=''){
	$type=get_safe_value($con,$_GET['type']);

		$status = null;  //initialize $status

	    // Handle active/deactive operations
		if ($type == 'status') {
			$operation = get_safe_value($con, $_GET['operation']);
			$id = get_safe_value($con, $_GET['id']);
	
			if ($operation == 'active') {
				$status = 1; // Set product as active
			} elseif ($operation == 'deactive') {
				$status = 0; // Set product as inactive
			}
	
			// Update product status in the database
			$update_status_sql = "UPDATE product SET status='$status' $condition1 WHERE id='$id'";
			mysqli_query($con, $update_status_sql);
		}
	

	
	if($type=='status'){
		$operation=get_safe_value($con,$_GET['operation']);
		$id=get_safe_value($con,$_GET['id']);


// Check the type of operation (approve, reject, etc.)
if ($operation == 'approve') {
	$submission_status = 'approved';
	$status = 1; // Set product as active
} elseif ($operation == 'reject') {
	$submission_status = 'rejected';
	$status = 0; // Set product as inactive
} else {
	$submission_status = 'pending';
	$status = 0; // Default to inactive if not approved or rejected
}

        $update_status_sql = "UPDATE product SET submission_status='$submission_status', status='$status' $condition1 WHERE id='$id'";
        mysqli_query($con, $update_status_sql);
    }

 // Fetch the user_id associated with the product
 $result = mysqli_query($con, "SELECT added_by FROM product WHERE id='$id'");
 if ($row = mysqli_fetch_assoc($result)) {
	 $user_id = $row['added_by'];


	 // Create a notification message
	 $notification_message = "Your product with ID $id has been $submission_status.";
            mysqli_query($con, "INSERT INTO notifications (user_id, message, created_at) VALUES ('$user_id', '$notification_message', NOW())");


			// Optional: send an email to the customer
            $user_result = mysqli_query($con, "SELECT email FROM users WHERE id='$user_id'");
            if ($user_row = mysqli_fetch_assoc($user_result)) {
                $user_email = $user_row['email'];
                $subject = "Your Product has been $submission_status";
                $body = "Hello,\n\nYour product with ID $id has been $submission_status.\n\nThank you!";
                $headers = "From: admin@yourwebsite.com";
				//mail($user_email, $subject, $body, $headers);
			}
 }
	if ($type == 'delete') {
        $id = get_safe_value($con, $_GET['id']);
        $delete_sql = "DELETE FROM product WHERE id='$id' $condition1";
        mysqli_query($con, $delete_sql);
    }
}

$sql = "SELECT product.*, categories.categories 
        FROM product, categories 
        WHERE product.categories_id=categories.id 
		AND product.status=1 
        AND (product.submission_status='approved' OR product.submission_status='pending' $condition) 
        ORDER BY product.id DESC";
$sql = "SELECT product.*, categories.categories 
        FROM product 
        JOIN categories ON product.categories_id=categories.id 
        WHERE 1 $condition 
        ORDER BY product.id DESC";
$res = mysqli_query($con, $sql);

// 		if($operation=='active'){
// 			$status='1';
// 		}else{
// 			$status='0';
// 		}
// 		$update_status_sql="update product set status='$status' $condition1 where id='$id'";
// 		mysqli_query($con,$update_status_sql);
// 	}
	
// 	if($type=='delete'){
// 		$id=get_safe_value($con,$_GET['id']);
// 		$delete_sql="delete from product where id='$id' $condition1";
// 		mysqli_query($con,$delete_sql);
// 	}
// }

// $sql="select product.*,categories.categories from product,categories where product.categories_id=categories.id $condition order by product.id desc";
// $res=mysqli_query($con,$sql);
?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			 <div class="card">
				<div class="card-body">
				   <h4 class="box-title">Products </h4>
				   <h4 class="box-link"><a href="manage_product.php">Add Product</a> </h4>
				</div>
				<div class="card-body--">
				   <div class="table-stats order-table ov-h">
					  <table class="table ">
						 <thead>
							<tr>
							   <th width="2%">ID</th>
							   <th width="10%">Categories</th>
							   <th width="30%">Name</th>
							   <th width="10%">Image</th>
							   <th width="10%">MRP</th>
							   <th width="7%">Price</th>
							   <th width="7%">Qty</th>
							   <th width="26%"></th>
							</tr>
						 </thead>
						 <tbody>
							<?php 
							$i=1;
							while($row=mysqli_fetch_assoc($res)){?>
							<tr>
							   <td><?php echo $row['id']?></td>
							   <td><?php echo $row['categories']?></td>
							   <td><?php echo $row['name']?></td>
							   <td><img src="<?php echo PRODUCT_IMAGE_SITE_PATH.$row['image']?>"/></td>
							   <td><?php echo $row['mrp']?></td>
							   <td><?php echo $row['price']?></td>
							   <td><?php echo $row['qty']?><br/>
							   <?php
							   $productSoldQtyByProductId=productSoldQtyByProductId($con,$row['id']);
							   $pneding_qty=$row['qty']-$productSoldQtyByProductId;
							   
							   ?>
							   Pending Qty <?php echo $pneding_qty?>
							   
							   </td>
							   <td>
								<?php
								// if($row['status']==1)
								// if ($row['submission_status'] == 'pending'){
								// 	echo "<span class='badge badge-complete'><a href='?type=status&operation=deactive&id=".$row['id']."'>Active</a></span>&nbsp;";
								// }else{
								// 	echo "<span class='badge badge-pending'><a href='?type=status&operation=active&id=".$row['id']."'>Deactive</a></span>&nbsp;";
								// }
								// echo "<span class='badge badge-edit'><a href='manage_product.php?id=".$row['id']."'>Edit</a></span>&nbsp;";
								
								// echo "<span class='badge badge-delete'><a href='?type=delete&id=".$row['id']."'>Delete</a></span>";

								if ($row['submission_status'] == 'pending') {
									echo "<span class='badge badge-pending'>Pending</span>";
									echo "<span class='badge badge-approve'><a href='?type=status&operation=approve&id=" . $row['id'] . "' style='color: Blue; text-decoration: none;'>Approve</a></span>&nbsp;";
									echo "<span class='badge badge-reject'><a href='?type=status&operation=reject&id=" . $row['id'] . "' style='color: red; text-decoration: none;'>Reject</a></span>&nbsp;";
								} else {
									echo "<span class='badge badge-approved'>Approved</span>";
								}
								
								?>

							</td>
							<td>
							<?php
                                                if ($row['status'] == 1) {
                                                    echo "<span class='badge badge-complete'><a href='?type=status&operation=deactive&id=" . $row['id'] . "'>Active</a></span>&nbsp;";
                                                } else {
                                                    echo "<span class='badge badge-pending'><a href='?type=status&operation=active&id=" . $row['id'] . "'>Deactive</a></span>&nbsp;";
                                                }
                                                echo "<span class='badge badge-edit'><a href='manage_product.php?id=" . $row['id'] . "'>Edit</a></span>&nbsp;";
                                                echo "<span class='badge badge-delete'><a href='?type=delete&id=" . $row['id'] . "'>Delete</a></span>";
                                                ?>

							   </td>
							</tr>
							<?php } ?>
						 </tbody>
					  </table>
				   </div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>
<?php
require('footer.inc.php');
?>