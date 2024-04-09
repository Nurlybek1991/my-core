<?php

namespace Service;

class LoggerService
{
    public function error($exception)
    {
        $file = './../Storage/error.txt';
        $data = date('d.m.Y h:i:s');
        $message = $exception->getMessage() . '. Внимание на строку ' . $exception->getLine() . ' в файле ' . $exception->getFile();

        echo $file . $data. $message;
//        return file_put_contents($file, $data . "\n" . $message . ";\n", FILE_APPEND);
    }

}