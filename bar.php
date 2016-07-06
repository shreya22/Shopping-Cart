<h1>Product Categories</h1>
<ul>
	<?php
		$catsql = "SELECT * FROM categories;";
		$catres = mysql_query($catsql);
		while($catrow = mysql_fetch_assoc($catres))
		{
			echo "<li><a href='" . $config_basedir
			. "/products.php?id=" . $catrow['id'] . "'>"
			. $catrow['name'] . "</a></li>";
		}
	?>
</ul>