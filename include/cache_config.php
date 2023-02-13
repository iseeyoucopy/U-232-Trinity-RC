<?php

declare(strict_types=1);

global $TRINITY20;
$TRINITY20['sitecache']['driver'] = 'memcached'; // Choices are memory, file, redis, memcached, apcu
$TRINITY20['sitecache']['prefix'] = 'u232_';

// Redis Settings
$TRINITY20['redis']['host'] = '127.0.0.1';
$TRINITY20['redis']['port'] = 6379;
$TRINITY20['redis']['database'] = 1;
$TRINITY20['redis']['socket'] = '';
$TRINITY20['redis']['use_socket'] = false;

// Memcached Settings
$TRINITY20['memcached']['host'] = '127.0.0.1';
$TRINITY20['memcached']['port'] = 11211;
$TRINITY20['memcached']['socket'] = '';
$TRINITY20['memcached']['use_socket'] = false;

// Mysqli Settings
$TRINITY20['mysql']['host'] = "";
$TRINITY20['mysql']['user'] = "";
$TRINITY20['mysql']['pass'] = "";
$TRINITY20['mysql']['db'] = "";

$TRINITY20['files']['path'] = ROOT_DIR . 'cache' . DIRECTORY_SEPARATOR;
