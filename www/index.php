<?php

use fl\tree\CategoryTree;

$basePath = dirname(__DIR__);

require_once "$basePath/vendor/autoload.php";

$fileName = "$basePath/data.csv";
$tree     = new CategoryTree($fileName);

header('Content-Type: application/json; charset=utf-8');

echo json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
