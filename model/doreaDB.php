<?php

include_once("abstractDoreaDB.php");

/**
 * an interface to connect to a PDO_SQLite3 Database
 */
class DoreaDB extends abstractDoreaDb {

   private $db;
   private $dbPath;
   private $createTableQuery;

   public function __construct() {
    $dbPath = __DIR__  . '/dorea.db';
       try {
           $this->db = new PDO('sqlite:'.$dbPath);
           $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           echo "Opened database successfully\n";
       } catch (PDOException $e) {
           echo 'Connection failed: ' . $e->getMessage();
       }
   }

   public function createTable(){

    $tableQuery = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY,
            username VARCHAR(50)
        )
    ";

    return $this->db->exec($tableQuery);
    
   }

   public function close() {
       $this->db = null;
   }
}