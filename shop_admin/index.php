<?php
session_start();
if (isset($_SESSION["admin"]) && $_SESSION["admin"])
    header('Location: admin_dash.php');
else
    header('Location: login.php');