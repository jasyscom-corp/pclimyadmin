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
