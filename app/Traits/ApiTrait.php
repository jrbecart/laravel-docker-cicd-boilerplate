<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use GuzzleHttp\Exception\RequestException;

trait ApiTrait
{
 
    // Helper method to get all results or only first one
    public static function getResult($result_list, $firstResult)
    {
        if (!empty($result_list)) {
            if ($firstResult) {
                if (is_array($result_list)) {
                    return $result_list[0];
                }
            }
            return $result_list;
        }
        return null;
    }
    
    protected static function sendRequestApi($param)
    {
      
        $consumer_key = docker_secret('PORTAL_ESB_CONSUMER_KEY');
        $consumer_secret = docker_secret('PORTAL_ESB_CONSUMER_SECRET');
        $base_uri = docker_secret('PORTAL_ESB_BASE_URI');
      
        $http = new Client();
        $stack = HandlerStack::create();
        $auth = new Oauth1(
            [
            'consumer_key'    => $consumer_key,
            'consumer_secret' => $consumer_secret,
            'token_secret' => false,
            ]
        );

        $stack->push($auth);
        $client = new Client(
            [
            'base_uri' => $base_uri,
            'handler' => $stack,
            'auth' => 'oauth',
            //'http_errors' => false
            ]
        );
        
        $header = array('Accept' => 'application/json');
        try {
            $response = $client->get($param, ['headers' => $header]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $exception = $e->getResponse()->getBody();
                $content = $exception->getContents();
                \Log::error("[ESB CALL] Param: " . $param . " Error: " . $content);
                return $content;
            } else {
                // network issue
                $message = $e->getMessage();
                \Log::error("[ESB NETWORK] Param: " . $param . " Error: " . $message);
                \Alert::warning(__('Error with NETWORK or API') . ' (' . $param . ') Message: ' .  $message)->flash();
                return $message;
            }
        }

        // check (if needed) $response->getStatusCode();
        return $response->getBody()->getContents();
    }
    
    public static function getEmployeeIdByUOcampusId($employeeId)
    {
        $getAll = "person/" . $employeeId . "/external_system";
        $response = self::sendRequestApi($getAll);
        $result = json_decode($response);
      
        if (!empty($result->ps_uo_extrn_sys_vw_list->ps_uo_extrn_sys_vw)) {
            foreach ($result->ps_uo_extrn_sys_vw_list->ps_uo_extrn_sys_vw as $extrn_sys) {
                if ($extrn_sys->external_system === "HRE" && $extrn_sys->current_flag === "Y") {
                    return $extrn_sys->external_system_id;
                }
            }
        }
        return null;
    }
    
    // reverse_lookup to check
    public static function getPersonIdByEmail($email, $firstResult = true)
    {
        $request = "person/search/email?email=". $email ;
        $response = self::sendRequestApi($request);
        $result = json_decode($response);

        if (!empty($result->email_list->email)) {
            return self::getResult($result->email_list->email, $firstResult);
        }
        return null;
    }

    // call one time when dashboard displayed
    public static function testApi()
    {

        $apiResults = null;

        // test person API
        $request = "person/123456789/name";
        $response = self::sendRequestApi($request);
        $result = json_decode($response);
        $result->name = "Person/Student";
        if (!empty($result->error)) {
            $apiResults[] = $result;
        }

        // test external_system API
        $getAll = "person/123456789/external_system";
        $response = self::sendRequestApi($getAll);
        $result = json_decode($response);
        $result->name = "external_system";
        if (!empty($result->error)) {
            $apiResults[] = $result;
        }

        // test employee API
        $getAll = "employee/search?first=test";
        $response = self::sendRequestApi($getAll);
        $result = json_decode($response);
        $result->name = "Employee";
        if (!empty($result->error)) {
            $apiResults[] = $result;
        }

        // test reverse lookup API
        $request = "person/search/email?email=jesuisuntest@uottawa.ca" ;
        $response = self::sendRequestApi($request);
        $result = json_decode($response);
        $result->name = "Reverse lookup email";
        if (!empty($result->error)) {
            $apiResults[] = $result;
        }
          
        return  $apiResults;
    }

    /*
    |--------------------------------------------------------------------------
    | Esb API call for student data (uOcampus)
    |--------------------------------------------------------------------------
    */
    
    public static function getStudentNameByEmployeeId($studentId, $firstResult = true)
    {
        $today = date("Y-m-d");
        $request = "person/". $studentId ."/name/PRI?effdt=$today";
        $response = self::sendRequestApi($request);
        $result = json_decode($response);
      
        if (!empty($result->name_list->name)) {
            return self::getResult($result->name_list->name, $firstResult);
        }
        return null;
    }
    
    public static function getStudentEmailById($studentId, $firstResult = true)
    {
        $request = "person/". $studentId ."/email";
        $response = self::sendRequestApi($request);
        $result = json_decode($response);
      
        if (!empty($result->email_list->email)) {

            if(is_array($result->email_list->email))
            {
                // first try student email
                if (strpos($studentId, "1000") !== 0)
                {
                    foreach($result->email_list->email as $email)
                    {
                        if (!empty($email->e_addr_type)) {
                            if ($email->e_addr_type === "UOTS") { //employee
                                return $email->email_addr;
                            }
                        }
                    }
                }

                // try employee email
                foreach($result->email_list->email as $email)
                {
                    if (!empty($email->e_addr_type)) {
                        if ($email->e_addr_type === "UOTE") { //employee
                            return $email->email_addr;
                        }
                    }
                }
            }
            else
            {
                return $result->email_list->email->email_addr;
            }

            //return self::getResult($result->email_list->email, $firstResult);
        }
        return null;
    }
    
    public static function getStudentLangById($studentId, $firstResult = true)
    {
        $request = "person/". $studentId ."/preferred_comm_lang";
        $response = self::sendRequestApi($request);
        $result = json_decode($response);
      
        if (!empty($result->ps_scc_comm_pref_list->ps_scc_comm_pref)) {
            return self::getResult($result->ps_scc_comm_pref_list->ps_scc_comm_pref, $firstResult);
        }
        return null;
    }
    
    // will return acad_career, effdt_from, effdt_to, acad_org, acad_group, prog_status
    public static function getStudentFacDepById($studentId, $firstResult = true)
    {
        $today = date("Y-m-d");
        $request = "person/". $studentId ."/program_stack?effdt=$today"; // more data

        $response = self::sendRequestApi($request);
        $result = json_decode($response);
      
        return $result;
    }
    
    /*
    |--------------------------------------------------------------------------
    | Esb API call for institution data
    |--------------------------------------------------------------------------
    */
    
    
    /*
    |--------------------------------------------------------------------------
    | Esb API call for employee data (Banner)
    |--------------------------------------------------------------------------
    */
     
    public static function getEmployeeByIdAll($employeeId, $firstResult = true)
    {
        $getAll = "employee/". $employeeId ."/?email=true&fac_dep=true&lang=true&name=true&location=true&telephone=true&isprof=true&info=true";
        $response = self::sendRequestApi($getAll);
      
        if (!empty(json_decode($response))) {
            return self::getResult(json_decode($response), $firstResult);
        }
        return null;
    }
    
    public static function getEmployeeByIdEmail($employeeId, $firstResult = true)
    {
        $getEmail = "employee/". $employeeId ."/email" ;
        $response = self::sendRequestApi($getEmail);
      
        if (!empty(json_decode($response)->email_list->email->EMAIL_ADDRESS)) {
            return self::getResult(json_decode($response)->email_list->email->EMAIL_ADDRESS, $firstResult);
        }
        return null;
    }
    
    public static function getEmployeeByIdFacdep($employeeId, $firstResult = true)
    {
        $getFacdep = "employee/". $employeeId ."/facdep" ;
        $response = self::sendRequestApi($getFacdep);
      
        if (!empty(json_decode($response)->facdep_list->facdep)) {
            return self::getResult(json_decode($response)->facdep_list->facdep, $firstResult);
        }
        return null;
    }
    
    public static function getEmployeeByIdLang($employeeId, $firstResult = true)
    {
        $getLang = "employee/". $employeeId ."/lang" ;
        $instance = new static();
        $response = $instance->sendRequestApi($getLang);
      
        if (!empty(json_decode($response)->lang_list->lang->CORRESPONDENCE_LNG)) {
            return self::getResult(json_decode($response)->lang_list->lang->CORRESPONDENCE_LNG, $firstResult);
        }
        return null;
    }
    
    public static function getEmployeeNameById($employeeId, $firstResult = true)
    {
        $getName = "employee/". $employeeId ."/name" ;
        $response = self::sendRequestApi($getName);
      
        if (!empty(json_decode($response)->name_list->name)) {
            return self::getResult(json_decode($response)->name_list->name, $firstResult);
        }
        return null;
    }
    
    public static function getEmployeeByIdLocation($employeeId, $firstResult = true)
    {
        $getLocation = "employee/". $employeeId ."/location" ;
        $response = self::sendRequestApi($getLocation);
      
        if (!empty(json_decode($response)->location_list)) {
            return self::getResult(json_decode($response)->location_list, $firstResult);
        }
        return null;
    }
    
    public static function getEmployeeByIdTelephone($employeeId, $firstResult = true)
    {
        $getTelephone = "employee/". $employeeId ."/telephone" ;
        $response = self::sendRequestApi($getTelephone);
      
        if (!empty(json_decode($response)->telephone_list)) {
            return self::getResult(json_decode($response)->telephone_list, $firstResult);
        }
        return null;
    }
    
    public static function getEmployeeByIdIsprof($employeeId, $firstResult = true)
    {
        $getIsprof = "employee/". $employeeId ."/isprof" ;
        $response = self::sendRequestApi($getIsprof);
      
        if (!empty(json_decode($response)->isprof_list->isprof->IS_PROF)) {
            return self::getResult(json_decode($response)->isprof_list->isprof->IS_PROF, $firstResult);
        }
        return null;
    }
    
    public static function getEmployeeByIdInfo($employeeId, $firstResult = true)
    {
        $getInfo = "employee/". $employeeId ."/info" ;
        $response = self::sendRequestApi($getInfo);
      
        if (!empty(json_decode($response)->info_list->info)) {
            return self::getResult(json_decode($response)->info_list->info, $firstResult);
        }
        return null;
    }
}
