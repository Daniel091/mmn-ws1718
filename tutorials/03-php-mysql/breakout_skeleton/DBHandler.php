<?php

class DBHandler
{
    const TABLE_ALBUMS = 'albums';
    var $connection;

    /**
     * @param $host String host to connect to.
     * @param $user String username to use with the connection. Make sure to grant all necessary privileges.
     * @param $password String password belonging to the username.
     * @param $db String name of the database.
     */
    function __construct($host, $user, $password, $db)
    {
        $this->connection = new mysqli($host, $user, $password, $db);
        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }

        $this->ensureAlbumsTable();
    }

    /*********************
     * DESTRUCTOR
     */

    /**
     * creates a database record for the given artist and title.
     * @param $artistName String name of te album's artist
     * @param $albumTitle String title of the album
     * @return bool true for success, false for error
     */
    function insertAlbum($artistName, $albumTitle)
    {
        if ($this->connection) {
            $insertStatement = $this->connection->prepare("INSERT INTO `musikTable`
            (`artist`, `title`) VALUES (?, ?)");

            $insertStatement->bind_param("ss", $artistName, $albumTitle);

            if ($insertStatement->execute()) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * makes sure that the albums table is present in the database
     * before any interaction occurs with it.
     */
    function ensureAlbumsTable()
    {
        if ($this->connection) {

            $query_createMusicTable = "CREATE TABLE IF NOT EXISTS `musikTable` (
            `id`  INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
            `artist` VARCHAR(100) NOT NULL ,
            `title` VARCHAR(100) NOT NULL
            )";

            $statement = $this->connection->prepare($query_createMusicTable);
            if ($statement->execute()) {

            };
        }
    }

    /**
     * @return array of rows (id, artist, title)
     */
    function fetchAlbums()
    {
        $albums = array();
        $query_albums = "SELECT id, artist, title FROM `musikTable`";
        $selectStatement = $this->connection->prepare($query_albums);
        $selectStatement->execute();

        $selectStatement->bind_result($id, $artist, $title);

        while($selectStatement->fetch()){

            // or do $albums [] = array($id, $artist, $title)
            array_push($albums, array($id, $artist, $title));
        }

        return $albums;
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