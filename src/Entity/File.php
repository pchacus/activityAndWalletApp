<?php


namespace App\Entity;



class File
{
    /**
     * @var AbstractFileState $fileState
     */
    private  $fileState;

    public function __construct()
    {
        $this->fileState = new FileClosed();
    }

    public function changeState(AbstractFileState $newState){

        $this->fileState = $newState;

    }
    public function open()
    {
        $this->fileState->open();
    }

    public function close()
    {
        $this->fileState->close();
    }

    public function read($fileName)
    {
        $this->fileState->read($fileName);
    }

    public function write($fileName, $data)
    {
        try {
            $this->fileState->write($fileName, $data);
        } catch (\Exception $e) {

        }
    }

}