<?php
  try {
    $DBBasketball = new PDO("mysql:host=localhost;dbname=basketball", "root", "root");
    $DBBasketball->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $DBConnected = true;
  } catch(PDOException $e) {
  }
?>