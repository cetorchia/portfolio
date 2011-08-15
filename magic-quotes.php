<?php

// Deal with those stupid magic quotes.

function remove_request_magic_quotes(&$REQ)
{
	if(get_magic_quotes_gpc)
	{
		foreach($REQ as $k => $v)
		{
			$REQ[$k] = stripslashes($v);
		}
	}
}

// Take them off for each request parameter map

remove_request_magic_quotes($_POST);
remove_request_magic_quotes($_GET);
remove_request_magic_quotes($_COOKIE);
remove_request_magic_quotes($_REQUEST);
