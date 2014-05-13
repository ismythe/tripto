<?php
/**
 * Aqusest arxiu conté el controlador dels serveis de tipus "Hotel"
 * 
 * @package    Tripto\Application\Controllers
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Controlador pels serveis de tipus "Hotel".
 *
 * Gestiona totes les accions disponibles, així com les solicituds AJAX necessàries
 * pels serveis tipus "Hotel"
 *
 */
class HotelController extends ControllerBase
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
     * Pàgina principal. Redirigeix a la funció filtrar
     * @return void
     */
    public function index() {
        
        $this->filtrar();
    }
    
    /**
     * Comprova que estiguin introduïts tots els paràmetres POST necessaris i crida a vistaFiltrar()
     * @return void
     */
    public function filtrar() {
        $this->model = new HotelModel($this->param);
        
        if (isset($_SESSION['params'][0])) {
            $ciutat = $_SESSION['params'][0];
        } else {
            $ciutat = false;
        }
        
        if (isset($_SESSION['params'][1])) {
            $data_inici = $_SESSION['params'][1];
        } else {
            $data_inici = false;
        }
        if (isset($_SESSION['params'][2])) {
            $data_fi = $_SESSION['params'][2];
        } else {
            $data_fi = false;
        }
        
        if ($ciutat && $data_inici && $data_fi) {
            
            //Es podria buscar la disponibilitat als hotels
            $this->vistaFiltrar($this->model->fullSearch($ciutat, $data_inici, $data_fi));
        } else if ($ciutat && $data_inici) {
            $this->model->startDateSearch($ciutat, $data_inici);
        } else if ($ciutat) {
            $this->model->citySearch($ciutat);
        } else {
            
            $this->model->searchAll();
        }
    }
    
    /**
     * Comprova si hi ha serveis disponibles a la zona i dates indicades, si és així els mostra
     *
     * @param  array $array
     * @return void
     */
    public function vistaFiltrar($array) {
        $html = null;
        
        foreach ($array as $arr) {
            $vals = array_values($arr);
            $nom = str_replace(" ", "-", $vals[1]);
            if ($vals[3] == 1) {
                $vals[3] = "1 estrella";
            } else {
                $vals[3] = $vals[3] . " estrelles";
            }
            $divs = "<div id=\"" . $vals[0] . "\"><a href=\"" . APP_W . "/hotel/zona/" . $vals[2] . "/" . $nom . "\"><h3>" . $vals[1] . "</h3><p><ul><li>Ciutat: " . $vals[2] . "</li><li>Categoria: " . $vals[3] . "</li></ul></p></a></div>";
            $html = $html . $divs;
        }
        if ($html == null) {
            $content = array('{result}' => "No hi ha hotels que coincideixin amb la cerca.");
        } else {
            $content = array('{result}' => $html);
        }
        
        $this->view = new View();
        $valores_a_cambiar = array('{css}' => ESTILS, '{js}' => JS, '{APP_W}' => APP_W);
        
        $this->view = new View();
        if (!$_SESSION['logged']) {
            $this->view->appendTemplate('header');
        } else {
            $this->view->appendTemplate('header_r');
        }
        $this->view->appendTemplate('resultats');
        $this->view->addProp($valores_a_cambiar);
        $this->view->appendProp($content);
        $this->view->setProp();
        $this->view->show();
    }
    
    /**
     * Mostra un resultat del servei.
     * @return void
     */
    public function zona() {
        $this->model = new HotelModel($this->param);
        
        $valores_a_cambiar = array('{css}' => ESTILS, '{js}' => JS, '{APP_W}' => APP_W);
        $html;
        
        if (count($this->param) == 1) {
            
            
            $ciutat = implode(array_keys($this->param));

            $nom = implode(array_values($this->param));
            $nom = urldecode(str_replace("-", " ", $nom));
            $ciutat = urldecode(str_replace("-", " ", $ciutat));
            
            
            if ($item = $this->model->showItem($ciutat, $nom)) {
                
                $id = $item[0]['id'];
                $nom = $item[0]['nom'];
                $adreca = $item[0]['adreca'];
                $ciutat = $item[0]['ciutat'];
                $categoria = $item[0]['categoria'];
                $preu = $this->model->getPreu($id);
                $preu = $preu[0][0];
                
                if ($categoria == 1) {
                    $categoria = "1 estrella";
                } else {
                    $categoria = $categoria . " estrelles";
                }
                $html = '<div id="' . $id . '"><h2>' . $nom . '</h2><h3>' . $categoria . '</h3><h4>' . $adreca . ', ' . $ciutat . '</h4><p>Preu unit&agrave;ri: ' . $preu . '&euro;</p><form action="' . APP_W . '/cistella/afegir/" method="post"><input style="display:none;" name="servei" value="' . $id . '"/><input style="display:none;" name="tipus" value="hotels"/><label for="places">Nombre de places: </label><input type="number" name="places" value="1" min="1"/><button id="afegir">Afegir a la cistella</button></form></div>' . '<div id="mapa"></div>';
            }
        } else {
            $html = "404";
        }
        $this->view = new View();
        if (!$_SESSION['logged']) {
            $this->view->appendTemplate('header');
        } else {
            $this->view->appendTemplate('header_r');
        }
        $this->view->appendTemplate('item');
        
        $this->view->addProp($valores_a_cambiar);
        $content = array('{item}' => $html);
        $this->view->appendProp($content);
        $this->view->setProp();
        $this->view->show();
    }
}
