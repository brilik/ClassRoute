<?php

define('PATH_ROOT', dirname(__FILE__));
define('PATH_VIEW', PATH_ROOT . '/view/');
define('THEME', 'default');
define('PATH_THEME_ACTIVE', PATH_VIEW . 'themes/' . THEME);
include_once PATH_ROOT . '/debug.php';

$post = [];

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
            die('Ошибка: шаблон должен содержать обязательные файлы <code>index.php</code> и <code>style.css</code>');
        }
        
        // Include header
        IncludeTemplate::file('header');
        
        // Include method\func page start
        if (class_exists($methodName)) {
            new $methodName;
        } elseif (function_exists(mb_strtolower($methodName))) {
            call_user_func($methodName);
        } else {
            die('Create class or function page');
        }
        
        // Include page or index
        if (IncludeTemplate::file('page', 'php', true)) {
            IncludeTemplate::file('page');
        } else {
            IncludeTemplate::file('index');
        }

        // Include footer
        IncludeTemplate::file('footer');
    }
}

class Posts
{
    public $posts = [];
    
    public function __construct()
    {
        array_push($this->posts, array(
            'ID' => 0,
            'post_slug' => '/',
            'post_type' => 'page',
            'post_title' => 'Страница привествия',
            'post_desc' => 'Здравствуй дорогой друг. Рад что ты разобрался как развернуть этот шаблон',
            'post_date' => date('Y-m-d H:i:s'),
            'post_view' => 0
        ));
    
        array_push($this->posts, array(
            'ID' => 1,
            'post_slug' => 'about',
            'post_type' => 'page',
            'post_title' => 'О нас',
            'post_desc' => 'Расскажу немного о себе. Начал продавать ещё с 7 лет, когда понял что люди покупают это.',
            'post_date' => date('Y-m-d H:i:s'),
            'post_view' => 0
        ));
        
        return $this->get();
    }
    
    public function get()
    {
        return $this->posts;
    }
}

class About
{
    public function __construct()
    {
        global $post;
        $post = new Posts();
        echo 'Сработал класс About';
        Debug::pr($post);
        $this->_other();
    }
    
    protected function _other()
    {
        echo ' and uses method _other()';
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
     * Build collection URI
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
     * Make the thing run.
     */
    public function submit()
    {
        $uriGetParam = isset($_GET['uri']) ? $_GET['uri'] : '/';
        
        foreach ($this->_uri as $key => $val) {
            $val = trim($val, '/');
            
            if (preg_match("#^$val$#", $uriGetParam)) {
                // Redirect page on method or function
                $useMethod = $this->_method[$key];
                // Include template files
                IncludeTemplate::method($useMethod);
            }
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
$r->add('admin', 'Admin');

Debug::pr($r);

$r->submit();