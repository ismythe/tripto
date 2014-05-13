<?php
/**
 * Aqusest arxiu conté el model utilitzat per registrar i controlar l'accés dels usuaris
 * 
 * @package    Tripto\Application\Models
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

/**
 * Model que gestiona les peticions a la base de dades referent als usuaris, de AgenciaController
 *
 */
class LoginModel extends Model
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
     * Busca un usuari a la base de dades i fa login.
     * @param  integer $us  Correu (únic) de l'usuari
     * @param  string $pas Contrassenya encriptada en md5.
     * @return array      Retorna un array amb l'id de l'usuari.
     */
    public function login($us, $pas) {
        $stmt = $this->db->prepare("SELECT * FROM usuaris WHERE email=:user AND password=:pass");
        $stmt->bindParam(":user", $us);
        $stmt->bindParam(":pass", $pas);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $result[0]['id'];
    }
    
    /**
     * Registra un usuari a la base de dades
     * @param  string $us   Nom de l'usuari
     * @param  string $cog  Cognom de l'usuari
     * @param  string $mail Correu electrònic de l'usuari.
     * @param  string $pas  Contrassenya encriptada en md5.
     * @return bool      Retorna true si s'ha insertat correctament l'usuari, false si hi ha hagut errors.
     */
    public function register($us, $cog, $mail, $pas) {
        $rol = 1;
        $stmt = $this->db->prepare("CALL sp_registrar_usuari(:us,:cog,:mail,:pass,:rol);");
        $stmt->bindParam(":us", $us);
        $stmt->bindParam(":cog", $cog);
        $stmt->bindParam(":mail", $mail);
        $stmt->bindParam(":pass", $pas);
        $stmt->bindParam(":rol", $rol);
        $stmt->execute();
        
        $lastid = $this->db->query('SELECT LAST_INSERT_ID()')->fetchAll(PDO::FETCH_COLUMN) [0];
        $idres = $this->db->query('SELECT id FROM usuaris ORDER BY id DESC LIMIT 1;')->fetchAll(PDO::FETCH_COLUMN) [0];
        
        if ($idres == $lastid) {
            return true;
        } else {
            return false;
        }
    }
}
