<?php

class DBHandler
{
    const TABLE_CONTACT = 'contacts';
    var $connection;

    function __construct($host, $user, $password, $db)
    {
        $this->connection = new mysqli($host, $user, $password, $db);
        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }

        $this->ensureContactTable();
    }

    function insertContact($fname, $lname, $street, $postal, $city, $email)
    {
        if ($this->connection) {
            $insertStatement = $this->connection->prepare("INSERT INTO " . self::TABLE_CONTACT . "
            (`firstname`,`lastname`,`street`,`postal`,`city`,`email`) VALUES (?, ?, ?, ?, ?, ?)");


            $int_postal = intval($postal);
            $insertStatement->bind_param("sssiss", $fname, $lname, $street, $int_postal, $city, $email);

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

            $query_createMusicTable = "CREATE TABLE IF NOT EXISTS " . self::TABLE_CONTACT . "(
            `id`  INT(5) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
            `firstname` VARCHAR(100) NOT NULL ,
            `lastname` VARCHAR(100) NOT NULL,
            `street` VARCHAR(100),
            `postal` INTEGER,
            `city` VARCHAR(100),
            `email` VARCHAR(100)
            )";

            $statement = $this->connection->prepare($query_createMusicTable);
            if ($statement->execute()) {
                //echo "Table is there";
            };
        }
    }

    function fetchContacts()
    {
        $contacts = array();
        $query = "SELECT id, firstname, lastname, street, postal, city, email FROM " . self::TABLE_CONTACT;
        $selectStatement = $this->connection->prepare($query);
        $selectStatement->execute();

        $selectStatement->bind_result($id, $firstname, $lastname, $street, $postal, $city, $email);

        while ($selectStatement->fetch()) {
            // or do $albums [] = array($id, $artist, $title)
            array_push($contacts, array($id, $firstname, $lastname, $street, $postal, $city, $email));
        }

        return $contacts;
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