<?php

namespace App\Traits;
use App\Traits\ApiTrait;

trait HelperTrait
{
    use ApiTrait;
    
    public static function checkAccessCrud($crud, $filter = false, $filterOption = "")
    {
        // if needed flush permission cache with: php artisan permission:cache-reset
        $auth_person = \App\Models\User::where('email', backpack_user()->email)->with('faculties')->first(); 
        $crud->crud->denyAccess(['list', 'create', 'delete', 'update', 'show' ]);
      
        // if (super) admin
        if ($auth_person && backpack_user()->hasAnyRole(['admin']))
        {
            $crud->crud->allowAccess(['list', 'create', 'delete', 'update', 'show']);
            return;
        } 
        
        $controllerName = (new \ReflectionClass($crud))->getShortName();
        $controllerName = substr($controllerName, 0, -14);
        
        // read permission
        if (backpack_user()->can('list ' . $controllerName))
            $crud->crud->allowAccess('list');
        if (backpack_user()->can('create ' . $controllerName))
            $crud->crud->allowAccess('create');
        if (backpack_user()->can('delete ' . $controllerName))
            $crud->crud->allowAccess('delete');
        if (backpack_user()->can('update ' . $controllerName))
            $crud->crud->allowAccess('update');
        if (backpack_user()->can('show ' . $controllerName))
            $crud->crud->allowAccess('show');
          
        // filter result  
        if($filter)
        {
            if(!empty($filterOption))
                $crud->crud->addClause('whereIn', $filterOption,  \Arr::pluck($auth_person->faculties, 'id'));
            else
                $crud->crud->addClause('whereIn', 'faculty_id',  \Arr::pluck($auth_person->faculties, 'id'));
        }

        if($controllerName === "User")
        {
            //$faculties = $auth_person->faculties->pluck('id')->toArray();
            $crud->crud->addClause('whereHas', 'faculties',  function ($q) use($auth_person) {
                       $q->whereIn('id', \Arr::pluck($auth_person->faculties, 'id') );
                   }
            );
        }
    }
    
    public static function getPersonInfo($email = null)
    {
        $PersonInfo = app('\App\Services\PersonInfo');
        
        // new api call with uOaccessId instead of uOcampusId...
        $res = self::getStudentNameByEmployeeId($email, true); // TODO add a redis cache if needed
        if (!empty($res->first_name) && !empty($res->last_name)) {
            $PersonInfo->firstName = $res->first_name;
            $PersonInfo->lastName = $res->last_name;
            $PersonInfo->found = true;
            $PersonInfo->id = $res->emplid;
            
            return $PersonInfo;
        }

        $PersonInfo->firstName = __("Not found (uOcampus)");
        $PersonInfo->lastName = __("Not found (uOcampus)");
        return $PersonInfo;
        
        /*
        // QA need the zzz (pre-prod)
        if ((filter_var(env('PORTAL_APP_DEBUG', docker_secret('PORTAL_APP_DEBUG')), FILTER_VALIDATE_BOOLEAN) || env('PORTAL_APP_ENV', docker_secret('PORTAL_APP_ENV')) === 'testing' || env('PORTAL_APP_ENV', docker_secret('PORTAL_APP_ENV')) === 'qa') &&  strpos(strtolower($email), 'zzz_') === false) {
            // dev only (ESB_BASE_URI=https://apigw-dev.uottawa.ca/api/v1/)
            $email = "zzz_" . $email; // needed for dev
        }
        
        $res =(strpos(strtolower($email), '@uottawa.ca') !== false ? self::getPersonIdByEmail($email) : null);
        
        
        
        if (!empty($res->emplid) && !empty($res->e_addr_type)) {
            $res = self::getStudentNameByEmployeeId($res->emplid, true); // TODO add a redis cache if needed
            if (!empty($res->first_name) && !empty($res->last_name)) {
                $PersonInfo->firstName = $res->first_name;
                $PersonInfo->lastName = $res->last_name;
                $PersonInfo->found = true;
                $PersonInfo->id = $res->emplid;
                
                return $PersonInfo;
            }

            $PersonInfo->firstName = __("Not found (uOcampus)");
            $PersonInfo->lastName = __("Not found (uOcampus)");
            return $PersonInfo;      
        }
        
                
        $PersonInfo->firstName = __("Not found (reverse lookup)");
        $PersonInfo->lastName = __("Not found (reverse lookup)");
        return $PersonInfo;
        
        */
    }

}
