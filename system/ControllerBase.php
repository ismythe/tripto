<?php
/**
 * Aqusest arxiu conté la classe abstracta ControllerBase
 * 
 * @package    Tripto\System
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Classe abstracta d'on heredaran la resta de controladors.
 */
abstract class ControllerBase
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
     * Constructor genèric.
     * @param array $params
     */
    public function __construct($params) {
    }
    
    /**
     * Funció abstracta
     */
    abstract public function index();
}
