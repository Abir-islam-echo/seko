<?php
require_once __DIR__ . '/vendor/autoload.php';

final class App
{
    const VERSION = '1.0.0';
    private static $instance = null;
    public $db;

    public function __construct()
    {
        session_start();
        define('APP_DIR', __DIR__);
        $env = new \Toll_Integration\Env(APP_DIR . '/.env');
        $env->load();
        define('APP_URL', getenv('APP_URL'));
        define('APP_FOLDER', '/ChintiAndParker_UAT/');

        //SetupDB

        if (!isset($_SESSION['root'])) {
            $_SESSION['root'] = false;
        }

        $this->db = new \Toll_Integration\DB();
        $this->db->init();

        //Log
        $log = new \Toll_Integration\Log();
    }

    public static function init()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

function init_toll_integration_app()
{
    return App::init();
}

$init_toll_integration_app = init_toll_integration_app();
