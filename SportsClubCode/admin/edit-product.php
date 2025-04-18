<?php
session_start();
error_reporting(0);
include('includes/config.php'); 

if (strlen($_SESSION['adminsession']) == 0) {
    header('location:logout.php');
    exit;
} else {
    if (!isset($_GET['pid']) || empty($_GET['pid'])) {
        echo "<h3>No product specified</h3>";
        exit;
    }
    $pid = intval($_GET['pid']);

    if (isset($_POST['update'])) {
        $category   = $_POST['category'];
        $subCategory= $_POST['subCategory'];
        $productName= $_POST['productName'];
        $productCompany = $_POST['productCompany'];
        $productPrice   = $_POST['productPrice'];
        $productPriceBeforeDiscount = $_POST['productPriceBeforeDiscount'];
        $productDescription        = $_POST['productDescription'];
        $shippingCharge     = $_POST['shippingCharge'];
        $productAvailability       = $_POST['productAvailability'];
        $updatedBy = $_SESSION['adminsession'];

        // Handle image uploads
        $imageFields = ['productImage1', 'productImage2', 'productImage3'];
        $uploadedImages = [];

        foreach ($imageFields as $field) {
            if (!empty($_FILES[$field]['name'])) {
                $imageName = time() . '_' . basename($_FILES[$field]['name']);
                $targetPath = "productimages/" . $imageName;
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
                    $uploadedImages[$field] = $imageName;
                }
            }
        }

        $updateImageSql = '';
        foreach ($uploadedImages as $field => $fileName) {
            $updateImageSql .= ", $field = '$fileName'";
        }

        try {
            $sql = "UPDATE products
                    SET categoryid = :category,
                        subCategoryid = :subCategory,
                        productName = :pName,
                        productCompany = :pCompany,
                        productPrice = :pPrice,
                        productPriceBeforeDiscount = :pPriceBD,
                        productDescription = :pDesc,
                        shippingCharge = :pship,
                        productAvailability = :pAvail,
                        lastUpdatedBy = :updatedBy,
                        UpdationDate = NOW()
                        $updateImageSql
                    WHERE id = :pid
                    LIMIT 1";

            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':category',   $category,   PDO::PARAM_INT);
            $stmt->bindParam(':subCategory',$subCategory,PDO::PARAM_INT);
            $stmt->bindParam(':pName',      $productName,PDO::PARAM_STR);
            $stmt->bindParam(':pCompany',   $productCompany,PDO::PARAM_STR);
            $stmt->bindParam(':pPrice',     $productPrice,PDO::PARAM_STR);
            $stmt->bindParam(':pPriceBD',   $productPriceBeforeDiscount,PDO::PARAM_STR);
            $stmt->bindParam(':pDesc',      $productDescription,PDO::PARAM_STR);
            $stmt->bindParam(':pship',      $shippingCharge,PDO::PARAM_STR);
            $stmt->bindParam(':pAvail',     $productAvailability,PDO::PARAM_STR);
            $stmt->bindParam(':updatedBy',  $updatedBy,  PDO::PARAM_STR);
            $stmt->bindParam(':pid',        $pid,        PDO::PARAM_INT);

            $stmt->execute();
            $msg = "Product updated successfully.";
        } catch (Exception $e) {
            $error = "Error updating product: " . $e->getMessage();
        }
    }

    try {
        $sql = "SELECT * FROM products WHERE id = :pid LIMIT 1";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo "<h3>Invalid product ID</h3>";
            exit;
        }
        $productRow = $stmt->fetch(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        echo "<h3>Error fetching product: " . $e->getMessage() . "</h3>";
        exit;
    }

    $catSql = "SELECT id, categoryName FROM shopping_category ORDER BY categoryName ASC";
    $catStmt = $dbh->prepare($catSql);
    $catStmt->execute();
    $allCategories = $catStmt->fetchAll(PDO::FETCH_OBJ);

    $subcatSql = "SELECT id, subCategoryName FROM sub_category ORDER BY subCategoryName ASC";
    $subcatStmt = $dbh->prepare($subcatSql);
    $subcatStmt->execute();
    $allSubCategories = $subcatStmt->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Edit Product</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/leftbar.php'); ?>
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Edit Product</h1>
            </div>
        </div>

        <?php if(isset($error)): ?><div class="errorWrap">ERROR: <?php echo htmlentities($error); ?></div><?php endif; ?>
        <?php if(isset($msg)): ?><div class="succWrap">SUCCESS: <?php echo htmlentities($msg); ?></div><?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Product Details</div>
                    <div class="panel-body">
                        <form method="post" enctype="multipart/form-data">
                            <!-- Your existing fields -->
 <div class="form-group">
                                <label>Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    <?php
                                    if($allCategories) {
                                        foreach($allCategories as $cat) {
                                            ?>
                                            <option value="<?php echo $cat->id; ?>"
                                                <?php if($cat->id == $productRow->categoryid) echo "selected"; ?>>
                                                <?php echo htmlentities($cat->categoryName); ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- SubCategory -->
                            <div class="form-group">
                                <label>Sub Category</label>
                                <select name="subCategory" class="form-control" required>
                                    <option value="">-- Select Subcategory --</option>
                                    <?php
                                    if($allSubCategories) {
                                        foreach($allSubCategories as $subcat) {
                                            ?>
                                            <option value="<?php echo $subcat->id; ?>"
                                                <?php if($subcat->id == $productRow->subCategoryid) echo "selected"; ?>>
                                                <?php echo htmlentities($subcat->subCategoryName); ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Product Name -->
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="productName" class="form-control" 
                                       value="<?php echo htmlentities($productRow->productName); ?>" required>
                            </div>

                            <!-- Product Company -->
                            <div class="form-group">
                                <label>Product Company</label>
                                <input type="text" name="productCompany" class="form-control"
                                       value="<?php echo htmlentities($productRow->productCompany); ?>" required>
                            </div>

                            <!-- Product Price Before Discount -->
                            <div class="form-group">
                                <label>Price Before Discount</label>
                                <input type="text" name="productPriceBeforeDiscount" class="form-control"
                                       value="<?php echo htmlentities($productRow->productPriceBeforeDiscount); ?>" required>
                            </div>

                            <!-- Selling Price -->
                            <div class="form-group">
                                <label>Selling Price</label>
                                <input type="text" name="productPrice" class="form-control"
                                       value="<?php echo htmlentities($productRow->productPrice); ?>" required>
                            </div>

                            <!-- Product Description -->
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="productDescription" class="form-control" rows="5" required>
                                    <?php echo htmlentities($productRow->productDescription); ?>
                                </textarea>
                            </div>

                            <!-- Shipping Charge -->
                            <div class="form-group">
                                <label>Shipping Charge</label>
                                <input type="text" name="shippingCharge" class="form-control"
                                       value="<?php echo htmlentities($productRow->shippingCharge); ?>" required>
                            </div>

                            <!-- Availability -->
                            <div class="form-group">
                                <label>Availability</label>
                                <select name="productAvailability" class="form-control" required>
                                    <option value="In Stock" 
                                        <?php if($productRow->productAvailability=="In Stock") echo "selected"; ?>>
                                        In Stock
                                    </option>
                                    <option value="Out of Stock"
                                        <?php if($productRow->productAvailability=="Out of Stock") echo "selected"; ?>>
                                        Out of Stock
                                    </option>
                                </select>
                            </div>

                            <!-- Image Uploads -->
                            <div class="form-group">
                                <label>Image 1</label><br>
                                <?php if($productRow->productImage1): ?><img src="productimages/<?php echo $productRow->productImage1; ?>" width="100"><br><?php endif; ?>
                                <input type="file" name="productImage1">
                            </div>

                            <div class="form-group">
                                <label>Image 2</label><br>
                                <?php if($productRow->productImage2): ?><img src="productimages/<?php echo $productRow->productImage2; ?>" width="100"><br><?php endif; ?>
                                <input type="file" name="productImage2">
                            </div>

                            <div class="form-group">
                                <label>Image 3</label><br>
                                <?php if($productRow->ProductImage3): ?><img src="productimages/<?php echo $productRow->ProductImage3; ?>" width="100"><br><?php endif; ?>
                                <input type="file" name="productImage3">
                            </div>

                            <!-- Submit -->
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                            <a href="manage-products.php" class="btn btn-default">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>
