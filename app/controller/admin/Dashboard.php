<?php

namespace App\Controller\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\View;

/**
 * Dashboard controller
 */
class Dashboard extends Controller
{

    /**
     * @var Database $db
     */
    private $db;

    /**
     * @var Database|\MysqliDb|null $connection
     */
    private $connection;

    /**
     * Films constructor.
     * @param $route_params
     */
    public function __construct($route_params)
    {
        parent::__construct($route_params);

        $this->db = new Database();
        $this->connection = $this->db::getInstance();
    }

    /**
     * Cancel add-edit mode
     */
    public function cancelAction()
    {
        header('Location: /admin/films/index');
    }

    /**
     * Tasks counters
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function indexAction()
    {
        if ($_SESSION && $_SESSION['is_admin'] == '1') {

            $taskCounters = $this->connection->get('task t', null, " SUM(CASE WHEN t.status=0 THEN 1 ELSE 0 END) as 'open',  SUM(CASE WHEN t.status=1 THEN 1 ELSE 0 END) as 'progress', SUM(CASE WHEN t.status=2 THEN 1 ELSE 0 END) as 'resolve'");

            View::renderTemplate('admin/dashboard/index.twig', [
                'taskCounters' => $taskCounters[0]
            ]);

        }else{
            header('Location: /auth/login');
        }
    }

}