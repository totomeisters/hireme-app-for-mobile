<?php
class ConnectDb
{
    private $db;
    private $api;

    function get_db()
    {
        //Get Db Info
        // $config = parse_ini_file('.env');
        $servername = "localhost";
        $serverusername = "u201145375_root";
        $serverpassword = "Hireme@pp722";
        $key = "hireme";
        $dbname = "u201145375_hiremedb";

        //DB Connection
        $this->db = new mysqli($servername, $serverusername, $serverpassword,$dbname);
        return $this->db;
       


    }
}