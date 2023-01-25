<?php

namespace vendor\parser;

use Exception;

session_start();
class TestParser
{
    public $link;
    public $file;
    public $read;
    public $selection;
    public $assignment;

    function __construct($link, $selection, $assignment)
    {
        $this->link = $link;
        $this->selection = $selection;
        $this->assignment = $assignment;
    }

    private function clearLink()
    {
        preg_match('#\/\/(.*?)\/#', $this->link, $name);
        $this->file = $name[1];

    }

    public function parser()
    {
        $this->clearLink();
        $html = $this->link;
        $ch = curl_init($html);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $response = curl_exec($ch);
            if (!$response) {
                throw new Exception('Ошибка подключения к адресу ' . $this->link . '<br>' . ' Ошибка curl: ' . curl_error($ch));
            }
            curl_close($ch);
            $this->read = $response;
    }

    public function replacement()
    {
        if (is_array($this->selection))
        {
            $arr = $this->selection;
            foreach ($arr as $k=>$v)
            {
                $read = str_replace($k, $v, $this->read);

                $this->read = $read;
            }

        } else {

            $read = str_replace($this->selection, $this->assignment, $this->read);

            $this->read = $read;
        }
    }

    public function save()
    {
        $file = $this->file . '.html';
        mkdir('../sites');
        fopen($file,'w');
        file_get_contents($file);
        file_put_contents($file,$this->read);
        rename($file,'../sites/'.$file);
    }
}

$parser = new TestParser($_POST['link'],$_POST['select'],$_POST['assignment']);

if(isset($_POST['linkBut']))
{
    $parser->parser();
    $read=$parser->read;
    header('Location:/');
    $_SESSION['site']=$read;
    $link=$parser->file;
    $_SESSION['link']=$link;
}
if(isset($_POST['selection']))
{
    if(isset($_SESSION['site']))
    {
        $parser->file=$_SESSION['link'];
        $parser->read=$_SESSION['site'];
        $parser->replacement();
        $read=$parser->read;
        header('Location:/');
        $_SESSION['site']=$read;
    }
}

if (isset($_POST['save']))
{
    if (isset($_SESSION['site']))
    {
        $parser->file=$_SESSION['link'];
        $parser->read=$_SESSION['site'];
        $parser->save();
    }
    header('Location:/');
}

if(isset($_POST['reset']))
{
    unset($_SESSION['site']);
    header('Location:/');
}
