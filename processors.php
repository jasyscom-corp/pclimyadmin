<?php

require_once "connections.php";

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
printf(" %-".$width[$h]."s |",$h);
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

function select_database(){

$stmt=db()->query("SHOW DATABASES");
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

render_table_index($rows,"Database");

echo "[0] Back\n";

$c=intval(readline("Select number: "));

if($c==0) return;

if($c<1 || $c>count($rows)){
set_info("Invalid selection");
return;
}

$GLOBALS["ACTIVE_DB"]=$rows[$c-1]["Database"];

set_info("Using database ".$GLOBALS["ACTIVE_DB"]);

}

/* =========================
TABLE
========================= */

function select_table(){

$db=db_with_database();

$stmt=$db->query("SHOW TABLES");

$rows=[];

while($r=$stmt->fetch(PDO::FETCH_NUM)){
$rows[]=["Table"=>$r[0]];
}

render_table_index($rows,"Table");

echo "[0] Back\n";

$c=intval(readline("Select number: "));

if($c==0) return null;

if($c<1 || $c>count($rows)){
set_info("Invalid selection");
return null;
}

return $rows[$c-1]["Table"];

}

function list_tables(){

$db=db_with_database();

$stmt=$db->query("SHOW TABLES");

$rows=[];

while($r=$stmt->fetch(PDO::FETCH_NUM)){
$rows[]=["Table"=>$r[0]];
}

render_table_index($rows,"Table");

}

function create_table(){

$db=db_with_database();

$table=readline("Table name (or 'back'): ");

if($table=="back") return;

$cols=[];

while(true){

$name=readline("Column name (or 'done'): ");

if($name=="done") break;

$type=readline("Column type (example VARCHAR(100)): ");

$cols[]="`$name` $type";

}

$sql="CREATE TABLE `$table`(".implode(",",$cols).")";

$db->exec($sql);

set_info("Table created");

}

function alter_table(){

$table=select_table();

if(!$table) return;

$db=db_with_database();

while(true){

echo "\nALTER TABLE $table\n";

echo "1 Add column\n";
echo "2 Drop column\n";
echo "0 Back\n";

$c=intval(readline("Select: "));

switch($c){

case 1:

$name=readline("Column name: ");
$type=readline("Column type: ");

$db->exec("ALTER TABLE `$table` ADD `$name` $type");

set_info("Column added");

break;

case 2:

$name=readline("Column name: ");

$db->exec("ALTER TABLE `$table` DROP `$name`");

set_info("Column removed");

break;

case 0:return;

}

}

}

function describe_table(){

$table=select_table();

if(!$table) return;

$db=db_with_database();

$stmt=$db->query("DESCRIBE `$table`");

$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

render_table($rows);

}

function browse_table(){

$table=select_table();

if(!$table) return;

$db=db_with_database();

$stmt=$db->query("SELECT * FROM `$table` LIMIT 20");

$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

render_table($rows);

}

function destroy_table(){

$table=select_table();

if(!$table) return;

$confirm=readline("Drop table $table ? (y/n): ");

if($confirm!="y") return;

$db=db_with_database();

$db->exec("DROP TABLE `$table`");

set_info("Table dropped");

}

function insert_row(){

$table=select_table();

if(!$table) return;

$db=db_with_database();

$stmt=$db->query("DESCRIBE `$table`");

$cols=$stmt->fetchAll(PDO::FETCH_ASSOC);

$data=[];

foreach($cols as $c){

if(strpos($c["Extra"],"auto_increment")!==false) continue;

$v=readline($c["Field"].": ");

$data[$c["Field"]]=$v;

}

$fields=array_keys($data);

$sql="INSERT INTO `$table` (`".implode("`,`",$fields)."`) VALUES ('".implode("','",$data)."')";

$db->exec($sql);

set_info("Row inserted");

}
