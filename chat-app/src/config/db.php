<?php

 class Db {

    public function connect() {
        $dbConnection = new PDO("sqlite:../../chat-app.db");
        $dbConnection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
} 

