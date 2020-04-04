<?php
namespace App\Controller;

use App\Core\Controller;
use App\Core\View;
use App\Core\Settings;

/**
 * Security controller
 */
class Auth extends Controller
{
    /**
     * Sign In
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function loginAction()
    {
        $messages = [];
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $username && $password){

            $settings = new Settings();
            $config = $settings->getConfig();

            if ($config['admin_username'] == $username && $config['admin_password'] == $password){
                $_SESSION['is_admin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;

                header('Location: /admin/tasks/index');

            }else{
                $messages[] = 'Login failed: Invalid username or password';
            }
        }

        View::renderTemplate('auth/index.twig',[
            'messages' => $messages
        ]);
    }

    /**
     * Sign out
     */
    public function logoutAction()
    {
        session_unset($_SESSION['is_admin']);
        session_unset($_SESSION['username']);
        session_unset($_SESSION['password']);
        session_destroy();
        header('Location: /');
    }
}