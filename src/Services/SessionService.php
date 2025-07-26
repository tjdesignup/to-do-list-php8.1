<?php

namespace App\Services;
use App\Enums\SessionKeyEnum;

class SessionService{

    public function destroySessions():void
    {
        $_SESSION = [];
        if(ini_get("session.use_cookies")){
            $params = session_get_cookie_params();
            setcookie(session_name(),'',time()-4200,$params['path'],$params['domain'],$params['secure'],$params['httponly']);
        }
        session_destroy();
    }
    public function set(SessionKeyEnum $enumKey, mixed $value):void
    {
        if(!isset($_SESSION[$enumKey->value]) || !is_array($_SESSION[$enumKey->value])) $_SESSION[$enumKey->value] = [];
        if(is_array($value))
        {
            $_SESSION[$enumKey->value] = array_merge($_SESSION[$enumKey->value], $value);
        }else{
            $_SESSION[$enumKey->value][] = $value;
        }
    }

    public function getAll(SessionKeyEnum $enumKey,string $unset = "NO_UNSET"):mixed
    {
        $values = $_SESSION[$enumKey->value] ?? null;
        if($unset === "UNSET") unset($_SESSION[$enumKey->value]);
        return $values;
    }
    public function get(SessionKeyEnum $enumKey,string $unset = "NO_UNSET"):mixed
    {
        $value = $_SESSION[$enumKey->value] ?? null;
        if(is_array($value)) $value = $value[0];
        if($unset === "UNSET") unset($_SESSION[$enumKey->value]);
        return $value;
    }

    public function setCsrfTokenSession():string
    {
        $enumKey = SessionKeyEnum::CSRF_TOKEN;
        $value = bin2hex(random_bytes(32));
        $_SESSION[$enumKey->value] = $value;
        return $value;
    }
    
    public function getCsrfTokenSession(string $unset = "NO_UNSET"):mixed
    {
        $enumKey = SessionKeyEnum::CSRF_TOKEN;
        $value = $_SESSION[$enumKey->value] ?? null;
        if($unset === "UNSET") unset($_SESSION[$enumKey->value]);
        return $value;
    }
}