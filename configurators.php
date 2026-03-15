<?php

require_once "connections.php";

function load_config()
{
    if(!file_exists("config.json")) return null;

    return json_decode(file_get_contents("config.json"),true);
}

function save_config($cfg)
{
    file_put_contents("config.json",json_encode($cfg,JSON_PRETTY_PRINT));
}

function test_connection($cfg)
{
    try{

        $dsn="mysql:host=".$cfg["host"].";port=".$cfg["port"];

        new PDO($dsn,$cfg["user"],$cfg["password"]);

        return true;

    }catch(Exception $e){

        $GLOBALS["LAST_INFO"]=$e->getMessage();

        return false;
    }
}

function db()
{
    static $pdo;

    if($pdo) return $pdo;

    $cfg=load_config();

    if(!$cfg) return null;

    try{

        $dsn="mysql:host=".$cfg["host"].";port=".$cfg["port"];

        $pdo=new PDO($dsn,$cfg["user"],$cfg["password"]);

        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    }catch(Exception $e){

        $GLOBALS["LAST_INFO"]=$e->getMessage();

        return null;
    }

    return $pdo;
}

function db_with_database()
{
    $cfg=load_config();

    if(!$GLOBALS["ACTIVE_DB"]) return null;

    $dsn="mysql:host=".$cfg["host"].";dbname=".$GLOBALS["ACTIVE_DB"].";port=".$cfg["port"];

    return new PDO($dsn,$cfg["user"],$cfg["password"]);
}
