<?php
/**
 * Aqusest arxiu conté la classe abstracta Model
 * 
 * @package    Tripto\System
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Classe genèrica que heredaran tots els models
 */
class Model
{
    
    /**
     * Variable on s'instanciarà un objecte PDO
     * @var object
     */
    protected $db;
    
    /**
     * Variable amb la configuració del model
     * @var array
     */
    protected $config;
    
    /**
     * Array per a comunicar amb la base de dades.
     * @var array
     */
    protected $datain = array();
    
    /**
     * Array per comunicar amb el controller
     * @var array
     */
    protected $dataout = array();
    
    /**
     * Constructor del model
     * @param array $arr
     */
    function __construct($arr) {
        
        $this->datain = array();
        $this->dataout = $arr;
    }
    
    /**
     * Mètode per afegir arrays de comunicació amb la base de dades
     * @param array $arr
     */
    public function setDatain($arr) {
        if (isset($arr)) {
            $this->datain = $arr;
        }
    }
    
    /**
     * Mètode per afegir arrays de comunicació amb el controlador
     * @param array $arr
     */
    public function addDataout($arr) {
        
        if (isset($this->dataout)) {
            $this->dataout = array_merge($this->dataout, $arr);
        } else {
            $this->dataout = $arr;
        }
    }
    
    /**
     * Retorna les dades a la base de dades
     * @return array
     */
    public function getDatain() {
        return $this->datain;
    }
    
    /**
     * Retorna les dades al controlador
     * @return array
     */
    public function getDataout() {
        return $this->dataout;
    }
}
