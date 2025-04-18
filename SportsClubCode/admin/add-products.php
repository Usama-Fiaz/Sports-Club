<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['adminsession']) == 0) {   
    header('location:logout.php');
} else { 
    if(isset($_POST['add'])) {
        $category = $_POST['category'];
        $subcat = $_POST['subcategory'];
        $productname = $_POST['productName'];
        $productcompany = $_POST['productCompany'];
        $productprice = $_POST['productprice'];
        $productpricebd = $_POST['productpricebd'];
        $productdescription = $_POST['productDescription'];
        $productscharge = $_POST['productShippingcharge'];
        $productavailability = $_POST['productAvailability'];
        $addedby = $_SESSION['adminsession'];

        // Handling Image Upload
        $productimage1 = $_FILES["productimage1"]["name"];
        $productimage2 = $_FILES["productimage2"]["name"];
        $productimage3 = $_FILES["productimage3"]["name"];

        $imgnewfile1 = md5($productimage1.time()).substr($productimage1, -4);
        $imgnewfile2 = md5($productimage2.time()).substr($productimage2, -4);
        $imgnewfile3 = md5($productimage3.time()).substr($productimage3, -4);

        move_uploaded_file($_FILES["productimage1"]["tmp_name"], "productimages/".$imgnewfile1);
        move_uploaded_file($_FILES["productimage2"]["tmp_name"], "productimages/".$imgnewfile2);
        move_uploaded_file($_FILES["productimage3"]["tmp_name"], "productimages/".$imgnewfile3);

        // Insert query
        $sql = "INSERT INTO products (categoryId, subCategoryId, productName, productCompany, productPrice, 
                productDescription, shippingCharge, productAvailability, productImage1, productImage2, productImage3, 
                productPriceBeforeDiscount, addedBy) 
                VALUES (:category, :subcat, :productname, :productcompany, :productprice, :productdescription, 
                :productscharge, :productavailability, :imgnewfile1, :imgnewfile2, :imgnewfile3, :productpricebd, :addedby)";

        $query = $dbh->prepare($sql);
        $query->bindParam(':category', $category, PDO::PARAM_INT);
        $query->bindParam(':subcat', $subcat, PDO::PARAM_INT);
        $query->bindParam(':productname', $productname, PDO::PARAM_STR);
        $query->bindParam(':productcompany', $productcompany, PDO::PARAM_STR);
        $query->bindParam(':productprice', $productprice, PDO::PARAM_STR);
        $query->bindParam(':productdescription', $productdescription, PDO::PARAM_STR);
        $query->bindParam(':productscharge', $productscharge, PDO::PARAM_STR);
        $query->bindParam(':productavailability', $productavailability, PDO::PARAM_STR);
        $query->bindParam(':imgnewfile1', $imgnewfile1, PDO::PARAM_STR);
        $query->bindParam(':imgnewfile2', $imgnewfile2, PDO::PARAM_STR);
        $query->bindParam(':imgnewfile3', $imgnewfile3, PDO::PARAM_STR);
        $query->bindParam(':productpricebd', $productpricebd, PDO::PARAM_STR);
        $query->bindParam(':addedby', $addedby, PDO::PARAM_INT);

        if($query->execute()) {
            echo "<script>alert('Product added successfully');</script>";
            echo "<script>window.location.href='manage-products.php'</script>";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Add Product</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <style>
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<div id="wrapper">
    <?php include_once('includes/header.php'); ?>
    <?php include_once('includes/leftbar.php'); ?>

    <div id="page-wrapper">
        <div class="container-fluid px-4">
            <h1 class="mt-4">Add Product</h1>
            <div class="card mb-4">
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Category</label>
                                <select name="category" class="form-control" onChange="getSubcat(this.value);" required>
                                    <option value="">Select Category</option> 
                                    <?php 
                                    $query = $dbh->prepare("SELECT * FROM shopping_category");
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    foreach($results as $row) { ?>
                                        <option value="<?php echo $row->id; ?>"><?php echo $row->categoryName; ?></option>
                                    <?php } ?>
                                </select>    
                            </div>
                            <div class="card mb-4">
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Subcategory</label>
                                <select name="subcategory" class="form-control" onChange="getSubcat(this.value);" required>
                                    <option value="">Select SubCategory</option> 
                                    <?php 
                                    $query = $dbh->prepare("SELECT * FROM sub_category");
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    foreach($results as $row) { ?>
                                        <option value="<?php echo $row->id; ?>"><?php echo $row->subCategoryName; ?></option>
                                    <?php } ?>
                                </select>    
                            </div>
                           

                            <div class="col-md-6 form-group">
                                <label>Product Name</label>
                                <input type="text" name="productName" class="form-control" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Product Company</label>
                                <input type="text" name="productCompany" class="form-control" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Product Price Before Discount</label>
                                <input type="text" name="productpricebd" class="form-control" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Product Price After Discount</label>
                                <input type="text" name="productprice" class="form-control" required>
                            </div>

                            <div class="col-md-12 form-group">
                                <label>Product Description</label>
                                <textarea name="productDescription" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Product Featured Image</label>
                                <input type="file" name="productimage1" class="form-control" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Product Image 2</label>
                                <input type="file" name="productimage2" class="form-control" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Product Image 3</label>
                                <input type="file" name="productimage3" class="form-control" required>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit" name="add" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
