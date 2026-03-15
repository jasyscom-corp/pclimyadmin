<?php

require_once "processors.php";

function pause(){
readline("Press ENTER...");
}

function exit_program(){

system("clear");

echo "\n====================================\n";
echo "     PCLIAMYADMIN BY JASYSCOM\n";
echo "====================================\n";
echo "Bye!\n\n";

sleep(1);

exit;

}

function main_menu(){

while(true){

$GLOBALS["CURRENT_MENU"]="MAIN MENU";

draw_header();

echo "1 Database Manager\n";
echo "2 SQL Console\n";
echo "3 Connection Settings\n";
echo "0 Exit\n";

$c=intval(readline("Select: "));

switch($c){

case 1:database_menu();break;
case 2:sql_console();break;
case 3:connection_wizard();break;
case 0:exit_program();

}

}

}

function database_menu(){

while(true){

$GLOBALS["CURRENT_MENU"]="DATABASE MANAGER";

draw_header();

echo "1 List databases\n";
echo "2 Select database\n";

if($GLOBALS["ACTIVE_DB"]){
echo "3 Table manager\n";
}

echo "0 Back\n";

$c=intval(readline("Select: "));

switch($c){

case 1:show_databases();pause();break;
case 2:select_database();pause();break;
case 3:table_menu();break;
case 0:return;

}

}

}

function table_menu(){

if(!$GLOBALS["ACTIVE_DB"]){

set_info("Select database first");
return;

}

while(true){

$GLOBALS["CURRENT_MENU"]="TABLE MANAGER";

draw_header();

echo "1 List tables\n";
echo "2 Create table\n";
echo "3 Insert row\n";
echo "4 Browse table\n";
echo "5 Describe table\n";
echo "6 Alter table\n";
echo "7 Destroy table\n";
echo "0 Back\n";

$c=intval(readline("Select: "));

switch($c){

case 1:list_tables();pause();break;
case 2:create_table();pause();break;
case 3:insert_row();pause();break;
case 4:browse_table();pause();break;
case 5:describe_table();pause();break;
case 6:alter_table();pause();break;
case 7:destroy_table();pause();break;
case 0:return;

}

}

}

function sql_console(){

while(true){

$GLOBALS["CURRENT_MENU"]="SQL CONSOLE";

draw_header();

$sql=readline("SQL> ");

if($sql=="exit") return;

try{

$stmt=db()->query($sql);

$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

render_table($rows);

}catch(Exception $e){

set_info($e->getMessage());

}

pause();

}

}

main_menu();
