<?php 

namespace App\Services;

class ValidationService{
    public array $errors = [];
    public function isEmailValidated(string $email):bool
    {   
        $isEmailvalidated = true;
        if(empty($email))
        {
            $this->errors[] = "Email is required!"; 
            $isEmailvalidated = false;
        }
        else{
            if(filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $domain = substr(strrchr($email, "@"), 1);
                if (!checkdnsrr($domain, "MX"))
                {
                    $this->errors[] = "Domain of e-mail does not exist."; 
                    $isEmailvalidated = false;
                }
            }
            else{
                $this->errors[] = "Email is not correct!";
                $isEmailvalidated = false;
            }
            if(strlen($email)>255)
            {
                $this->errors[] = "Email is too long!"; 
                $isEmailvalidated = false;
            }
        }
        return $isEmailvalidated;
    }

    public function isPasswordValidated(string $password):bool
    {
    $isPasswordValidated = true;
    if (empty($password))
    {
        $this->errors[] = "Password is required."; 
        $isPasswordValidated = false;
    }
    if (strlen($password) < 8)
    {
        $this->errors[] = "Password must be at least 8 characters."; 
        $isPasswordValidated = false;
    }
    if (strlen($password) > 72)
    {
        $this->errors[] = "Password is too long."; 
        $isPasswordValidated = false;
    }
    if (!preg_match('/[A-Z]/', $password))
    {
        $this->errors[] = "Password must contain at least one uppercase letter."; 
        $isPasswordValidated = false;
    }
    if (!preg_match('/[a-z]/', $password))
    {
        $this->errors[] = "Password must contain at least one lowercase letter."; 
        $isPasswordValidated = false;
    }
    if (!preg_match('/[0-9]/', $password))
    {
        $this->errors[] = "Password must contain at least one number."; 
        $isPasswordValidated = false;
    }
    return $isPasswordValidated;
    }
    
    public function isCsrfTokenValidated(string $crsfTokenHashSession,string $csrfTokenHash):bool
    {
        return isset($crsfTokenHashSession, $csrfTokenHash) && hash_equals($crsfTokenHashSession, $csrfTokenHash);
    }

    public function emailDomainValidation($email):bool
    {
        if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
            $domain = substr(strrchr($email, "@"), 1);
            return (checkdnsrr($domain, "MX")) ? true : false;
        }else
        {
            return false;
        }
    }
}