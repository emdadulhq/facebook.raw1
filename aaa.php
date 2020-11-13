<?php


class Php{
    public $name = "Imdadul Haque";
    public $age = 40;
    public $job = "Full stack developer";

    public function info()
    {
        return "<h1> Hello, This is ". $this ->name. ". And I am " .$this->age." years old. My job is ". $this->job ."</h1>";
    }
}

class Python extends Php {
    public $slogan= " <h1> Amar sonar bangla ami tomay valobasi</h1>";

}

$python = new python;
echo $python -> slogan;
echo "<hr>";
echo $python -> name;
echo "<hr>";
echo $python -> job;
echo "<hr>";
echo $python -> info();
echo "<hr>";