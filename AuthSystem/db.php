<?php
include('./init.php');
$server = "localhost";
$serverUserName = "root";
$serverPass = "";

$conn = mysqli_connect($server,$serverUserName,$serverPass);
$mainDB = $conn;
$dbCheck =  mysqli_select_db($mainDB,'chat');




function executeMultiSql($mysqli,$sqlReq){
    $sql = explode(';', $sqlReq);
        foreach ($sql as $s) {
            mysqli_query($mysqli, $s . ';');
            
        }
}


/* function validateDatabase($isDbExist,$mainDB,$conn){
    if (!$isDbExist)
      $sql = 'CREATE DATABASE ecommerceMainDb; 
              USE ecommerceMainDb;
              SELECT database();
              CREATE TABLE users(id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,username VARCHAR(30) NOT NULL,password VARCHAR(30) NOT NULL,contact TEXT(10000) NOT NULL,
                                preferences TEXT(65500) NOT NULL,cart TEXT(65000) NOT NULL,billingInfo TEXT(6000),shopInfo TEXT(65500) NOT NULL) ;
              CREATE TABLE sellers(id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,username VARCHAR(30) NOT NULL,password VARCHAR(30) NOT NULL,email VARCHAR(30) NOT NULL,
                                billingInfo TEXT(10000) NOT NULL,items TEXT(65000)NOT NULL) ;';
      executeMultiSql($conn,$sql);
      
      $isDbExist =  mysqli_select_db($mainDB,'ecommerceMainDb');
      validateDatabase($isDbExist,$mainDB,$conn);
      
  }
 */

?>