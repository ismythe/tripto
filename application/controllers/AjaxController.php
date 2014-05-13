<?php
/**
 * Aqusest arxiu conté el controlador principal de les solicituds AJAX que usa l'aplicació
 *
 * @package    Tripto\Application\Controllers
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Controlador que s'encarrega de la majoria de solicituds AJAX
 */
class AjaxController extends ControllerBase
{
    
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
     * Funció heredada de ControllerBase
     *
     * Necessària perquè s'hereda, sinó provoca errors. Redirigeix a l'índex.
     * @return void
     */
      public function index(){
        header('Location: '.APP_W);
    }
    
    /**
     * Retorna les zones disponibles per cada tipus de servei mitjançant AjaxModel
     * @return string JSon amb les zones disponibles
     */
    public function data() {
        $this->model = new AjaxModel();
        $arr = [];
        if (isset($_POST['tipus'])) {
            $res = $this->model->getZona($_POST['tipus']);
            switch ($_POST['tipus']) {
                case 'vol':
                    $zona = 'destinacio';
                    break;

                case 'hotel':
                    $zona = 'ciutat';
                    break;

                case 'escapada':
                    $zona = 'zona';
                    break;
            }
            
            foreach ($res as $key => $value) {
                
                $array = array('value' => $value[$zona]);
                array_push($arr, $array);
            }
            $arr = array_unique($arr, SORT_REGULAR);
            
            echo json_encode($arr);
        }
    }
    
    /**
     * Retorna l'adreça d'un hotel mitjançant AjaxModel
     * @return string
     */
    public function getadreca() {
        $this->model = new AjaxModel();
        $id = $_POST['id'];
        $adreca = $this->model->getAdreca($id);
        $adreca = $adreca[0]['adreca'] . ',' . $adreca[0]['ciutat'];
        
        echo $adreca;
    }
    
    /**
     * Retorna un arxiu XML de l'adreça indicada.
     * @return string En format XML
     */
    public function rss() {
        $url = 'http://www.spain.info/es/rss/rss_reportajes.html';
        $xml = file_get_contents($url);
        print_r($xml);
    }
}
