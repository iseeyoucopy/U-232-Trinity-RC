<?php

declare(strict_types = 1);

global $INSTALLER09;
$INSTALLER09['sitecache']['driver'] = 'redis'; // Choices are memory, file, redis, memcached, apcu
$INSTALLER09['sitecache']['prefix'] = 'u232_';

// Redis Settings
$INSTALLER09['redis']['host'] = '127.0.0.1';
$INSTALLER09['redis']['port'] = 6379;
$INSTALLER09['redis']['database'] = 1;
$INSTALLER09['redis']['socket'] = '';
$INSTALLER09['redis']['use_socket'] = false;

// Memcached Settings
$INSTALLER09['memcached']['host'] = '127.0.0.1';
$INSTALLER09['memcached']['port'] = 11211;
$INSTALLER09['memcached']['socket'] = '';
$INSTALLER09['memcached']['use_socket'] = false;

$INSTALLER09['files']['path'] = '';
