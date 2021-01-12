<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RedisController;

/**
 * Session helper functions
 *
 */
class SessionReaderController extends Controller
{
    /**
     * Return named docker secret
     *
     * @return string
     */
    public function docker_secret(string $name)
    {
        return trim(file_get_contents('/run/secrets/' . $name));
    }

    /**
     * Decrypt data (from cookie or redis data store)
     *
     * @return string
     */
    public function decrypt(string $base64_key, string $encrypted_data)
    {
        $payload = json_decode(base64_decode($encrypted_data), true);
        $iv = base64_decode($payload['iv']);
        $key = base64_decode(substr($base64_key, 7));
        $decrypted = openssl_decrypt($payload['value'],  'AES-256-CBC', $key, 0, $iv);

        if ($decrypted === false) {
            throw new Exception('Could not decrypt the data.');
        }

        return $decrypted;
    }

    /**
     * Return data from cookie
     *
     * @return string
     */
    public function read($sessionId)
    {
        if (! is_null($decoded = json_decode($sessionId, true)) && is_array($decoded)) {
            if (isset($decoded['expires']) && $this->currentTime() <= $decoded['expires']) {
                return $decoded['data'];
            }
        }

        return '';
    }

    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        return 'login_'.'web'.'_'.sha1("Illuminate\Auth\SessionGuard");
    }
    
    /**
     * Get user info based on session cookie and data
     *
     * @return array
     */
    public function getUser()
    {
        $base64_key = $this->docker_secret('PORTAL_APP_KEY');
    
        $app_name = \Str::slug(trim($this->docker_secret('PORTAL_APP_NAME')), '_');
    
        if(!empty($_COOKIE[ $app_name . "_session"]))
        {
            $sessionId = $this->decrypt($base64_key, $_COOKIE["portal_session"]);
            
            // Update Laravel 7.22 
            // Now cookie save as: cookie_name|cookie_value instead of just cookie_value
            // Now ie: "49af282609d9e012aa0e8295d9e40602e8b968ae|Dkvvfk7RAliud5pyWiCpNU7sJwgqkTNIBHszxayg"
            if (!empty($sessionId) && strlen($sessionId > 41))
              $sessionId = substr($sessionId, 41);
 
            $prefix = $app_name.'_database_';
            $prefix .= $app_name.'_cache';

            // read data session from REDIS data store
            $redis = new RedisController('redis', 6379);
            $encrypted_data = $redis->cmd('AUTH', $this->docker_secret('PORTAL_REDIS_PASS'))
                                    ->cmd('GET', $prefix . ':' . $sessionId )
                                    ->set();     
            if(!empty($encrypted_data[1]))
            {
                $encrypted_data = unserialize($encrypted_data[1]);
                $data_string = $this->decrypt($base64_key, $encrypted_data);
                $data = @unserialize(unserialize($data_string));
                $id = (isset($data[$this->getName()]) ? $data[$this->getName()] : null); 

                if(!empty($id))
                {
                    $username = $this->docker_secret('PORTAL_DB_USERNAME');
                    $password = $this->docker_secret('PORTAL_DB_PASSWORD');
                    $dbname = $this->docker_secret('PORTAL_DB_DATABASE');
                    $dbhost = $this->docker_secret('PORTAL_DB_HOST');

                    //$dbname = "default";

                    try {
                        $conn = new \PDO("mysql:host=".$dbhost.";dbname=" . $dbname, $username, $password);
                        // set the PDO error mode to exception
                        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                        $stmt = $conn->prepare("SELECT name, email, id FROM users where id = ?");
                        $stmt->execute(["$id"]);
                        $result = $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                       
                        foreach ($stmt as $rows)
                        {
                            return $rows;         
                        }
                    }
                    catch(PDOException $e)
                    {
                        echo "Connection failed: " . $e->getMessage();
                    }
                }
            }
        }
        return null;
    }
    
    
    /**
     * Remove user when the user logout
     *
     * @return array
     */
    public function removeUser()
    {
        $base64_key = $this->docker_secret('PORTAL_APP_KEY');
    
        $app_name = \Str::slug(trim($this->docker_secret('PORTAL_APP_NAME')), '_');
    
        if(!empty($_COOKIE[ $app_name . "_session"]))
        {
            $sessionId = $this->decrypt($base64_key, $_COOKIE["portal_session"]);

            $prefix = $app_name.'_database_';
            $prefix .= $app_name.'_cache';

            // read data session from REDIS data store
            $redis = new RedisController('redis', 6379);
            $encrypted_data = $redis->cmd('AUTH', $this->docker_secret('PORTAL_REDIS_PASS'))
                                    ->cmd('DEL', $prefix . ':' . $sessionId ) 
                                    ->set();     
            
            return true;
        }
        return null;
    }


}