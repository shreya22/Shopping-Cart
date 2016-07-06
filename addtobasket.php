<?php
session_start();

require("db.php");
require("functions.php");

//checking if the passed is is valid or not, if not, redirected
$validid = pf_validate_number($_GET['id'],
"redirect", $config_basedir);

//selecting product form the products table with the same id as the chosen one 
$prodsql = "SELECT * FROM products WHERE id = " . $_GET['id'] . ";";
$prodres = mysql_query($prodsql);
$numrows = mysql_num_rows($prodres);
$prodrow = mysql_fetch_assoc($prodres);

if($numrows == 0)  //no such product found.....redirect to base page
{header("Location: " . $config_basedir);
}
else
{
	if(isset($_POST['submit']))   //if the submt button was clicked
	{
		if($_SESSION['SESS_ORDERNUM'])   //if the order number is set ie. if the order is already open
		{	//inset statement adds product ida and quantity to orderitems table, where order_id is SESS_ORDERNUM
			
			$itemsql = "INSERT INTO orderitems(order_id, product_id, quantity) VALUES(". $_SESSION['SESS_ORDERNUM'] . ", ". $_GET['id'] . ", ". $_POST['amountBox'] . ")";
			mysql_query($itemsql);
		}else{
			//no order number is set, means no order is open
			//first, an order must be created in orders table 
			//now, we'll check if the user is logged in or not
			
			if($_SESSION['SESS_LOGGEDIN']){  //if the user is logged in
				$sql = "INSERT INTO orders(customer_id, registered, date) VALUES(". $_SESSION['SESS_USERID'] . ", 1, NOW())";
				mysql_query($sql);
				$_SESSION['SESS_ORDERNUM'] = mysql_insert_id(); //returns an id based on auto increment
				
				//insert fields into orderitems table
				$itemsql = "INSERT INTO orderitems(order_id, product_id, quantity) VALUES(". $_SESSION['SESS_ORDERNUM']
				. ", " . $_GET['id'] . ", ". $_POST['amountBox'] . ")";
				mysql_query($itemsql);
			}else{          //users not registered
				
				//session_id() returns the session ID if a session exists.
				
				$sql = "INSERT INTO orders(registered, date, session) VALUES(". "0, NOW(), '" . session_id() . "')";
				mysql_query($sql);
				$_SESSION['SESS_ORDERNUM'] = mysql_insert_id();
				
				$itemsql = "INSERT INTO orderitems(order_id, product_id, quantity) VALUES("
				. $_SESSION['SESS_ORDERNUM'] . ", " . $_GET['id'] . ", ". $_POST['amountBox'] . ")";
				
				mysql_query($itemsql);
			}
	
		}
	
		$totalprice = $prodrow['price'] * $_POST['amountBox'];
		$updsql = "UPDATE orders SET total = total + ". $totalprice . " WHERE id = ". $_SESSION['SESS_ORDERNUM'] . ";";
		$updres= mysql_query($updsql);
		
		header("Location: " . $config_basedir . "showcart.php");
	}
	else{
		require("header.php");
		
		echo "<form action='addtobasket.php?id=". $_GET['id'] . "' method='POST'>";
		echo "<table cellpadding='10'>";
		
		echo "<tr>";
		if(empty($prodrow['image'])) {
			echo "<td><img
			src='tea.jpg' width='50' alt='". $prodrow['name'] . "'></td>";
		}
		else {
			echo "<td>
			<img src='".$prodrow['image']. "' width='50' alt='" . $prodrow['name']. "'></td>";
		}
		
		echo "<td>" . $prodrow['name'] . "</td>";
		echo "<td>Select Quantity <select name='amountBox'>";
		for($i=1;$i<=100;$i++)
		{
			echo "<option>" . $i . "</option>";
		}
		echo "</select></td>";
		
		echo "<td><strong>&pound;". sprintf('%.2f', $prodrow['price']). "</strong></td>";
		echo "<td><input type='submit' name='submit' value='Add to basket'></td>";
		echo "</tr>";
		
		echo "</table>";
		echo "</form>";
	}
}
require("footer.php");
?>