<?php

require('db.php');
require("functions.php");

//if valid id exists, it is stores in the variable $validid, else it redirected to the base page
$validid= pf_validate_number($_GET['id'], "redirect", $config_basedir);

require('header.php');

$prodcatsql = "SELECT * FROM products WHERE cat_id = " . $_GET['id'] . ";";
$prodcatres = mysql_query($prodcatsql);
$numrows = mysql_num_rows($prodcatres);

if($numrows == 0)
{
	echo "<h1>No products</h1>";
	echo "There are no products in this category.";
}
else{
	echo "<table cellpadding='10'>";
	while($prodrow= mysql_fetch_assoc($prodcatres))
	{
		echo '<tr>';
		if(empty($prodrow['image']))
		{
			echo "<td><img src='orange.jpg' alt='".$prodrow['name']."'></td>";
		}else{
			echo "<td><img src='".$prodrow['image']."' alt=".$prodrow['name']."'></td>";
		}
		
		echo '<td>';
		echo "<h3>" . $prodrow['name'] . "</h3>";
		echo "<p>" . $prodrow['description'].'</p>';
		echo "<p><strong>OUR PRICE: &pound;". sprintf('%.2f', $prodrow['price']) . "</strong></p>";
		echo "<p>[<a href='addtobasket.php?id=". $prodrow['id'] . "'>buy</a>]</p>";
		echo "</td>";
		echo "</tr>";
		
	}
	echo '<table>';
	
}
require("footer.php");
?>