<?php
/**
 * Config-file for MySQL database connection.
 *
 */
return [
    'dsn'            => "mysql:host=blu-ray.student.bth.se;dbname=matg12;",
    'username'       => "matg12",
    'password'       => "T]FF5vI%",
    'driver_options' => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'   => "spot_",
    'verbose'        => false,
    'debug_connect'  => true,
];
