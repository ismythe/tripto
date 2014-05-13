<?php
/**
 * Aqusest és l'encarregat d'iniciar l'aplicació
 * 
 * @package    Tripto\System
 * @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author     Ian Smythe <smythe.ian@gmail.com>
 */

require 'config.inc';

/**
 * Classe que s'executa a l'iniciar l'aplicació
 */
abstract class Index
{
    
    /**
     * Funció que inicia l'aplicació
     * @throws Exception Si hi ha hagut algun error a l'executar l'aplicació
     */
    static function run() {
        try {
            $front = Bootstrap::getInstance();
            
            $front->route();
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}

/**
 * Variable que guarda una instància de la aplicació
 * @var object
 */
$config = Config::getInstance();
$config->set('DB_HOST','localhost');
$config->set('DB_NAME', 'agency');
$config->set('DB_USERNAME', 'ian');
$config->set('DB_PASSWORD', '1234');

session_start();
if (!isset($_SESSION['cistella'])) {
    $_SESSION['cistella'] = [];
}
$jsconfig = array('root' => APP_W);
//Guardem la variable APP_W de PHP en un arxiu json per a utilitzar-la amb javascript.
file_put_contents('config.json', json_encode($jsconfig));

Index::run();
