<?php
session_start(); 
  $host = 'localhost'; // Dirección del servidor
  $dbname = 'rfa_basededatos'; // Nombre de tu base de datos
  $username = 'root'; // Tu usuario de MySQL
  $password = ''; // Tu contraseña de MySQL
  
  try {
       //Crear una nueva instancia de PDO
      $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
      $pdo = new PDO($dsn, $username, $password);
  
      //Configurar PDO para lanzar excepciones en caso de errores
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      
  } catch (PDOException $e) {
       //Manejo de errores
    echo "Error en la conexión: " . $e->getMessage();
  }
  
  


?>