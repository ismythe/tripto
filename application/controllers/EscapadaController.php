<?php
/**
 * Aqusest arxiu conté el controlador dels serveis de tipus "Escapada"
 * 
 * @package    Tripto\Application\Controllers
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Controlador pels serveis de tipus "Escapada".
 *
 * Gestiona totes les accions disponibles, així com les solicituds AJAX necessàries
 * pels serveis tipus "Escapada"
 *
 */
class EscapadaController extends ControllerBase
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
        
        $this->model = new EscapadaModel($this->param);
        
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
            
            $this->vistaFiltrar($this->model->fullSearch($ciutat, $data_inici, $data_fi));
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
            $divs = "<div id=\"" . $vals[0] . "\"><a href=\"" . APP_W . "/escapada/zona/" . $vals[2] . "/" . $nom . "\"><h3>" . $vals[1] . "</h3><p><ul><li>Zona: " . $vals[2] . "</li><li>Data Inici: " . $vals[3] . "</li><li>Data Fi: " . $vals[4] . "</li></ul></p></a></div>";
            $html = $html . $divs;
        }
        if ($html == null) {
            $content = array('{result}' => "No hi ha escapades que coincideixin amb la cerca.");
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
        $this->model = new EscapadaModel($this->param);
        
        $valores_a_cambiar = array('{css}' => ESTILS, '{js}' => JS, '{APP_W}' => APP_W);
        $html;
        
        if (count($this->param) == 1) {
            $ciutat = implode(array_keys($this->param));
            $nom = implode(array_values($this->param));
            $nom = urldecode(str_replace("-", " ", $nom));
            $ciutat = urldecode(str_replace("-", " ", $ciutat));
            
            if ($item = $this->model->showItem($ciutat, $nom)) {
                $id = $item[0]['id'];
                $nom = $item[0]['zona'];
                $ciutat = $item[0]['descrip'];
                $preu = $this->model->getPreu($id);
                $preu = $preu[0][0];
                
                $html = '<div id="' . $id . '"><h2>' . $nom . '</h2><p>' . $ciutat . '</p><p>Preu unit&agrave;ri: ' . $preu . '&euro;</p><form action="' . APP_W . '/cistella/afegir/" method="post"><input style="display:none;" name="servei" value="' . $id . '"/><input style="display:none;" name="tipus" value="escapades"/><label for="places">Nombre de places: </label><input type="number" name="places" value="1" min="1"/><button id="afegir">Afegir a la cistella</button></form></div>';
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
