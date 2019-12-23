<?php

define('PATH_ROOT', dirname(__FILE__));
define('PATH_VIEW', PATH_ROOT . '/view/');
define('THEME', 'default');
define('PATH_THEME_ACTIVE', PATH_VIEW . 'themes/' . THEME);
include_once PATH_ROOT . '/debug.php'; // todo: remove

class IncludeTemplate
{
    public static function file($fileName, $ext = 'php', $nonShow = false)
    {
        $pathToFile = PATH_THEME_ACTIVE . "/{$fileName}.$ext";
        
        if ( ! is_dir(PATH_THEME_ACTIVE)) {
            die('Не существует такой папки: ' . PATH_THEME_ACTIVE);
        }
        
        if (file_exists($pathToFile)) {
            if ($nonShow) {
                return $nonShow;
            }
            
            include_once $pathToFile;
        }
        
        unset($pathToFile, $fileName);
    }
    
    public static function method($methodName)
    {
        // Exists require files for template
        if ( ! IncludeTemplate::file('index', 'php', true) ||
             ! IncludeTemplate::file('style', 'css', true)) {
            die('<script>alert("Ошибка: шаблон должен содержать обязательные файлы <code>index.php</code> и <code>style.css</code>");</script>');
        }
        
        // Include method\func page start
        if (class_exists($methodName)) {
            new $methodName;
        } elseif (function_exists(mb_strtolower($methodName))) {
            call_user_func($methodName);
        } else {
            die('Create class or function page');
        }
        
        // Include header
        IncludeTemplate::file('header');
        
        // Include template
        if ($methodName === 'NotPage' && ! IncludeTemplate::file('404', 'php', true)) {
            // Include page or index
            if (IncludeTemplate::file('page', 'php', true)) {
                // Include page.php
                IncludeTemplate::file('page');
            } else {
                // Include index.php
                IncludeTemplate::file('index');
            }
        } elseif (IncludeTemplate::file('404', 'php', true)) {
            echo ' and uses template 404 page';
        }
        
        // Include footer
        IncludeTemplate::file('footer');
    }
}

class NotPage
{
    public function __construct()
    {
        echo 'Use class NotPage';
    }
}

class About
{
    public function index()
    {
        echo ' и вызвал метод index';
    }
    
    public function article()
    {
        echo ' и вызвал метод article';
    }
    
    public function __construct()
    {
        echo 'Сработал класс About';
//        Debug::pr($post);
        $this->index();
    }
    
    protected function _other()
    {
        echo ' и вызвал метод _other()';
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

function home()
{
    echo "Is function the Home";
}


class Route
{
    private $_uri = [];
    private $_method = [];
    
    /**
     * Build collection URI.
     *
     * @param $uri
     * @param null $method
     */
    public function add($uri, $method = null)
    {
        $this->_uri[] = '/' . trim($uri, '/');
        
        if ($method !== null) {
            $this->_method[] = $method;
        }
    }
    
    /**
     * Route to class.
     *
     * @param string $method
     *
     * @example $method = param: /?uri=page
     * @example $method = folder: /page
     */
    public function start($method = 'param')
    {
        // Make the thing run (for request param?uri=).
        $url = isset($_GET['uri']) ? $_GET['uri'] : '/';
        
        // Make the thing run (for request folder/style).
        if ($method === 'folder') {
            $url = $this->get_request();
        }
        
        if ( ! in_array("/$url", $this->_uri)) {
            IncludeTemplate::method('NotPage');
        }
        
        foreach ($this->_uri as $key => $val) {
            $val = trim($val, '/');
            
            if (preg_match("#^$val$#", $url)) {
                // Redirect page on method or function
                $useMethod = $this->_method[$key];
                // Include template files
                IncludeTemplate::method($useMethod);
            }
        }
        
        unset($url, $method);
    }
    
    /**
     * Get url path.
     *
     * @return array|mixed|string
     */
    protected function get_request()
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        
        if ($_SERVER['HTTP_HOST'] === 'localhost') {
            $array_uri = explode('/', $uri);
            array_shift($array_uri);
            $uri = $array_uri[1];
            unset($array_uri);
        } else {
            $uri = explode('/', $uri);
        }
        
        return $uri;
    }
}

$r = new Route();
$r->add('/', 'Home');
$r->add('about', 'About');
$r->add('news', 'News');
$r->add('contacts', 'Contacts');
$r->add('shop', 'Shop');
$r->add('delivery', 'Delivery');
$r->add('admin', 'Admin');

Debug::pr($r);

$r->start('folder');