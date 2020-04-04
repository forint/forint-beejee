<?php
namespace App\Controller;

use App\Core\Controller;
use App\Core\Database;
use App\Core\View;

/**
 * Class Task
 * @package App\Controller
 */
class Task extends Controller
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
     * Task constructor.
     * @param $route_params
     */
    public function __construct($route_params)
    {
        parent::__construct($route_params);

        $this->db = new Database();
        $this->connection = $this->db::getInstance();
    }
    /**
     * Add action page
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function addAction()
    {
        View::renderTemplate('task/add.twig');
    }

    /**
     * Insert task
     *
     * @throws \Exception
     */
    public function saveAction()
    {
        $messages = [];

        $task_id = $_POST['task_id'] ?? null;
        $username = $_POST['username'] ?? null;
        $email = $_POST['email'] ?? null;
        $description = $_POST['description'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $username && $email && $description){

            $this->connection->where("username", $username);
            $this->connection->where("email", $email);
            $this->connection->where("description", $description);
            $_tasks = $this->connection->get('task');

            // Checking for existing task
            if (sizeof($_tasks) > 0) {
                $messages[] = 'Такая задача уже есть. Попробуйте добавить другую.';
            }else{
                $taskData = [
                    'username' => $username,
                    'email' => $email,
                    'slug' => $_POST['slug'] = \Transliterator::createFromRules(
                        ':: Any-Latin;'
                        . ':: NFD;'
                        . ':: [:Nonspacing Mark:] Remove;'
                        . ':: NFC;'
                        . ':: [:Punctuation:] Remove;'
                        . ':: Lower();'
                        . '[:Separator:] > \'-\''
                    )->transliterate( substr($description, 0, 14) ),
                    'description' => $description,
                    'status' => 0,
                ];

                $result = $this->connection->insert('task', $taskData);
                if ($result){
                    $messages[] = 'Вы успешно добавили новую задачу.';
                }else{
                    $messages[] = 'Произошла непредвиденная ошибка. Пожалуйста попробуйте ещё.';
                }
            }


        }else{
            $messages[] = 'Произошла непредвиденная ошибка. Пожалуйста попробуйте ещё.';
        }
        $_SESSION['messages'] = $messages;
        header('Location: /');
    }

}