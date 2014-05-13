<?php
/**
 * Aqusest arxiu conté la classe encarregada de gestionar les vistes.
 * 
 * @package    Tripto\System
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */


/**
 * Classe que genera les vistes de l'aplicació
 */
class View
{
    
    /**
     * Variable que emmagatzema els continguts de les plantilles.
     * @var string
     */
    private $template;
    
    /**
     * Array amb les pròpietats que es substituïran a les plantillles.
     * @var array
     */
    private $array_propietats;
    
    /**
     * Constructor.
     */
    public function __construct() {
    }
    
    /**
     * Afegeix el contingut d'un únic arxiu a la variable 'template'
     * @param string $arxiu Arxiu de plantilla a afegir.
     */
    public function setTemplate($arxiu) {
        $this->template = file_get_contents(APP . 'public' . DS . 'tpl' . DS . $arxiu . '.tpl');
    }
    
    /**
     * Adjunta una plantilla especificada a la variable 'template'
     *
     * Serveix per a adjuntar múltiples plantilles i poder generar un sistema de subplantilles.
     * L'odre en que es criden les subplantilles és clau per a que després es visualitzin correctament.
     *
     * @param  string $arxiu Arziu de plantilla a afegir
     */
    public function appendTemplate($arxiu) {
        $this->template = $this->template . file_get_contents(APP . 'public' . DS . 'tpl' . DS . $arxiu . '.tpl');
    }
    
    /**
     * Afegeix un array de propietats a la variable 'array_propietats'
     * @param array $array Array associatiu amb les propietats.
     */
    public function addProp($array) {
        $this->array_propietats = $array;
    }
    
    /**
     * Permet adjuntar vàries vegades un array de propietats
     *
     * @param  array $param Array de propietats.
     */
    
    public function appendProp($param) {
        if ($param || $this->array_propietats) {
            if ($this->array_propietats == null) {
                $this->array_propietats = $param;
            } else {
                $this->array_propietats = array_merge($this->array_propietats, $param);
            }
        }
    }
    
    /**
     * Replaça les propietats de la plantilla amb les de la variable 'array_propietats'
     */
    public function setProp() {
        $this->template = str_replace(array_keys($this->array_propietats), array_values($this->array_propietats), $this->template);
    }
    
    /**
     * Mostra la plantilla
     */
    public function show() {
        print_r($this->template);
    }
}
