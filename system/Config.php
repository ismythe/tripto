<?php
/**
 * Aqusest arxiu guarda la configuració de la aplicació
 * 
 * @package    Tripto\System
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Classe on s'emmagatzema la configuració de la aplicació
 */
class Config
{
    
    /**
     * Variable per emmagatzemar una instància de la pròpia classe.
     * @var object
     */
    static $instance;
    
    /**
     * Array on es passaran els paràmetres a guardar
     * @var array
     */
    private $data = array();
    
    /**
     * Mètode per a crear una instància Singleton
     * @return object Retorna una instància de la pròpia classe.
     */
    public static function getInstance() {
        if (!(self::$instance) instanceof self) {
            self::$instance = new self;
            return self::$instance;
        } else {
            return self::$instance;
        }
    }
    
    /**
     * Constructor. Inicialitza l'array de dades.
     */
    private function __construct() {
        $this->data = array();
    }
    
    /**
     * Emmagatzema un valor a l'array de dades.
     * @param string $nom  Clau de l'array associatiu.
     * @param mixed $valor Valor de l'array associatiu.
     */
    public function set($nom, $valor) {
        if (!array_key_exists($nom, $this->data)) {
            $this->data[$nom] = $valor;
        }
    }
    
    /**
     * Retorna el valor de l'array de dades.
     * @param  string $nom Clau de l'array associatiu.
     * @return mixed      El valor de l'array associatiu.
     */
    public function get($nom) {
        if (array_key_exists($nom, $this->data)) {
            return $this->data[$nom];
        }
    }
}
