<?php

namespace App\Controllers;
use App\Enums\SessionKeyEnum;

class RegisterController extends BaseController{

    public function index():void
    {
        $this->render('register.php');
    }

    public function register():void
    {
        if($this->isCsrfTokenValidOrRedirect("register"))
        {
            $email = htmlspecialchars(trim($_POST['email']));
            $password = trim($_POST['password']);
            if($this->validationService->isEmailValidated($email) && $this->validationService->isPasswordValidated($password))
            {
                if($this->baseRepo->userExists($email))
                {
                    $this->sessionService->set(SessionKeyEnum::ERRORS,"User with this email exists.");
                    $this->redirect("register");
                }else{   
                    $passHash = password_hash($password,PASSWORD_DEFAULT);
                    $this->baseRepo->register($email,$passHash);
                    $this->sessionService->set(SessionKeyEnum::MESSAGE,"Registration was succesfull!");
                    $this->redirect("login");
                }
            }else{
                $this->sessionService->set(SessionKeyEnum::ERRORS,$this->validationService->errors);
                $this->redirect("register");
            }
        }
    }


    public function emailExistsEndpoint():void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $email = trim($data['email']?? '');
        if(strlen($email)<=255){
            $exists = $this->baseRepo->userExists($email) ?? false;           
        }else{
            $exists = false;
        }
        echo json_encode(['exists' => (bool)$exists]);
    }

    public function emailDomainValidationEndpoint():void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $email = trim($data['email']?? '');
        if(strlen($email)<=255){
            $domainExists = $this->validationService->emailDomainValidation($email) ?? false;
        }else{
            $domainExists = false;
        }
        echo json_encode(['domainExists' => (bool)$domainExists]);
    }
}
