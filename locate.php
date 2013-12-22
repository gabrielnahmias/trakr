<?php


function getLocation($user, $realm) {
    $path = rtrim(str_replace('\\', '/', __DIR__), '/');
    $command = sprintf('%s/bin/wpsapitest -u %s -r %s', $path, $user, $realm);
    if (strncasecmp('WIN', PHP_OS, 3) === 0)
    {
        $command = str_replace('/wpsapitest', '/wpsapitest.exe', $command);
    }
    if (preg_match_all('~(-?\d{1,2}[.]\d+, -?\d{1,3}[.]\d+)~', shell_exec($command), $coordinates, PREG_SET_ORDER) > 0)
    {
        $result = array_pop($coordinates); // 1st coordinate is gathered from IP, 2nd from Wi-Fi - lets get the best one
    }
    return (is_array($result) === true) ? current($result) : false;
}

$user = 'gabriel';
$realm = 'terrasoft';

echo getLocation($user, $realm);