<?php
session_start();
unset($_SESSION['secure_access']);
header('Location: secure.php');
exit;
