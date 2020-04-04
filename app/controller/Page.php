<?php
namespace App\Controller;

use App\Core\View;
use App\Core\Controller;

/**
 * Class Page
 * @package App\Controller
 */
class Page extends Controller
{

    /**
     * 404 Not found page
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notFoundAction()
    {
        View::renderTemplate('page/notFound.twig');

    }
}