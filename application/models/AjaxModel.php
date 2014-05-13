<?php
/**
 * Aqusest arxiu conté el model de les solicituts AJAX
 * 
 * @package    Tripto\Application\Models
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Model principal de les peticions en AJAX
 *
 */
class AjaxModel extends Model
{
    
    /**
     * Variable protegida que retorna les dades del model. Comunica amb Controller
     * @var array
     */
    protected $dataout;
    
    /**
     * Variable que s'utilitzarà per a connectar-se a la base de dades a través de PDO.
     * @var object
     */
    protected $db;
    
    /**
     * Constructor del model.
     *
     * Inicialitza un objecte PDO amb els paràmetres extrets de la coniguració.
     * @param array $params
     */
    public function __construct($params) {
        parent::__construct($params);
        $this->addDataOut($params);
        try {
            $config = Config::getInstance();
            $this->db = new PDO('mysql:host=' . $config->get('DB_HOST') . ';dbname=' . $config->get('DB_NAME') . ';charset=UTF8', $config->get('DB_USERNAME'), $config->get('DB_PASSWORD'),array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
        catch(PDOException $e) {
            print "<p>Error: No es pot connectar amb la base de dades </p>";
            print "<p>Error: " . $e->getMessage() . "</p>\n";
            exit();
        }
    }
    
    /**
     * Retorna l'array DataOut
     * @return array
     */
    public function getDataOut() {
        return $this->dataout;
    }
    
    /**
     * Afegeix paràmetres a l'array DataOut
     * @param array $params
     */
    public function addDataOut($params) {
        if (isset($this->dataout)) {
            $this->dataout = array_merge($this->dataout, $params);
        } else {
            $this->dataout = $params;
        }
    }
    
    /**
     * Busca la ciutat o zona on està un servei.
     * @param  string $tipus Tipus de servei a buscar
     * @return array|bool Retorna un array associatiu amb la zona, o en cas d'error, returna fals
     */
    public function getZona($tipus) {
        $sentencia;
        switch ($tipus) {
            case 'hotel':
                $sentencia = "SELECT ciutat FROM hotels;";
                break;

            case 'vol':
                $sentencia = "SELECT destinacio FROM vols;";
                break;

            case 'escapada':
                $sentencia = "SELECT zona FROM plans;";
                break;
        }
        $stmt = $this->db->prepare($sentencia);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * Busca l'adreça d'un hotel.
     * @param  integer $id Id de l'hotel
     * @return array     Resultat amb l'adreça de l'hotel, en format d'array associatiu
     */
    public function getAdreca($id) {
        $stmt = $this->db->prepare("SELECT adreca,ciutat FROM hotels WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }
}

