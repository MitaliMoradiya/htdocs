<?php
require('connection.inc.php');
require('functions.inc.php');

// session_start(); // Start session or continue the existing session


// Check if the user is logged in
if(!isset($_SESSION['USER_ID'])){
    header('location:login.php');
}

// Fetch categories from the database
$category_res = mysqli_query($con, "SELECT id, categories FROM categories WHERE status=1 ORDER BY categories ASC");

if(isset($_POST['submit'])){
    $categories_id = get_safe_value($con, $_POST['categories_id']);
    $name = get_safe_value($con, $_POST['name']);
    $mrp = get_safe_value($con, $_POST['mrp']);
    $price = get_safe_value($con, $_POST['price']);
    $qty = get_safe_value($con, $_POST['qty']);
    $short_desc = get_safe_value($con, $_POST['short_desc']);
    $description = get_safe_value($con, $_POST['description']);
    $meta_title = get_safe_value($con, $_POST['meta_title']);
    $meta_desc = get_safe_value($con, $_POST['meta_desc']);
    $meta_keyword = get_safe_value($con, $_POST['meta_keyword']);
    
    // Image Upload Logic
    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($image_tmp, PRODUCT_IMAGE_SERVER_PATH.$image);

    $added_by = $_SESSION['USER_ID'];
    $submission_status = 'pending';

    $query =  "INSERT INTO product (categories_id, name, mrp, price, qty, image, short_desc, description, meta_title, meta_desc, meta_keyword, added_by, submission_status) VALUES ('$categories_id', '$name', '$mrp', '$price', '$qty', '$image', '$short_desc', '$description', '$meta_title', '$meta_desc', '$meta_keyword', '$added_by', '$submission_status')";
    
    if (mysqli_query($con, $query)) {
    header('location:add_product_confirmation.php');
    exit(); // Ensure no further code is executed
} else {
    echo "Error: " . mysqli_error($con);
}
}
?>

<!-- HTML Form -->
<form method="post" enctype="multipart/form-data" style="max-width: 600px; margin: auto; padding: 20px; background-color: #f9f9f9; border-radius: 10px;">
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="name" style="font-weight: bold; margin-bottom: 5px; display: block;">Product Name:</label>
        <input type="text" id="name" name="name" placeholder="Product Name" required class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="categories_id" style="font-weight: bold; margin-bottom: 5px; display: block;">Category:</label>
        <select id="categories_id" name="categories_id" required class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            <option value="">Select a Category</option>
            <?php while ($row = mysqli_fetch_assoc($category_res)): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['categories']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="mrp" style="font-weight: bold; margin-bottom: 5px; display: block;">MRP:</label>
        <input type="text" id="mrp" name="mrp" placeholder="MRP" required class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="price" style="font-weight: bold; margin-bottom: 5px; display: block;">Price:</label>
        <input type="text" id="price" name="price" placeholder="Price" required class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="qty" style="font-weight: bold; margin-bottom: 5px; display: block;">Quantity:</label>
        <input type="text" id="qty" name="qty" placeholder="Quantity" required class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="image" style="font-weight: bold; margin-bottom: 5px; display: block;">Product Image:</label>
        <input type="file" id="image" name="image" required class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="short_desc" style="font-weight: bold; margin-bottom: 5px; display: block;">Short Description:</label>
        <textarea id="short_desc" name="short_desc" placeholder="Short Description" required class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"></textarea>
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="description" style="font-weight: bold; margin-bottom: 5px; display: block;">Description:</label>
        <textarea id="description" name="description" placeholder="Description" required class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"></textarea>
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="meta_title" style="font-weight: bold; margin-bottom: 5px; display: block;">Meta Title:</label>
        <input type="text" id="meta_title" name="meta_title" placeholder="Meta Title" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="meta_desc" style="font-weight: bold; margin-bottom: 5px; display: block;">Meta Description:</label>
        <input type="text" id="meta_desc" name="meta_desc" placeholder="Meta Description" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <label for="meta_keyword" style="font-weight: bold; margin-bottom: 5px; display: block;">Meta Keyword:</label>
        <input type="text" id="meta_keyword" name="meta_keyword" placeholder="Meta Keyword" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    </div>
    
    <div class="form-group" style="margin-bottom: 15px;">
        <input type="submit" name="submit" value="Submit" class="btn btn-primary" style="width: 100%; padding: 10px; background-color: #007bff; border: none; border-radius: 5px; color: white; cursor: pointer;">
    </div>
</form>
