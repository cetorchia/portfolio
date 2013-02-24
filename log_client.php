<?php

$h = fopen('./clients.log', 'a');
fwrite($h, sprintf("Date: %s\n", date('r')));
fwrite($h, sprintf("Address: %s/%s\n", $_SERVER['REMOTE_HOST'], $_SERVER['REMOTE_ADDR']));
fwrite($h, sprintf("Referer: %s\n", $_SERVER['HTTP_REFERER']));
fwrite($h, sprintf("User-agent: %s\n", $_SERVER['HTTP_USER_AGENT']));
fwrite($h, "\n");
fclose($h);
