<?php

$autoloadFile = __DIR__.'/../vendor/autoload.php';
if (!file_exists($autoloadFile)) {
    throw new RuntimeException('Install dependencies to run phpunit.');
}
require_once $autoloadFile;

defined('JPATH_COMPONENT') or define('JPATH_COMPONENT', dirname(__DIR__).'/../src/Component/');
defined('JPATH_PLUGIN') or define('JPATH_PLUGIN', dirname(__DIR__).'/../src/Plugin/');
define('JOOSCO_STUBS', dirname(__DIR__).'/unit/stubs/');
const STATUS_SUCCESS = 1;
const STATUS_FAILURE = 0;
ini_set('session.save_path', dirname(__DIR__).'/tests/session');
