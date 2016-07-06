<?php
session_start();
require("db.php");

//if logged in, divert to main page
if(isset($_SESSION['SESS_LOGGEDIN']) == TRUE) {
header("Location: " . $config_basedir);
}

//if submit button is pressed...
if(isset($_POST['submit']))
{
	$loginsql = "SELECT * FROM logins WHERE username = '" . $_POST['userBox']. "' AND password = '" . $_POST['passBox']. "'";
	$loginres = mysql_query($loginsql);
	$numrows = mysql_num_rows($loginres);
	if($numrows == 1)
	{
	
		//2 things have to be tracked, user_number and order_number
		//user number is: $_SESSION['SESS_USERID']
		//order number is: $_SESSION['SESS_OREDRNUM']
	
		$loginrow = mysql_fetch_assoc($loginres);
		
		$_SESSION['SESS_LOGGEDIN'] = 1;
		$_SESSION['SESS_USERNAME'] = $loginrow['username'];
		$_SESSION['SESS_USERID'] = $loginrow['id'];
		
		//selects a specific customer with a given id with an unfinished order
		$ordersql = "SELECT id FROM orders WHERE customer_id = " . $_SESSION['SESS_USERID']. " AND status < 2";
		$orderres = mysql_query($ordersql);
		$orderrow = mysql_fetch_assoc($orderres);
		
		$_SESSION['SESS_ORDERNUM'] = $orderrow['id'];   //which number of order is this

		header("Location: " . $config_basedir);
	}
	else
	{
		header("Location: http://" . $HTTP_HOST
		. $_SERVER['REQUEST_URI'] . "?error=1");
	}
}

else
{
require("header.php");
?>
<h1>Customer Login</h1>
Please enter your username and password to
log into the websites. If you do not have an account, you can get one for free by <a
href="register.php">registering</a>.
<p>

<?php
if(isset($_GET['error'])) {
echo "<strong>Incorrect username/password</strong>";
}
?>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
	<table>
		<tr>
			<td>Username</td>
			<td><input type="textbox" name="userBox">
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="passBox">
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" value="Log in">
		</tr>
	</table>
</form>

<?php
}
require("footer.php");
?>