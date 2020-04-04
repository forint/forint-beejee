<?php
namespace App\Core;

use Knp\Bundle\PaginatorBundle\Helper\Processor;
use Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationExtension;
use Symfony\Component\Translation\Translator;

use Symfony\Component\Routing\Generator\UrlGenerator;
/**
 * Twig view
 */
class View
{
    /**
     * @var \Twig_Environment $engine
     */
    public static $engine;

    /**
     * Render a view file
     *
     * @param string $view  The view file
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        //$file = "../view/$view";  // relative to Core directory
        $file = $_SERVER['DOCUMENT_ROOT']."app/view/$view";  // absolute paths

        if (is_readable($file)) {
            require $file;
        } else {
            echo "$file not found";
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template The template file
     * @param array $args Associative array of data to display in the view (optional)
     *
     * @param $template
     * @param array $args
     * @throws \ReflectionException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public static function renderTemplate($template, $args = [])
    {
        if (self::$engine === null) {

            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/view');

            self::$engine = new \Twig\Environment($loader, array('debug' => true));

            /** Knp/PaginationExtension require some inner functionality */
            $refClassUrlGenerator = new \ReflectionClass(UrlGenerator::class);
            $refClassTranslator = new \ReflectionClass(Translator::class);

            $proccesor = new Processor($refClassUrlGenerator->newInstanceWithoutConstructor(),$refClassTranslator->newInstanceWithoutConstructor());
            $paginationExtension = new PaginationExtension($proccesor);
            self::$engine->addExtension($paginationExtension);

            /** path function for union sorting, pages, sortField and other get params */
            self::$engine->addFunction(new \Twig\TwigFunction('path', function($uri, $query){
                $queryParams = [];
                foreach ($query as $paramName=>$paramValue){
                    $queryParams[] = $paramName.'='.$paramValue;
                }
                return $uri.'?'.implode('&', $queryParams);
            }));

            self::$engine->addExtension(new \Twig\Extension\DebugExtension());
            self::$engine->addExtension(new \Twig_Extensions_Extension_Text());

            /** @var Show date in custom format $filter */
            $filter = new \Twig\TwigFilter('date_russian_month', function ($date) {
                $months = [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
                $key = $date->format('n');
                return $date->format('d ' . $months[$key] . ' Y');
            });
            self::$engine->addFilter($filter);


            self::$engine->addGlobal('_server', $_SERVER);
            self::$engine->addGlobal('_session', $_SESSION);
            self::$engine->addGlobal('_post', $_POST);
            self::$engine->addGlobal('_get', $_GET);
        }

        echo self::$engine->render($template, $args);
    }
}