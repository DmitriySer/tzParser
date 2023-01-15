<?php

namespace vendor\parser;

require 'autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

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
        $client = new Client([
            'base_uri' => 'UriInterface',
            'timeout' => 10,
        ]);
        $promise = $client->requestAsync('GET', $html);
        $promise->then(
            function (ResponseInterface $res) {
                 $res->getBody() . "\n";
            },
            function (RequestException $e) {
                echo $e->getMessage() . "\n";
                 $e->getRequest()->getMethod();
            }
        );
        $res = $promise->wait();
        $body = $res->getBody();
        $stringBody = (string) $body;

        $this->read = $stringBody;
    }

    public function replacement($selection,$assignment)
    {
        $read = str_replace( $this->selection, $this->assignment, $this->read);

        $this->read = $read;
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
    $read=$parser->read;
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
        $parser->replacement($_POST['select'],$_POST['assignment']);
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
