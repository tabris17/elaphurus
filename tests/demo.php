<?php
require __DIR__ . '/../src/Ela.php';

use Ela\Log\LoggerAwareInterface;
use Ela\Log\LoggerAwareTrait;

/*
echo \Ela\System::readAndWriteFile(__DIR__.'/test.txt', function ($handle) {
    sleep(10);
    fwrite($handle, __FILE__);
    fflush($handle);
    return __FILE__;
});

*/


