<?php
session_start();
if (!isset($_SESSION["logged"])) {return;}
session_destroy();
header("Location: index.php");