<?php
session_start();
error_reporting(0);
include('includes/config.php'); 

// 1) Check admin session
if (strlen($_SESSION['adminsession']) == 0) {
    header('location:logout.php');
    exit;
} else {

  
    if (!isset($_GET['subcatid']) || empty($_GET['subcatid'])) {
        echo "<h3>No subcategory specified</h3>";
        exit;
    }
    $subcatId = intval($_GET['subcatid']);

    // 3) If form is submitted, update the sub_category record
    if (isset($_POST['update'])) {
        $categoryId       = $_POST['category'];
        $subCategoryName  = $_POST['subCategoryName'];
        $updationDate     = date("Y-m-d H:i:s");

        try {
            $sql = "UPDATE sub_category
                    SET categoryId     = :categoryId,
                        subCategoryName= :subCategoryName,
                        updationDate   = :updationDate
                    WHERE id = :id
                    LIMIT 1";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':categoryId',      $categoryId,      PDO::PARAM_INT);
            $stmt->bindParam(':subCategoryName', $subCategoryName, PDO::PARAM_STR);
            $stmt->bindParam(':updationDate',    $updationDate,    PDO::PARAM_STR);
            $stmt->bindParam(':id',             $subcatId,        PDO::PARAM_INT);

            if ($stmt->execute()) {
                $msg = "Subcategory updated successfully.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        } catch (Exception $e) {
            $error = "Error updating subcategory: " . $e->getMessage();
        }
    }

    // 4) Fetch the existing subcategory row
    try {
        $sql = "SELECT id, categoryId, subCategoryName, creationDate, updationDate, createdBy
                FROM sub_category
                WHERE id = :id
                LIMIT 1";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $subcatId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo "<h3>Invalid Subcategory ID</h3>";
            exit;
        }
        $subcatRow = $stmt->fetch(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        echo "<h3>Error fetching subcategory: " . $e->getMessage() . "</h3>";
        exit;
    }

    // 5) Fetch all categories for the dropdown
    $catSql = "SELECT id, categoryName FROM shopping_category ORDER BY categoryName ASC";
    $catStmt = $dbh->prepare($catSql);
    $catStmt->execute();
    $allCategories = $catStmt->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SportsSync | Edit Subcategory</title>
    <!-- Bootstrap / SB Admin 2 CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <style>
        .errorWrap {
            padding: 10px;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            margin-bottom: 20px;
        }
        .succWrap {
            padding: 10px;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<div id="wrapper">
    <!-- Top nav -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom:0">
        <?php include_once('includes/header.php'); ?>
        <?php include_once('includes/leftbar.php'); ?>
    </nav>

    <!-- Main page content -->
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Edit Subcategory</h1>
            </div>
        </div>

        <!-- Display error / success messages -->
        <?php if(isset($error) && $error!=""): ?>
            <div class="errorWrap">
                <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
            </div>
        <?php elseif(isset($msg) && $msg!=""): ?>
            <div class="succWrap">
                <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
            </div>
        <?php endif; ?>

        <!-- Form to edit subcategory -->
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Subcategory Details</div>
                    <div class="panel-body">
                        <form method="post">
                            <!-- Category dropdown -->
                            <div class="form-group">
                                <label>Select Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    <?php
                                    if ($allCategories) {
                                        foreach($allCategories as $cat) {
                                            ?>
                                            <option value="<?php echo $cat->id; ?>"
                                                <?php if($cat->id == $subcatRow->categoryId) echo "selected"; ?>>
                                                <?php echo htmlentities($cat->categoryName); ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Subcategory Name -->
                            <div class="form-group">
                                <label>Subcategory Name</label>
                                <input type="text" name="subCategoryName" class="form-control"
                                       value="<?php echo htmlentities($subcatRow->subCategoryName); ?>" required>
                            </div>

                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                            <a href="manage-sub-category.php" class="btn btn-default">Cancel</a>
                        </form>
                    </div><!-- panel-body -->
                </div><!-- panel -->
            </div><!-- col-lg-8 -->
        </div><!-- row -->
    </div><!-- page-wrapper -->
</div><!-- wrapper -->

<!-- Scripts -->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>
