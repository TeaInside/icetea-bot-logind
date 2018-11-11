<?php

require __DIR__."/bootstrap/init.php";

if (file_exists(PID_FILE)) {
	$pid = (int)trim(file_get_contents(PID_FILE));
	shell_exec("kill -9 {$pid}");
}

shell_exec("nohup /usr/bin/env php ".__DIR__."/bin/ibld >> ".__DIR__."/storage/logs/ibld.log 2>&1 &");
