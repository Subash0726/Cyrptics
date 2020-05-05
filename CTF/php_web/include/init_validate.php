<?php
/**
 * Created by PhpStorm.
 * User: azizt
 * Date: 2/21/2017
 * Time: 8:24 PM
 */
if (!isset($_SESSION[$ses_uname_key])) {
    header("Location: index.php");
    exit;
}