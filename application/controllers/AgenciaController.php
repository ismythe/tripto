<?php
/**
 * Aqusest arxiu conté el controlador principal de l'aplicació
 *
 * @package    Tripto\Application\Controllers
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Controlador principal de la aplicació
 *
 */
class AgenciaController extends ControllerBase
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
     * Aquesta funció mostra la pàgina principal de la aplicació
     * @return void
     */
    public function index() {
        
        $valores_a_cambiar = array('{css}' => ESTILS, '{js}' => JS, '{APP_W}' => APP_W);
        
        $this->view = new View();
        if (!$_SESSION['logged']) {
            $this->view->appendTemplate('header');
        } else {
            $this->view->appendTemplate('header_r');
        }
        $this->view->appendTemplate('search');
        $this->view->addProp($valores_a_cambiar);
        $this->view->setProp();
        $this->view->show();
    }
    
    /**
     * Funció que mostra la plantilla amb el formulari d'accés a l'aplicació
     * @return void
     */
    public function accedir() {
        if (!$_SESSION['logged']) {
            $this->model = new LoginModel($this->param);
            $valores_a_cambiar = array('{css}' => ESTILS, '{js}' => JS, '{APP_W}' => APP_W);
            $this->view = new View();
            if (!$_SESSION['logged']) {
                $this->view->appendTemplate('header');
            } else {
                $this->view->appendTemplate('header_r');
            }
            $this->view->appendTemplate('login');
            $this->view->addProp($valores_a_cambiar);
            $this->view->setProp();
            $this->view->show();
        } else {
            header('Location: ' . APP_W);
        }
    }
    
    /**
     * Funció que mostra la plantilla amb el formulari de registre de l'aplicació
     * @return void
     */
    public function registrar() {
        $this->model = new LoginModel($this->param);
        $valores_a_cambiar = array('{css}' => ESTILS, '{js}' => JS, '{APP_W}' => APP_W);
        $this->view = new View();
        if (!$_SESSION['logged']) {
            $this->view->appendTemplate('header');
        } else {
            $this->view->appendTemplate('header_r');
        }
        $this->view->appendTemplate('registrar');
        $this->view->addProp($valores_a_cambiar);
        $this->view->setProp();
        $this->view->show();
    }
    
    /**
     * Funció que crida al model de Login per controlar l'accés dels usuaris
     * @return void
     */
    public function login() {
        $this->model = new LoginModel($this->param);
        $us = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS);
        $pas = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
        $pas = md5($pas);
        if (isset($_POST['user']) && count($resultat = $this->model->login($us, $pas)) == 1) {
            $_SESSION['uid'] = $resultat;
            $_SESSION["logged"] = true;
            echo true;
        } else {
            echo false;
        }
    }
    
    /**
     * Funció que crida al model de login per a registrar un usuari a la base de dades
     * @return void
     */
    public function register() {
        $this->model = new LoginModel($this->param);
        $us = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS);
        $cog = filter_input(INPUT_POST, 'cog', FILTER_SANITIZE_SPECIAL_CHARS);
        $mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_SPECIAL_CHARS);
        $pas = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($reg = $this->model->register($us, $cog, $mail, $pas) == true) {
            header('Location: ' . APP_W . '/agencia/accedir');
        } else {
            header('Location: ' . APP_W . '/agencia/registrar');
        }
    }
    
    /**
     * Funció que neteja la sessió de l'usuari i el desconnecta
     * @return void
     */
    public function logout() {
        unset($_SESSION["logged"]);
        unset($_SESSION['uid']);
        unset($_SESSION['params']);
        unset($_SESSION['serveis']);
        unset($_SESSION['cistella']);
        header('Location: ' . APP_W);
    }
    
    /**
     * Redirigeix a l'usuari al controlador corresponent a la busca realitzada
     * @return void
     */
    public function buscar() {
        if (isset($_POST['servei'])) {
            if (isset($_POST['ciutat'])) {
                $_SESSION['params'][0] = $_POST['ciutat'];
                if (isset($_POST['data_inici'])) {
                    $_SESSION['params'][1] = $_POST['data_inici'];
                }
                if (isset($_POST['data_fi'])) {
                    $_SESSION['params'][2] = $_POST['data_fi'];
                }
            }
            switch ($_POST['servei']) {
                case 'vol':
                    header('Location: ' . APP_W . '/vol/');
                    break;

                case 'hotel':
                    header('Location: ' . APP_W . '/hotel/');
                    break;

                case 'escapada':
                    header('Location: ' . APP_W . '/escapada/');
                    break;
            }
        } else {
            echo "Error";
        }
    }
}
