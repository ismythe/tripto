<?php
/**
 * Aqusest arxiu conté la classe encarregada de gestionar les rutes.
 * 
 * @package    Tripto\System
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Classe que s'encarrega de gestionar les rutes de la aplicació
 *
 */
class Bootstrap
{
    
    /**
     * Variable que emmagatzema el controlador a cridar
     * @var string
     */
    protected $controller;
    
    /**
     * Variable que emmagatzema l'acció a cridar
     * @var string
     */
    protected $action;
    
    /**
     * Variable que emagatzema els paràmetres agafats per URL
     * @var array
     */
    protected $params;
    
    /**
     * Variable per instanciar-se de manera Singleton
     * @var object
     */
    static $instance;
    
    /**
     * Variable per emmagatzemar la URL
     * @var string
     */
    static $request;
    
    /**
     * Funció que comprova si la classe s'ha instanciat.
     * @return object Retorna una instància de la pròpia classe.
     */
    public static function getInstance() {
        
        if (!(self::$instance) instanceof self) {
            
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor. Separa la URL en controlador, acció i paràmetres.
     */
    function __construct() {
        
        $request = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT);
        $parts = explode('/', trim($request, '/'));
        
        //treiem part del nom d'aplicació
        array_shift($parts);
        $this->controller = !empty($parts[0]) ? $parts[0] === "index.php" ? DEFAULT_CONTROLLER : $parts[0] : DEFAULT_CONTROLLER;
        $this->action = !empty($parts[1]) ? $parts[1] : DEFAULT_ACTION;
        
        // completem un array associatiu amb el paràmetres.
        if (!empty($parts[2])) {
            $keys = $values = array();
            for ($i = 2, $cnt = count($parts); $i < $cnt; $i++) {
                if ($i % 2 == 0) {
                    
                    // si és parell és una clau
                    $keys[] = $parts[$i];
                } else {
                    
                    //és imparell és un valor
                    $values[] = $parts[$i];
                }
            }
            $this->params = array_combine($keys, $values);
        }
    }
    
    /**
     * Funció que executa un controlador i acció.
     * @throws Exception Si no hi ha controlador.
     * @throws Exception Si no hi ha acció.
     */
    public function route() {
        
        $cont = $this->controller;
        $action = $this->action;
        $param = $this->params;
        
        $classe = ucfirst(strtolower($this->controller)) . 'Controller';
        
        if (class_exists($classe)) {
            
            $routeCont = new ReflectionClass($classe);
            if ($routeCont->hasMethod($this->action)) {
                
                $controller = $routeCont->newInstance($this->params);
                
                $method = $routeCont->getMethod($this->action);
                
                $method->invoke($controller);
            } else {
                throw new Exception("No Action");
            }
        } else {
            throw new Exception("No Controller");
        }
    }
}
