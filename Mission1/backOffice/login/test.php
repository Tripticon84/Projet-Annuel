<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/api/dao/admin.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/api/utils/server.php";

makeApiRequest("api/admin/login.php", ["yann@bc.com", "r"]);
