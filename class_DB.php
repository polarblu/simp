<?php

/**
        Static Class DB, Simple database class.
**/

Class DB
{
        private static $hostname = "localhost";
        private static $username = "user";
        private static $password = "pass";
        private static $database = "db";

        private static $mysqli = NULL;

        private static $connected = false;

        public static $result;

        // Connect to database.
        private static function Connect()
        {
                self::$mysqli = new mysqli(self::$hostname, self::$username, self::$password, self::$database);

                self::$connected = true;
        }

        // Checks if database is connected, and connect if it isn't.
        private static function connCheck()
        {
                if (!self::$connected)
                        self::Connect();

                return true;
        }

        public static function Query($q)
        {
                dbLog( sprintf("[%s] Query called: %s\n", date("d.m.y H:i:s"), $q) );

                self::connCheck();

                self::$result = self::$mysqli->query($q);

                if (self::$mysqli->errno)
                {

                        dbLog(
                                sprintf("Query Error: %s\n(Query: %s)\n", self::$mysqli->error, $q)
                        );

                        exit;
                }
        }

        // Return the number results for the last query
        public static function numResults()
        {
                return self::$result->num_rows;
        }

        public static function getResult()
        {
                if (self::$result)
                        return self::$result->fetch_assoc();
        }


        public static function useDatabase($database)
        {
                self::$database = $database;
                self::Connect();
        }
}

function dbLog($msg){
        $fh = fopen("db.log", "a+");
        fwrite($fh, $msg . "\n\n");
        fclose($fh);

        return true;
}

?>
