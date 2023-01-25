<?php

namespace vendor\parser;

use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;

require 'autoload.php';


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
        $client = new Browser();
        $url = $this->link;
        $res = $client->get($url);
        $res->then(function (ResponseInterface $response) {
            $_SESSION['site'] = (string)$response->getBody();
        }, function (Exception $e) {
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
        });
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
    header('Location:/');
    $_SESSION['site']=$parser->read;
    $_SESSION['link']=$parser->file;
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
