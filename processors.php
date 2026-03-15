<?php

require_once "connections.php";

$GLOBALS["ACTIVE_DB"] = null;
$GLOBALS["CURRENT_MENU"] = "INIT";

$config = load_config();

if (!$config) {

    $GLOBALS["LAST_INFO"] = "Server not configured";

} else {

    if (test_connection($config)) {

        $GLOBALS["LAST_INFO"] = "Server ready";

    } else {

        $GLOBALS["LAST_INFO"] = "Connection failed";
    }
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

function select_table(){

    $db=db_with_database();

    if(!$db) return null;

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

    if(!$db) return;

    $stmt=$db->query("SHOW TABLES");

    $rows=[];

    while($r=$stmt->fetch(PDO::FETCH_NUM)){
        $rows[]=["Table"=>$r[0]];
    }

    render_table_index($rows,"Table");
}

function create_table(){

    $db=db_with_database();

    if(!$db) return;

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

        if(strpos($c9["Extra"],"auto_increment")!==false) continue;

        $v=readline($c["Field"].": ");

        $data[$c["Field"]]=$v;
    }

    $fields=array_keys($data);

    $sql="INSERT INTO `$table` (`".implode("`,`",$fields)."`) VALUES ('".implode("','",$data)."')";

    $db->exec($sql);

    set_info("Row inserted");
}
