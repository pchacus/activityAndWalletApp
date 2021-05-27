<?php


namespace App\Entity;


abstract class AbstractFileState
{

   abstract public function open();
   abstract public function close();

    /**
     * @param $fileName
     * @return mixed
     */
    abstract public function read($fileName);

    /**
     * @param $fileName
     * @param $data
     * @return mixed
     */
   abstract public function write($fileName,$data);
}