<?php

date_default_timezone_set("Asia/Jakarta");

define("BASEPATH", realpath(__DIR__."/.."));
define("STORAGE_PATH", BASEPATH."/storage");
define("TMP_PATH", STORAGE_PATH."/tmp");
define("SUDOERS", 
	[
		243692601 // @ammarfaizi2
	]
);

define("PID_FILE", STORAGE_PATH."/pid/ibld.pid");
