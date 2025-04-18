<?php
session_start();
error_reporting(0);
include('includes/config.php'); 

// Check if admin is logged in
if (strlen($_SESSION['adminsession']) == 0) {
    header('location:logout.php');
    exit;
} else {
    // --- Handle Delete Action ---
    if (isset($_GET['del'])) {
        $delId = intval($_GET['del']);
        try {
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id', $delId, PDO::PARAM_INT);
            $stmt->execute();
            $_SESSION['delmsg'] = "Product deleted successfully";
        } catch (Exception $e) {
            $_SESSION['delmsg'] = "Error deleting product: " . $e->getMessage();
        }
        echo "<script>window.location.href='manage-products.php';</script>";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Manage Products</title>
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <style>
        .errorWrap {
            padding: 10px;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .product-img {
            width: 60px;
            height: auto;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/leftbar.php'); ?>
    </nav>
    <!-- /Navigation -->

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Manage Products</h1>
            </div>
        </div>

        <!-- Display messages -->
        <?php if ($_SESSION['delmsg']) { ?>
            <div class="alert alert-danger">
                <?php echo htmlentities($_SESSION['delmsg']); ?>
            </div>
            <?php unset($_SESSION['delmsg']); ?>
        <?php } ?>

        <!-- Products Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Products Details
            </div>
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Sub Category</th>
                                <th>Category</th>
                                <th>Creation Date</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Fetch products joined with sub_category and shopping_category
                        $sql = "SELECT p.id, p.productName, p.productImage1, p.postingDate, p.UpdationDate,
                                       s.subCategoryName, c.categoryName
                                FROM products p
                                JOIN sub_category s ON p.subCategoryId = s.id
                                JOIN shopping_category c ON p.categoryId = c.id
                                ORDER BY p.id DESC";
                        $stmt = $dbh->prepare($sql);
                        $stmt->execute();
                        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;
                        if ($stmt->rowCount() > 0) {
                            foreach ($results as $row) {
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($cnt); ?></td>
                                    <td>
                                        <?php if (!empty($row->productImage1)) { ?>
                                            <img src="productimages/<?php echo htmlentities($row->productImage1); ?>" class="product-img" alt="Product" />
                                        <?php } ?>
                                        <br />
                                        <?php echo htmlentities($row->productName); ?>
                                    </td>
                                    <td><?php echo htmlentities($row->subCategoryName); ?></td>
                                    <td><?php echo htmlentities($row->categoryName); ?></td>
                                    <td><?php echo htmlentities($row->postingDate); ?></td>
                                    <td><?php echo htmlentities($row->UpdationDate); ?></td>
                                    <td>
                                        <!-- Edit Link -->
                                        <a href="edit-product.php?pid=<?php echo $row->id; ?>" class="btn btn-info btn-sm" title="Edit Product">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        &nbsp;&nbsp;
                                        <!-- Delete Link -->
                                        <a href="manage-products.php?del=<?php echo $row->id; ?>" 
                                           onclick="return confirm('Are you sure you want to delete this product?');" 
                                           class="btn btn-danger btn-sm" title="Delete Product">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                $cnt++;
                            }
                        } else {
                            echo "<tr><td colspan='7'>No products found.</td></tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div><!-- .dataTable_wrapper -->
            </div><!-- .panel-body -->
        </div><!-- .panel -->

    

    </div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<!-- jQuery -->
<script src="../vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<!-- DataTables JavaScript -->
<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
<!-- Custom Theme JavaScript -->
<script src="../dist/js/sb-admin-2.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
            pageLength: 10
        });
    });
</script>
</body>
</html>
<?php } ?>
