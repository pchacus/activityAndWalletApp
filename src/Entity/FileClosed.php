<?php


namespace App\Entity;



class FileClosed extends AbstractFileState
{
    /**
     * @var File $file
     */
    private $file;

    public function open()
    {

        $this->file->changeState(new FileOpened());

    }

    public function close()
    {
        echo "Plik zamknięty!";
    }

    public function read($fileName)
    {
        echo "Plik zamkniety nie mozna odczytać";
    }

    public function write($fileName, $data)
    {
       throw new \Exception( "Plik zamknięty nie mozna zapisac");
    }


}