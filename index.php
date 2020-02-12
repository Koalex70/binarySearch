<?php

set_time_limit(0);

/**
 * @param $fileName string File name
 * @param $count int The number of key pairs - the value written to the file
 */
function createFile($fileName, $count)
{
    $file = fopen($fileName, "w");
    for ($i = 0; $i < $count; $i++) {
        fwrite($file, "key" . $i . "\t" . "value" . $i . "\x0A");
    }
}

/**
 * @param $fileName string File name
 * @param $key int Search key
 * @return mixed|string
 */
function binarySearch($fileName, $key)
{
    $file = new SplFileObject($fileName);
    $start = 0;

    $end = filesize($fileName) - 1;
    while ($start <= $end) {
        $position = floor(($start + $end) / 2);
        $file->fseek($position);

        $file->current();
        $str = explode("\t", $file->fgets());

        $strnatcmp = strnatcmp($str[0], $key);

        if ($strnatcmp > 0) {
            $end = $position - 1;
        } elseif ($strnatcmp < 0) {
            $start = $position + 1;
        } else {
            return $str[1];
        }
    }
    return 'undef'; // не найденно значение
}

//createFile('test3.txt', 240000000); //File size ~10.3 GB

$fileName = 'test3.txt';
$key = 'key1';

$time = time();
$result = binarySearch($fileName, $key);
$time = time() - $time;

echo 'Key - ' . $key . "; Value - " . $result . ";\n" . "Lead time - " . $time . " sec";
