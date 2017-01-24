<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    require_once 'vendor/autoload.php';

    try{
        
        $status = new MCS\YodelParcelStatus(
            'JJD0002256702001360', 'EC1V3QW'
        );
        
        $a = $status->getStatus();
        
        print_r($a);
        
        
    } catch (Exception $e) {
        print_r($e->getMessage());    
    }
