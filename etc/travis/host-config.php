<?php
$ds = DIRECTORY_SEPARATOR;
/* General */
$conf['host'] = 'travis-ci';
$conf['root'] = __DIR__.$ds.'..'.$ds.'..'.$ds.'lib'.$ds;
$conf['production'] = TRUE;
$conf['uagent-key'] = NULL;
$conf['cms']['user'] = 'travis@ps-webforge.com';
$conf['cms']['password'] = 'dUHnXlXN5ZtWAQ6FCDeA';

/* System Datenbank (für dumps per Console für die Tests) */
$conf['system']['dbm']['user'] = 'root';
$conf['system']['dbm']['password'] = '';
$conf['system']['dbm']['host'] = 'localhost';

/* Host Pattern für automatische baseURLs */
$conf['url']['hostPattern'] = '%s.travis.ps-webforge.net';

/* Project Paths */
$conf['projects']['root'] = realpath(__DIR__.'/../../').$ds;
$conf['projects']['psc-cms']['root'] = realpath(__DIR__.'/..').$ds;

/* Environment */
$conf['defaults']['system']['timezone'] = 'Europe/Berlin';
$conf['defaults']['system']['chmod'] = 0644;
$conf['defaults']['i18n']['language'] = 'de';
$conf['defaults']['languages'] = array('de');

/* Executables */

/* Mail */
$conf['defaults']['mail']['smtp']['user'] = NULL;
$conf['defaults']['mail']['smtp']['password'] = NULL;
$conf['defaults']['debug']['errorRecipient']['mail'] = NULL;
$conf['mailchimp']['test']['apiKey'] = 'schwachsinn-cs5';
?>