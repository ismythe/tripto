<?php
/**
 * Aqusest arxiu conté un controlador base que redirecciona al controlador principal
 * 
 * @package    Tripto\Application\Controllers
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Controlador base de la aplicació. Redirecciona al principal.
 *
 */
class IndexController extends ControllerBase {

    /**
     * Defineix el model utilitzat per la classe
     * @var object
     */
    private $model;
    
    /**
     * Defineix la vista que utilitzarà la classe
     * @var object
     */
    private $view;
    
    /**
     * Els paràmetres que rep per URL del Bootstrap
     * @var array
     */
    private $param;
    
    /**
     * Constructor de la classe
     * @param array $params
     */
    public function __construct($params) {
        parent::__construct($params);
        $this->param = $params;
        $this->view = new View();
        
    }
    /**
     * Redirecciona al controlador principal de la aplicació
     * @return void
     */
    public function index() {
        header('Location: agencia');
    }
}
