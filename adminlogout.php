<?php
session_start();

require("config.php");
session_unset("SESS_ADMINLOGGEDIN");
header("Location: " . $config_basedir);
?>