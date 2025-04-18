<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['adminsession'])==0)
{   
    header('location:logout.php');
}
else{ 
if(isset($_POST['submit']))
{
    $currentimage=$_POST['currentimage'];
    $imagepath="productimages/".$currentimage;
    $productimage1=$_FILES["productimage1"]["name"];
    
    $imgnewfile=md5($productimage1.time()).substr($productimage1,-4);
    move_uploaded_file($_FILES["productimage1"]["tmp_name"],"productimages/".$imgnewfile);
    
    $updatedby=$_SESSION['adminsession'];
    $pid=intval($_GET['id']);
    
    $sql="UPDATE products SET productImage1=:imgnewfile, lastUpdatedBy=:updatedby WHERE id=:pid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':imgnewfile',$imgnewfile,PDO::PARAM_STR);
    $query->bindParam(':updatedby',$updatedby,PDO::PARAM_INT);
    $query->bindParam(':pid',$pid,PDO::PARAM_INT);
    $query->execute();
    
    unlink($imagepath);
    echo "<script>alert('Product image updated successfully');</script>";
    echo "<script>window.location.href='manage-products.php'</script>";
}
?>
