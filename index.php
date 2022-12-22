<?php

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
        $ch = curl_init($html);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        $response = curl_exec($ch);
        if (!$response){
            echo "Ошибка подключения к адресу " . $this->link;
            exit();
        }else {
            $file = fopen($this->file . '.html', 'w');
            curl_setopt($ch, CURLOPT_FILE, $file);
            curl_exec($ch);
        }
        curl_close($ch);
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


