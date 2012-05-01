<?php

/* ==================================================================

    URL Joining
    Construct the URLs for each service based on configuration

================================================================== */

    # Sickbeard URL Joining
    if($config['sickbeardHTTPS']) {$sickbeardProtocol = "https";} else {$sickbeardProtocol = "http";}
    if($config['sickbeardUsername']) {
        $sickbeardURL = $sickbeardProtocol."://".$config['sickbeardUsername'].":".$config['sickbeardPassword']."@".$config['sickbeardURL'].":".$config['sickbeardPort'];
    } else {
        $sickbeardURL = $sickbeardProtocol."://".$config['sickbeardURL'].":".$config['sickbeardPort'];
    }

    # Sabnzbd URL Joining
    if($config['sabnzbdHTTPS']) {$sabProtocol = "https";} else {$sabProtocol = "http";}
    if($config['sabnzbdUsername']) {
        $sabURL = $sabProtocol."://".$config['sabnzbdUsername'].":".$config['sabnzbdPassword']."@".$config['sabnzbdURL'].":".$config['sabnzbdPort'];
    } else {
        $sabURL = $sabProtocol."://".$config['sabnzbdURL'].":".$config['sabnzbdPort'];
    }

    # Transmission Joining
    if($config['transmissionUsername']) {
        $transmissionURL = "http://".$config['transmissionURL'].":".$config['transmissionPort']."@".$config['transmissionUsername'].":".$config['transmissionPassword'];
    } else {
        $transmissionURL = "http://".$config['transmissionURL'].":".$config['transmissionPort'];
    }

    # Headphones Joining
    if($config['headphonesHTTPS']) {$headphonesProtocol = "https";} else {$headphonesProtocol = "http";}
    $headphonesURL = $headphonesProtocol."://".$config['headphonesURL'].":".$config['headphonesPort'];

    # Couchpotato Joining
    if($config['couchpotatoHTTPS']) {$couchpotatoProtocol = "https";} else {$couchpotatoProtocol = "http";}
    $couchpotatoURL = $couchpotatoProtocol."://".$config['couchpotatoURL'].":".$config['couchpotatoPort'];

/* ==================================================================

    ByteSize Function
    http://www.phpfront.com/php/Convert-Bytes-to-corresponding-size/

================================================================== */

function ByteSize($bytes)  
    { 
    $size = $bytes / 1024; 
    if($size < 1024) 
        { 
        $size = number_format($size, 2); 
        $size .= ' KB'; 
        }  
    else  
        { 
        if($size / 1024 < 1024)  
            { 
            $size = number_format($size / 1024, 2); 
            $size .= ' MB'; 
            }  
        else if ($size / 1024 / 1024 < 1024)   
            { 
            $size = number_format($size / 1024 / 1024, 2); 
            $size .= ' GB'; 
            }  
        } 
    return $size; 
    } 

?>