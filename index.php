<?php
require 'lib/Filemanager.php';

use lib\Filemanager;

$fileManager = new Filemanager();

echo $fileManager->isFileExist('filename.txt');




$dirName = __Dir__;
$fileName = __Dir__ . '/tmp.txt';

$fileManager->createDir([$dirName . '/test1', $dirName . '/test2']);

