<?php
namespace App\Controller\Admin;

use App\Core\View;
use App\Core\Controller;
use App\Core\Database;

/**
 * Class Tasks
 * @package App\Controller\Admin
 */
class Tasks extends Controller
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
     * Tasks constructor.
     * @param $route_params
     */
    public function __construct($route_params)
    {
        parent::__construct($route_params);

        $this->db = new Database();
        $this->connection = $this->db::getInstance();
    }

    /**
     * List of tasks
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function indexAction()
    {
        if ($_SESSION && $_SESSION['is_admin'] == '1'){
            $tasks = $this->connection->get ('task');
            $messages = (array_key_exists('messages', $_GET)) ? $_GET['messages'] : null;

            $responseData = [
                'tasks' => $tasks,
                'messages' => $messages,
            ];

            if (array_key_exists('messages', $_SESSION) && sizeof($_SESSION['messages']) > 0){
                $responseData['messages'] = $_SESSION['messages'];
                unset($_SESSION['messages']);
            }

            View::renderTemplate('admin/tasks/list.twig', $responseData);
        }else{
            header('Location: /auth/login');
        }
    }

    /**
     * Add new task
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function addAction()
    {
        if ($_SESSION && $_SESSION['is_admin'] == '1'){
            View::renderTemplate('admin/tasks/add.twig');
        }else{
            header('Location: /auth/login');
        }
    }

    /**
     * Edit task
     *
     * @param int $id
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function editAction(int $id = null)
    {
        if ($_SESSION && $_SESSION['is_admin'] == '1'){

            $task = null;
            $connection = $this->db::getInstance();

            $taskId = $_POST['id'] ?? null;
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $description = $_POST['description'] ?? null;

            try {
                if ($taskId && is_numeric($taskId)) {

                    if ($username && $description) {

                        $this->connection->where("id", $taskId);
                        $task = $connection->update('task', $_POST);

                        if ($task){
                            header('Location: /admin/tasks/index?messages[]=Task updated successfully');
                        }else{
                            header('Location: /admin/tasks/add?messages[]=Can\'t insert task: ' . $connection->getLastError());
                        }
                    }
                }else{
                    if ($username && $description) {
                        $_POST['slug'] = \Transliterator::createFromRules(
                            ':: Any-Latin;'
                            . ':: NFD;'
                            . ':: [:Nonspacing Mark:] Remove;'
                            . ':: NFC;'
                            . ':: [:Punctuation:] Remove;'
                            . ':: Lower();'
                            . '[:Separator:] > \'-\''
                        )->transliterate( substr($description, 0, 14) );

                        $id = $connection->insert('task', $_POST);
                        if ($id){
                            $this->connection->where("id", $id);
                            $result = $connection->update('task', $_POST);

                            header('Location: /admin/tasks/index?messages[]=Task inserted successfully');
                        }else{
                            header('Location: /admin/tasks/add?messages[]=Can\'t insert task: ' . $connection->getLastError());
                        }
                    }else{
                        if (!$id || !is_numeric($id)){
                            header('Location: /admin/tasks/index?messages[]=Task isn\'t inserted. Title or description is empty!');
                        }
                    }
                }
            }catch(\Exception $e){
                // TODO:: Need require some logger interface, and write errors into a file
            }

            if ($id && is_numeric($id)){

                $this->connection->where ("id", $id);
                $task = $this->connection->getOne('task');
            }

            View::renderTemplate('/admin/tasks/edit.twig',[
                'task' => $task
            ]);

        }else{
            header('Location: /auth/login');
        }
    }

    /**
     * Delete task
     *
     * @param int|null $id
     * @throws \Exception
     */
    public function deleteAction(int $id = null)
    {
        if ($_SESSION && $_SESSION['is_admin'] == '1'){
            if ($id && is_numeric($id)){
                $this->connection->where ("id", $id);
                $task = $this->connection->getOne('task');


                $this->connection->where("id", $id);
                $result = $this->connection->delete('task');

                $_SESSION['messages'][] = 'Task deleted successfully';
                if ($result) {
                    header('Location: /admin/tasks/index');
                }

            }
        }else{
            header('Location: /auth/login');
        }
    }

}