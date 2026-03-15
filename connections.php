<?php

require_once "configurators.php";

function set_info($msg){
    $GLOBALS["LAST_INFO"]=$msg;
}

function draw_header(){

    system("clear");

    $cfg=load_config();

    echo "================================================\n";
    echo "          PCLIAMYADMIN BY JASYSCOM\n";
    echo "================================================\n";

    if($cfg){
        echo "SERVER: ".$cfg["host"].":".$cfg["port"]."\n";
    }else{
        echo "SERVER: not configured\n";
    }

    echo "------------------------------------------------\n";

    echo "MENU: ".$GLOBALS["CURRENT_MENU"]."\n";
    echo "ACTIVE DB: ".($GLOBALS["ACTIVE_DB"] ?? "none")."\n";

    echo "------------------------------------------------\n";
    echo "INFO: ".$GLOBALS["LAST_INFO"]."\n";
    echo "------------------------------------------------\n\n";
}

/* =========================
TABLE GRID
========================= */

function render_table($rows){

    if(!$rows){
        echo "No data\n";
        return;
    }

    $headers=array_keys($rows[0]);

    $width=[];

    foreach($headers as $h){
        $width[$h]=strlen($h);
    }

    foreach($rows as $row){
        foreach($row as $k=>$v){
            $width[$k]=max($width[$k],strlen((string)$v));
        }
    }

    $line="+";

    foreach($width as $w){
        $line.=str_repeat("-", $w+2)."+";
    }

    echo $line."\n";

    echo "|";

    foreach($headers as $h){
        printf(" %-". $width[$h]."s |",$h);
    }

    echo "\n".$line."\n";

    foreach($rows as $row){

        echo "|";

        foreach($headers as $h){
            printf(" %-".$width[$h]."s |",$row[$h]);
        }

        echo "\n";
    }

    echo $line."\n";
}

function render_table_index($rows,$field){

    $i=1;
    $data=[];

    foreach($rows as $r){
        $data[]=["#" => $i++,$field => $r[$field]];
    }

    render_table($data);
}

/* =========================
DATABASE
========================= */

function show_databases(){

    $stmt=db()->query("SHOW DATABASES");
    $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

    render_table_index($rows,"Database");
}
