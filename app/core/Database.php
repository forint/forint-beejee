<?php
namespace App\Core;

use App\Core\Settings;

/**
 * Class Database
 * @package App\Core
 */
class Database
{
    /**
     * @var \MysqliDb|null $instance
     */
    private static $instance = null;

    /**
     * @var $name
     */
    private $name;

    /**
     * @var $host
     */
    private $host;

    /**
     * @var $port
     */
    private $port;

    /**
     * @var $username
     */
    private $username;

    /**
     * @var $password
     */
    private $password;

    /**
     * Database constructor.
     */
    public function __construct(){

        $settings = new Settings();
        $config = $settings->getConfig();

        $this->host = $config['database_host'];
        $this->port = $config['database_port'];
        $this->name = $config['database_name'];
        $this->username = $config['database_user'];
        $this->password = $config['database_password'];

        self::$instance = new \MysqliDb ($this->getHost(), $this->getUsername(), $this->getPassword(), $this->getName());

    }

    /**
     * Retrieve Singleton database instance
     * @return Database|\MysqliDb|null
     */
    public static function getInstance()
    {
        if (self::$instance != null) {
            return self::$instance;
        }

        return new self;
    }

    /**
     * Get name
     * @return mixed
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Get host
     * @return mixed
     */
    public function getHost(){
        return $this->host;
    }

    /**
     * Get port
     * @return mixed
     */
    public function getPort(){
        return $this->port;
    }

    /**
     * Get username
     * @return mixed
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * Get password
     * @return mixed
     */
    public function getPassword(){
        return $this->password;
    }

}