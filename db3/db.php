<?php

namespace db;


class db
{

    private static $_instance = null;

    public static function getInstance($dbname = 'dbnd3', $username = 'symfony', $password = 'symfony') {
        if (null === self::$_instance) {
            self::$_instance = new \PDO("mysql:host=localhost;dbname=$dbname;charset=utf8", $username, $password);
            self::$_instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$_instance;
    }

}