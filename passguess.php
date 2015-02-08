<?php

/**
 * I just wanted to make this. So there.
 *
 * Copyright (c) 2015 Carlos E. Torchia
 * Licensed under GNU GPL version 2 or 3.
 * No warranty.
 */

//ini_set('display_errors', 1); error_reporting(E_ALL);
set_time_limit(3600);

define('MAX_PASSWORD_LENGTH', 7);

function getNext($password) {
    for($i = strlen($password) - 1; $i >= -1; $i--) {
        if ($i == -1) {
            $password = chr(0) . $password;
            break;
        } else if (ord($password[$i]) == 255) {
            $password[$i] = chr(0);
            continue;
        } else {
            $password[$i] = chr(ord($password[$i]) + 1);
            break;
        }
    }
    return $password;
}

if (isset($_POST['password']) && !empty($_POST['password'])) {
    $password = '';
    $time = time();
    $hash = hash('sha256', $_POST['password']);
    while (
        (hash('sha256', $password) !== $hash) &&
        (strlen($password) <= MAX_PASSWORD_LENGTH))
    {
        $password = getNext($password);
        //echo bin2hex($password) . ', ';
    }
    if (strlen($password) <= MAX_PASSWORD_LENGTH) {
        $totalTime = time() - $time;
    } else {
        $errorMessage = 'Exceeded ' . MAX_PASSWORD_LENGTH . ' characters.';
    }
}
?>

<html>
<body>
<?php
    if (isset($errorMessage)) {
        echo '<div style="color: #ff0000; font-weight: bold;">';
        echo '<p>' . $errorMessage . '</p>';
        echo '</div>';
    } else if (isset($totalTime)) {
        echo '<div style="color: #ff0000; font-weight: bold;">';
        echo '<p>Password: ' . $password . '</p>';
        echo '<p>That took ' . $totalTime . ' seconds</p>';
        echo '</div>';
    }
?>
<div>
<p>
This page will take your password, encrypt it using SHA256, 
and determine what your password was using the brute force approach on the
encrypted hash. It will then say how long that took to figure out.
</p>
<p>
This should give you an idea of how useless it is to have a password that is
less than 8 characters, even if it is not some common phrase.
</p>
</div>
<form action="" method="POST">
<div>Password: <input type="text" name="password" value="<?= isset($_POST['password']) ? $_POST['password'] : '123' ?>"/></div>
<div><input type="submit" name="submit" value="submit"/></div>
</form>
</body>
</html>
