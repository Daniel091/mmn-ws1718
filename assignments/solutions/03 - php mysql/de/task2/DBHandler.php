<?php

class DBHandler
{
    const TABLE_NOTES = 'notes';
    var $connection;

    function __construct($host, $user, $password, $db)
    {
        $this->connection = new mysqli($host, $user, $password, $db);
        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }

        $this->ensureContactTable();
    }

    // Follow prepare, bind, execute
    function insertNote($title, $text)
    {
        if ($this->connection) {
            $insertStatement = $this->connection->prepare("INSERT INTO " . self::TABLE_NOTES . "
            (`title`,`description`) VALUES (?, ?)");


            $insertStatement->bind_param("ss", $title, $text);

            if ($insertStatement->execute()) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    // Follow prepare, bind, execute
    function deleteNote($noteId)
    {
        if ($this->connection) {
            $insertStatement = $this->connection->prepare("DELETE FROM " . self::TABLE_NOTES . " WHERE `id`=?");

            $insertStatement->bind_param("i", $noteId);

            if ($insertStatement->execute()) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * makes sure that the contact table is present in the database
     * before any interaction occurs with it.
     */
    function ensureContactTable()
    {
        if ($this->connection) {

            $query_createMusicTable = "CREATE TABLE IF NOT EXISTS " . self::TABLE_NOTES . "(
            `id`  INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
            `title` VARCHAR(100) NOT NULL ,
            `description` VARCHAR(500)
            )";

            $statement = $this->connection->prepare($query_createMusicTable);
            if ($statement->execute()) {
                //echo "Table is there";
            };
        }
    }

    function fetchNotes()
    {
        $notes = array();
        $query = "SELECT id, title, description FROM " . self::TABLE_NOTES;
        $selectStatement = $this->connection->prepare($query);
        $selectStatement->execute();

        $selectStatement->bind_result($id, $title, $text);

        while ($selectStatement->fetch()) {
            // or do $albums [] = array($id, $artist, $title)
            array_push($notes, array("id" => $id, "title" => $title, "text" => $text));
        }

        return $notes;
    }

    /**
     * useful to sanitize data before trying to insert it into the database.
     * @param $string String to be escaped from malicious SQL statements
     */
    function sanitizeInput(&$string)
    {
        $string = $this->connection->real_escape_string($string);
    }
}