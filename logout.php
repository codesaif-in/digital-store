<?php
require_once('includes/functions.php');

// Destroy all session data
$_SESSION = array();
session_destroy();

// Redirect to home page
redirect('index.php');