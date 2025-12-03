<?php

//database configuration
//define('HOST', 'sql309.byethost24.com');
//define('USERNAME', 'b24_37182285');
//define ('PASSWORD', 'royaleagle2019.');
//define('DATABASE', 'b24_37182285_chromeseabed');
define ('DATABASE', 'aekstbcz_chromeseabed');
define('PASSWORD', 'Royaleagle2019.');
define ('USERNAME', 'aekstbcz_ayotunde');
define ('HOST', 'localhost');

//other site conf
define ('SITE_NAME', 'Chrome Seabed Connect');
define ('URL_ROOT', '/');
define ('BASE_MEDIA_ROOT', dirname(dirname(__DIR__)));
define ('SYSTEM_ROOT', dirname(__DIR__));

define ('WEBSITE_EMAIL', 'admin@chromeseabedconnect.com');
define ('ALTERNATE_WEBSITE_EMAIL', 'ayotundeokunubi73@gmail.com');

define("GCLOUD_BUCKET_NAME", 'chromeseabed-file-storage');
define("GCLOUD_URL_ROOT", 'https://storage.googleapis.com/'.GCLOUD_BUCKET_NAME.'/'.'public/');

define ("DATABASE_TYPE", "SQLITE"); //MYSQL - for mysqli database
define ("ENVIRONMENT", "PRODUCTION"); //PRODUCTION - for launching

?>