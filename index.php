<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;


class TestParser
{
    public $link;
    public $file;
    public $selection;
    public $assignment;
    public $read;

    function __construct($link, $selection, $assignment)
    {
        set_time_limit(0);
        $this->link = $link;
        $this->selection = $selection;
        $this->assignment = $assignment;
    }

    public function clearLink()
    {
        preg_match('#\/\/(.*?)\/#', $this->link, $name);
        $this->file = $name[1];

        return $this->file;
    }

    public function parser()
    {
        $this->clearLink();
        $html = $this->link;
        $client = new Client([
            'base_uri' => 'UriInterface',
            'timeout' => 2.0,
        ]);
        $response =$client->get($html);
        $response =$response->getBody();
        if (!$response){
            echo "Ошибка подключения к адресу " . $this->link;
            exit();
        }else {
            fopen($this->file . '.html', 'w');
            file_put_contents( $this->file.'.html', $response);
        }
    }


    public function replacement()
    {
        $read = file_get_contents($this->file.'.html');
        $read = str_replace( $this->selection, $this->assignment, $read);
        file_put_contents($this->file.'.html', $read);

        echo $read;
    }

    public function start()
    {
        $time_start = microtime(true);
        $this->clearLink();
        try {
            if (!file_exists($this->file . '.html'))
            {
                throw new Exception();
            }
            $this->replacement();
        }catch (Exception $a)
        {
            $this->parser();
            $this->replacement();
        }
        $time_end = microtime(true);
        $time = $time_end - $time_start;
        echo 'Времени заняло ' . $time;
    }

}
$a = new TestParser($argv[1],$argv[2],$argv[3]);
print_r($a->start());


