<?php
    session_set_cookie_params(0, '/', $_SERVER['HTTP_HOST'], $secure=true, $httponly=true);
    session_start();

    require('includes/forceapi.class.php');    

    $force24 = new Force24API();
    
    if(isset($_COOKIE['f24_personId']) &&
        $force24->validateGuid($_COOKIE['f24_personId'])) {
        $f24_guid = $_COOKIE['f24_personId'];
    }
    
    if(isset($_GET['f24_pid']) &&
        $force24->validateGuid($_GET['f24_pid'])){
        $f24_guid = $_GET['f24_pid'];
    }
    
    if(isset($f24_guid)) {
        $force24->setLogin("client username", "client password"); // Replace with correct portal username and password
        
        // Get the token outside of the class as you may want to do multiple requests in one script run
        $token = $force24->getToken();
        
        if($token['access_token']) {
            $force24->setToken($token['access_token']);
            
            // Detect a GUID and request the personId
            $intId = $force24->getPersonId($f24_guid);
            
            if(is_integer($intId)) {
                $f24_pid = $intId;
            } 
            
            // Retrieve the data
            $visitor_info = $force24->getContactInfo($f24_pid); 
        } 
    }

    // die (print_r  ($visitor_info)); // You can use this to see if your GUID visitor information is correctly pulling through
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="Force24" content="Brennan Jenkins" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="cache-control" content="no-cache"/>
    
    <link href="build/css/styles.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="https://www.google.com/s2/favicons?domain=https://force24.co.uk" type="image/x-icon">
    <link rel="shortcut icon" href="https://www.google.com/s2/favicons?domain=https://force24.co.uk" type="image/x-icon">

    <title>Title Here</title>
</head>
<body>

<header>    
    
</header>

