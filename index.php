<?php

class Debug
{
    public static function pr($arr)
    {
        echo "<pre style='color:darkred'>***** Hello debug: *****\n\n";
        print_r($arr);
        echo "\n***** end debug *****</pre>";
    }
}

class About
{
    public function __construct()
    {
        echo "This is the about page";
        $this->_other();
    }
    
    protected function _other()
    {
        echo ". This is the other function, lolz.";
    }
}

class News
{
    public function __construct()
    {
        echo "This is the News page.";
    }
}

class Contacts
{
    public function __construct()
    {
        echo "This is the Contacts page.";
    }
}

class Shop
{
    public function __construct()
    {
        echo "This is the Shop page.";
    }
}

function home(){
    echo "Is funciton the Home";
}


class Route
{
    private $_uri = [];
    private $_method = [];
    
    public function add($uri, $method = null)
    {
        // Build collection URI
        $this->_uri[] = '/' . trim($uri, '/');
        
        if ($method !== null) {
            $this->_method[] = $method;
        }
    }
    
    public function submit()
    {
        // Make the thing run
        $uriGetParam = isset($_GET['uri']) ? $_GET['uri'] : '/';
        
        echo "<b>This uri:</b> $uriGetParam<br>";
        
        foreach ($this->_uri as $key => $val) {
            echo "<b>In arr:</b> $val";
            
            $val = trim($val, '/');
            
            if (preg_match("#^$val$#", $uriGetParam)) {
                echo '<b><i>    <--    match!</i></b>';
                
                // Redirect page on method or function
                $useMethod = $this->_method[$key];
                
                if (class_exists($useMethod)) {
                    new $useMethod();
                } elseif (function_exists(mb_strtolower($useMethod))) {
                    call_user_func($this->_method[$key]);
                } else {
                    die('Create class or function page');
                }
                
                unset($useMethod);
            }
            
            echo '<br>';
        }
    }
}

$r = new Route();
$r->add('/', 'Home');
$r->add('about', 'About');
$r->add('news', 'News');
$r->add('contacts', 'Contacts');
$r->add('shop', 'Shop');
$r->add('delivery', 'Delivery');

Debug::pr($r);

$r->submit();