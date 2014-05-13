<?php
/**
 * Aqusest arxiu conté el model dels serveis de tipus "Vol"
 * 
 * @package    Tripto\Application\Models
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Model que gestiona les peticions a la base de dades de VolController
 *
 */
class VolModel extends Model
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
     * Retorna totes les dades d'un servei de tipus "Vol", en una zona concreta, entre dos dates.
     * @param  string $ciutat     Nom de la zona/ciutat on es vol buscar el vol.
     * @param  string $data_inici Data d'inici del vol.
     * @param  string $data_fi    Data de fi del vol.
     * @return array|bool      Retorna un array associatiu amb les dades del vol, o fals en cas d'error.
     */
    public function fullSearch($ciutat, $data_inici, $data_fi) {
        
        $stmt = $this->db->prepare("SELECT * FROM vols WHERE destinacio=:dest AND data_vol BETWEEN :data_inici AND :data_fi ");
        $stmt->bindParam(":dest", $ciutat);
        $stmt->bindParam(":data_inici", $data_inici);
        $stmt->bindParam(":data_fi", $data_fi);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * Mostra un vol concret
     * @param  string $dest Destinació del vol
     * @param  string $data   Data de sortida del vol.
     * @return array|bool  Retorna un array associatiu amb la informació del vol, o false, en cas d'error.
     */
    public function showItem($dest, $data) {
        $dest = urldecode($dest);
        $stmt = $this->db->prepare("SELECT * FROM vols WHERE destinacio=:dest AND data_vol=:data");
        $stmt->bindParam(":dest", $dest);
        $stmt->bindParam(":data", $data);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * Retorna el preu d'un vol.
     * @param  integer $id Id del vol.
     * @return array     Array amb el preu del vol.
     */
    public function getPreu($id) {
        $stmt = $this->db->prepare("SELECT preu FROM serveis where id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
}
