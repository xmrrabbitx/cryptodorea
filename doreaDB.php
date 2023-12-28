<?php

/**
 * an interface to connect to a SQLite Database
 */
class DoreaDB {
   private $db;

   function __construct() {
       try {
           $this->db = new PDO('sqlite:test.db');
           $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           echo "Opened database successfully\n";
       } catch (PDOException $e) {
           echo 'Connection failed: ' . $e->getMessage();
       }
   }

   function close() {
       $this->db = null;
   }
}

// Create an instance of the DoreaDB class
$db = new DoreaDB();

// Perform database operations as needed...

// Close the database connection when done
$db->close();