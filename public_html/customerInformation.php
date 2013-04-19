<?php
require('includes/application_top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <script>
    <?php if ($_SESSION['customer_id']) { ?>
    parent.setValue("isLoggedIn","true");
    <?php } else { ?>
    parent.setValue("isLoggedIn","false");
    <?php } ?>
    parent.setValue("total",<?=$_SESSION["cart"]->total;?>);
    parent.setValue("totalItems",<?=$_SESSION["cart"]->count_contents();?>);
    parent.setValue("cartID","<?=$_SESSION["cart"]->cartID;?>");
    parent.setValue("securityToken","<?=$_SESSION["securityToken"];?>");
    parent.setValue("customer_first_name","<?=$_SESSION["customer_first_name"];?>");
    </script>
</head>

<body>
<?
print "TOTAL = " . $_SESSION["cart"]->total . "<BR>";
print "ITEMS = " . $_SESSION["cart"]->count_contents() . "<BR>";
print "CART ID = " . $_SESSION["cart"]->cartID . "<BR>";
//print "<pre>";
//print_r($_SESSION);
//print_r($_SERVER);
//print "</pre>";

?>
</body>
</html>

