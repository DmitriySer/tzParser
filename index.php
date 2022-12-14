<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

class TestParser
{
    public $link;
    public $file;
    public $selection;
    public $assignment;

    function __construct($link, $selection, $assignment)
    {
        $this->link = $link;
        $this->selection = $selection;
        $this->assignment = $assignment;
    }

    public function parser()
    {
        preg_match('#\/\/(.*?)\/#',$this->link,$name);
        $this->file = $name[1];
        if(!file_exists($this->file.'.html')){
            fopen($this->file.'.html','w');
            $timeStart = microtime(true);
            $html = $this->link;
            $client = new Client([
                'base_uri' => 'UriInterface',
                'timeout' => 2.0,
            ]);
            $response =$client->get($html);
            $response =$response->getBody();

            file_put_contents( $this->file.'.html', $response);
            $timeEnd = microtime(true);
            $time = $timeEnd - $timeStart;
            if ($time > 10){
                echo "Дительность более 10 секунд";
                exit();
            }else {
                echo "Длительность загрузки " . round($time, 2) . " сек ";
            }
        }
        return $this->file;
    }

    public function replacement()
    {
        $this->parser();
        $read = file_get_contents($this->file.'.html');
        $read = str_replace( $this->selection, $this->assignment, $read);
        file_put_contents($this->file.'.html', $read);
        echo $read;
    }

}
$a = new TestParser($argv[1],$argv[2],$argv[3]);
print_r($a->replacement());