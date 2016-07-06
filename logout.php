<?php
session_start();
require('config.php');

session_unset("SESS_LOGGEDIN");
session_unset("SESS_USERNAME");
session_unset("SESS_USERID");

header("Location: " . $config_basedir);

?>