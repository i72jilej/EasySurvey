<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo 'IP INTERNA: '.$_SERVER['SERVER_ADDR'].'<br><br>';


if($_SERVER["HTTP_X_FORWARDED_FOR"]) 
{ 
    if($pos=strpos($_SERVER["HTTP_X_FORWARDED_FOR"]," ")) 
    { 
        echo "IP local: ".substr($_SERVER["HTTP_X_FORWARDED_FOR"],0,$pos)." - IP Pública: ".substr($_SERVER["HTTP_X_FORWARDED_FOR"],$pos+1); 
        $hostlocal=substr($_SERVER["HTTP_X_FORWARDED_FOR"],$pos+1); 
    }else{ 
        echo "&ippublica=".$_SERVER["HTTP_X_FORWARDED_FOR"]; 
        $hostlocal=$_SERVER["HTTP_X_FORWARDED_FOR"]; 
    } 
    if($_SERVER["REMOTE_ADDR"]) 
        echo " - Proxy: ".$_SERVER["REMOTE_ADDR"]; 
}else{ 
    echo "&ippublica=".$_SERVER["REMOTE_ADDR"]; 
    $hostlocal=$_SERVER["REMOTE_ADDR"]; 
    if($hostlocal!=$_SERVER["REMOTE_ADDR"]) 
        echo " - Hostname: ".$hostlocal; 
} 
$hostname=gethostbyaddr($hostlocal); 
if($hostlocal!=$hostname) 
    echo "&hostname=".$hostname; 

   if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; }   
   elseif (isset($_SERVER['HTTP_VIA'])) { $ip = $_SERVER['HTTP_VIA']; }   
   elseif (isset($_SERVER['REMOTE_ADDR'])) { $ip = $_SERVER['REMOTE_ADDR']; } 
   else { $ip = "Desconocido"; } 
   echo "&ip=" . $ip . "&dns=".$_SERVER['HTTP_USER_AGENT'];  
   
   ?>