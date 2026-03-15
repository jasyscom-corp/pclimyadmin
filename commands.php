<?php

require_once "processors.php";
require_once "connections.php";

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
        echo "4 Import/Export\n";
        echo "5 Advanced Tools\n";
        echo "0 Exit\n";

        $c=intval(readline("Select: "));

        switch($c){

        case 1:database_menu();break;
        case 2:sql_console();break;
        case 3:connection_wizard();break;
        case 4:import_export_menu();break;
        case 5:advanced_tools_menu();break;
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
        echo "3 Create database\n";
        echo "4 Delete database\n";
        echo "5 Database info\n";
        
        if($GLOBALS["ACTIVE_DB"]){
            echo "6 Table manager\n";
        }

        echo "0 Back\n";

        $c=intval(readline("Select: "));

        switch($c){

        case 1:show_databases();pause();break;
        case 2:select_database();pause();break;
        case 3:create_database();pause();break;
        case 4:delete_database();pause();break;
        case 5:database_info();pause();break;
        case 6:table_menu();break;
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
        echo "7 Drop table\n";
        echo "8 Table info\n";
        echo "9 Search data\n";
        echo "10 Customize table\n";
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
        case 8:table_info();pause();break;
        case 9:search_table();pause();break;
        case 10:customize_table();pause();break;
        case 0:return;
        }
    }
}

function import_export_menu(){

    while(true){

        $GLOBALS["CURRENT_MENU"]="IMPORT/EXPORT";

        draw_header();

        echo "1 Backup database\n";
        echo "2 Restore backup\n";
        echo "3 Export table\n";
        echo "4 Import table\n";
        echo "5 Structure only\n";
        echo "6 Data only\n";
        echo "0 Back\n";

        $c=intval(readline("Select: "));

        switch($c){

        case 1:backup_database();pause();break;
        case 2:restore_backup();pause();break;
        case 3:export_table();pause();break;
        case 4:import_table();pause();break;
        case 5:export_structure();pause();break;
        case 6:export_data();pause();break;
        case 0:return;
        }
    }
}

function advanced_tools_menu(){

    while(true){

        $GLOBALS["CURRENT_MENU"]="ADVANCED TOOLS";

        draw_header();

        echo "1 Query history\n";
        echo "2 Performance monitor\n";
        echo "3 Index manager\n";
        echo "4 Trigger manager\n";
        echo "5 View manager\n";
        echo "6 Security audit\n";
        echo "0 Back\n";

        $c=intval(readline("Select: "));

        switch($c){

        case 1:query_history();pause();break;
        case 2:performance_monitor();pause();break;
        case 3:index_manager();pause();break;
        case 4:trigger_manager();pause();break;
        case 5:view_manager();pause();break;
        case 6:security_audit();pause();break;
        case 0:return;
        }
    }
}

function create_database(){

    $db=db();

    if(!$db) return;

    $name=readline("Database name (or 'back'): ");

    if($name=="back") return;

    try{

        $db->exec("CREATE DATABASE IF NOT EXISTS `$name`");

        set_info("Database created");

    }catch(Exception $e){

        set_info($e->getMessage());
    }
}

function delete_database(){

    show_databases();

    $name=readline("Database name to delete (or 'back'): ");

    if($name=="back") return;

    if(!$GLOBALS["ACTIVE_DB"] || $GLOBALS["ACTIVE_DB"]!=$name){

        $confirm=readline("Database $name will be deleted. Proceed? (y/n): ");

        if($confirm!="y") return;

        $db=db();

        try{

            $db->exec("DROP DATABASE IF EXISTS `$name`");

            set_info("Database deleted");

        }catch(Exception $e){

            set_info($e->getMessage());
        }

    }else{

        set_info("Cannot delete active database");
    }
}

function database_info(){

    $db=db();

    if(!$db) return;

    try{

        $stmt=$db->query("SELECT name AS Database,
                          (CASE
                              WHEN LOCATE('InnoDB',support)>0 THEN 'InnoDB'
                              ELSE support
                          END) AS Engine,
                          (COUNT(*)!=0) AS Support");

        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

        render_table($rows);

    }catch(Exception $e){

        set_info($e->getMessage());
    }
}

function table_info(){

    $table=select_table();

    if(!$table) return;

    $db=db_with_database();

    try{

        $stmt=$db->query("SHOW TABLE STATUS WHERE Name='$table'");

        $info=$stmt->fetch(PDO::FETCH_ASSOC);

        echo "Table Info:\n";
        echo "-------------\n";

        echo "Rows: ".$info["Rows"]."\n";
        echo "Data Length: ".round($info["Data_length"]/1024,2)." KB\n";
        echo "Index Length: ".round($info["Index_length"]/1024,2)." KB\n";
        echo "Data Free: ".round($info["Data_free"]/1024,2)." KB\n";
        echo "Engine: ".$info["Engine"]."\n";
        echo "Collation: ".$info["Collate"]."\n";

    }catch(Exception $e){

        set_info($e->getMessage());
    }
}

function search_table(){

    $table=select_table();

    if(!$table) return;

    $db=db_with_database();

    try{

        $field=readline("Field to search: ");
        $value=readline("Search term: ");

        $stmt=$db->query("SELECT * FROM `$table` WHERE `$field` LIKE '%$value%'");

        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

        render_table($rows);

    }catch(Exception $e){

        set_info($e->getMessage());
    }
}

function customize_table(){

    $table=select_table();

    if(!$table) return;

    while(true){

        $GLOBALS["CURRENT_MENU"]="CUSTOMIZE TABLE";

        draw_header();

        $db=db_with_database();

        try{

            $stmt=$db->query("SELECT * FROM `$table` LIMIT 1");

            $columns=array_keys($stmt->fetch(PDO::FETCH_ASSOC));

            render_table($columns);

        }catch(Exception $e){

            set_info($e->getMessage());
        }

        echo "\n1 Filter columns\n";
        echo "2 Sort data\n";
        echo "3 Pagination\n";
        echo "4 Format styles\n";
        echo "0 Back\n";

        $c=intval(readline("Select: "));

        switch($c){

        case 1:filter_columns($table);break;
        case 2:sort_data($table);break;
        case 3:set_pagination($table);break;
        case 4:set_format($table);break;
        case 0:return;
        }
    }
}

function backup_database(){

    $db=db();

    try{

        $stmt=$db->query("SHOW DATABASES");

        $databases=$stmt->fetchAll(PDO::FETCH_COLUMN);

        render_table_index($databases,"Database");

        $db_name=readline("Database to backup: ");

        if(!in_array($db_name,$databases)){

            set_info("Unknown database");

            return;
        }

        $tables_query=$db->query("SHOW TABLES FROM `$db_name`");

        $tables=$tables_query->fetchAll(PDO::FETCH_COLUMN);

        $backup_path="backup_".$db_name."_".date("Ymd_Hi.s").".sql";

        $file=fopen($backup_path,"w");

        foreach($tables as $table){

            fwrite($file,"DROP TABLE IF EXISTS `$table`;\n");

            $create_query=$db->query("SHOW CREATE TABLE `$db_name`.`$table`");

            $create=$create_query->fetch(PDO::FETCH_COLUMN,1);

            fwrite($file,$create.";\n\n");

            $data_query=$db->query("SELECT * FROM `$db_name`.`$table`");

            while($row=$data_query->fetch(PDO::FETCH_ASSOC)){

                $values=[];

                foreach($row as $field=>$val){

                    $values[]="'".str_replace("'", "\\'", $val)."'";
                }

                $line="INSERT INTO `$table`(`".implode("`,`",array_keys($row))."`) VALUES(".implode(",",$values).");\n";

                fwrite($file,$line);
            }
        }

        fclose($file);

        set_info("Backup successful: $backup_path");

    }catch(Exception $e){

        set_info($e->getMessage());
    }
}

function restore_backup(){

    $path=readline("Backup file path: ");

    if(!file_exists($path)){

        set_info("File not found");

        return;
    }

    $backup=file_get_contents($path);

    $db=db();

    try{

        $db->exec($backup);

        set_info("Restore successful");

    }catch(Exception $e){

        set_info($e->getMessage());
    }
}

function export_table(){

    $table=select_table();

    if(!$table) return;

    $db=db_with_database();

    try{

        $export_path="export_".$table."_".date("Ymd_Hi.s");

        $format=readline("Format (sql/csv/json/txt): ");

        switch($format){

        case "sql":
            export_sql($table,$db,$export_path);
            break;

        case "csv":
            export_csv($table,$db,$export_path);
            break;

        case "json":
            export_json($table,$db,$export_path);
            break;

        case "txt":
            export_txt($table,$db,$export_path);
            break;

        default:
            set_info("Unknown format");
            return;
        }

        set_info("Export successful: $export_path");

    }catch(Exception $e){

        set_info($e->getMessage());
    }
}

function import_table(){

    $table=select_table();

    if(!$table) return;

    $db=db_with_database();

    $import_path=readline("Import file path: ");

    if(!file_exists($import_path)){

        set_info("File not found");

        return;
    }

    $import_data=file_get_contents($import_path);

    try{

        $db->exec($import_data);

        set_info("Import successful");

    }catch(Exception $e){

        set_info($e);
    }
}

function filter_columns($table){

    $db=db_with_database();

    try{

        $stmt=$db->query("SELECT * FROM `$table` LIMIT 1");

        $columns=array_keys($stmt->fetch(PDO::FETCH_ASSOC));

        render_table($columns);

        $show_columns=readline("Columns to show (comma separated) or 'all': ");

        $cols=($show_columns=="all") ?
                "*":implode(",",explode(",",$show_columns));

        $stmt=$db->query("SELECT $cols FROM `$table`");

        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

        render_table($rows);

    }catch(Exception $e){

        set_info($e->getMessage());
    }
}

function sort_data($table){

    $db=db_with_database();

    try{

        $stmt=$db->query("SELECT * FROM `$table` LIMIT 1");

        $columns=array_keys($stmt->fetch(PDO::FETCH_ASSOC));

        render_table($columns);

        $sort_column=readline("Sort by: ");

        if(!in_array($sort_column,$columns)){

            set_info("Unknown column");

            return;
        }

        $dir=readline("Direction (asc/desc): ");
        $dir=($dir=="desc")?"DESC":"ASC";

        $stmt=$db->query("SELECT * FROM `$table` ORDER BY `$sort_column` $dir");

        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

        render_table($rows);

    }catch(Exception $e){

        set_info($e->getMessage());
    }
}

sql_console(){
    
    while(true){

    $GLOBALS["CURRENT_MENU"]="SQL CONSOLE";

    draw_header();

    $sql=readline("SQL> ");

    if($sql=="exit") break;

    try{

        $stmt=db_with_database()->query($sql);

        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

        render_table($rows);

    }catch(Exception $e){

        set_info($e->getMessage());
    }

    pause();
    }
}
