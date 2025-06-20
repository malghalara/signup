<?php
require_once 'includes/functions.php';

// Destroy session and redirect
session_destroy();
redirectTo('login.php');
?>