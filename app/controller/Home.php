<?php
namespace App\Controller;

use App\Core\Database;
use App\Core\View;
use App\Core\Controller;
use Knp\Component\Pager\Paginator;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
/**
 * Class Home
 * @package App\Controller
 */
class Home extends Controller
{
    /**
     * Count tasks per page
     */
    const TASK_PER_PAGE = 3;

    /**
     * Default sorting direction
     */
    const TASK_SORT_DIRECTION = 'desc';

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
     * View homepage
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
        $offset = 0;
        if (isset($_GET['pg']) && is_numeric($_GET['pg'])){
            $offset = ($_GET['pg'] * self::TASK_PER_PAGE) - self::TASK_PER_PAGE;
        }

        $sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : "created";
        $direction = self::TASK_SORT_DIRECTION;
        if (isset($_GET['direction']) && !empty($_GET['direction'])){
            $direction = $_GET['direction'];
        }

        $currentPageNumber = (isset($_GET['pg']) && is_numeric($_GET['pg']))?$_GET['pg']:1;
        $this->connection->setTrace(true);
        $this->connection->groupBy("t.id");
        $this->connection->orderBy($sort, $direction);
        $tasks = $this->connection->withTotalCount()->get('task t',  [$offset, self::TASK_PER_PAGE]); // ,  [$offset, self::TASK_PER_PAGE]

        $query = $this->connection->trace[0][0];

        $paginator = new Paginator();
        $pagination = $paginator->paginate(
            $tasks, /* query NOT result */
            $currentPageNumber, /*page number*/
            self::TASK_PER_PAGE /*limit per page*/
        );
        $slidingPagination = new SlidingPagination([]);
        $slidingPagination->setTemplate('pagination/foundation_v5_pagination.twig');
        $slidingPagination->setCurrentPageNumber($currentPageNumber);
        $slidingPagination->setPaginatorOptions([
            "page_name" => 'pg', # page query parameter name
            "sort_field_name" => 'sort', # sort field query parameter name
            "sort_direction_name" => 'direction',  # sort direction query parameter name
            "distinct" => true, # ensure distinct results, useful when ORM queries are using GROUP BY statements
            "filter_field_name" => 'username',  # filter field query parameter name
            "filter_value_name" => 'filterValue'  # filter value query parameter name
        ]);
        $slidingPagination->setCustomParameters([]);
        $slidingPagination->setTotalItemCount($this->connection->totalCount);
        $slidingPagination->setItemNumberPerPage(self::TASK_PER_PAGE);

        $responseData = [
            'page_count' => ceil($this->connection->totalCount / self::TASK_PER_PAGE),
            'tasks' => $tasks,
            'currentPage' => $currentPageNumber,
            'pagination' => $slidingPagination
        ];

        if (array_key_exists('messages', $_SESSION) && sizeof($_SESSION['messages']) > 0){
            $responseData['messages'] = $_SESSION['messages'];
            unset($_SESSION['messages']);
        }

        View::renderTemplate('home/index.twig', $responseData);

    }
}