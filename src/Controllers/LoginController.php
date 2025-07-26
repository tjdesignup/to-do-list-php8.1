<?php

namespace App\Controllers;
use App\Enums\SessionKeyEnum;

class LoginController extends BaseController{

    public function index():void
    {  
        $this->render('login.php');
    }
    
    public function login():void
    {
        if($this->isCsrfTokenValidOrRedirect("login"))
        {
            $email = htmlspecialchars(trim($_POST['email']));
            $password = trim($_POST['password']);
            if(!$this->baseRepo->userExists($email))
            {
                $this->sessionService->set(SessionKeyEnum::ERRORS,"User or password was wrong.");
                $this->redirect("login");
            }else{
                $hashedPassword = $this->baseRepo->getHashPasswordByEmail($email);
                if (password_verify($password, $hashedPassword)) {
                    $userId = $this->baseRepo->getUserId($email);
                    $this->sessionService->set(SessionKeyEnum::IS_AUTHENTICATED,true);
                    $this->sessionService->set(SessionKeyEnum::USER_ID,$userId);
                    $this->redirect("dashboard");
                } else {
                $this->sessionService->set(SessionKeyEnum::ERRORS,"User or password was wrong.");
                $this->redirect("login");
                }
            }
        }
    }

    public function userExistsEndpoint():void
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

    public function passwordVerifyEndpoint():void
    {
        $isVerify = false;
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $email = trim($data['email']?? '');
        $password = trim($data['password']?? '');
        if(strlen($email)<=255 && ($this->baseRepo->userExists($email))?? false){
            $hashedPassword = $this->baseRepo->getHashPasswordByEmail($email);
            if(password_verify($password, $hashedPassword)) 
            {
              $isVerify = true;   
            }
        }
        echo json_encode(['isVerify' => (bool)$isVerify]);
    }
}