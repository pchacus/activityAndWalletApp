<?php


namespace App\Entity;


class FileOpened extends AbstractFileState
{

    /**
     * @var File $file
     */
    private $file;


    public function open()
    {
        echo "Plik otwarty!";
    }

    public function close()
    {
       $this->file->changeState(new FileClosed());
    }

    public function read($fileName)
    {
        if (file_exists($fileName)) {
            $file = fopen($fileName, 'r');
            $content = "";

            while (!feof($file)) {
                $line = fgetc($file);
                $content = $content . $file;
            }
            return $content;
        }

        return new \Exception("File doesn't exist");
    }


    public function write($fileName,$data)
    {
        var_dump("plik");
        //if(is_writable($fileName)) {

            $file = fopen($fileName, "a+");
            fwrite($file, $data->getName());

       // }

    }


}