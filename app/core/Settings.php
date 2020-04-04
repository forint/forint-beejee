<?php
namespace App\Core;

use Symfony\Component\Yaml\Yaml;

/**
 * Class Settings
 * @package App\Settings
 */
class Settings
{
    /**
     * @var $config
     */
    private $config;

    /**
     * Settings constructor.
     */
    public function __construct()
    {
        $this->config = Yaml::parseFile($_SERVER['DOCUMENT_ROOT'].'config.yml');
    }

    /**
     * Get config array
     */
    public function getConfig()
    {
        return $this->config;
    }
}