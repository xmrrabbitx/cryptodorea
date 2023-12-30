<?php

require(WP_PLUGIN_DIR . "/dorea/exceptions/databaseError.php");
require(WP_PLUGIN_DIR . "/dorea/abstracts/abstractDoreaDB.php");

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

   /**
    * create initial table users
    */
   public function createTable(){

    try{
        $tableQuery = "
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY,
                username VARCHAR(50)
            )
        ";
        return $this->db->exec($tableQuery);
    }catch(Exception $error){
       databaseError('- error in creating table sqlite3');
       throw new Exception($error);
    }
        
   }

   public function insertIntoTable(){

   }

   public function close() {
       $this->db = null;
   }
}