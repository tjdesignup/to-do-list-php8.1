<?php

namespace App\Controllers;
use App\Enums\SessionKeyEnum;

class UnregisterController extends BaseController{

    public function index()
    {
        $this->render('dash.php',[],'unregister.php');
    }

    public function unregister():void
    {
        if($this->isCsrfTokenValidOrRedirect("unregister"))
        {
            $password = trim($_POST['password']);
            $userId = $this->sessionService->get(SessionKeyEnum::USER_ID);
            $email = $this->baseRepo->getUserEmail($userId);
            $hashPassword = $this->baseRepo->getHashPasswordByEmail($email);
            if(password_verify($password,$hashPassword))
            {
                $this->baseRepo->unregister($userId);
                $this->sessionService->destroySessions();
                $this->redirect("register");
            }else{
                $this->sessionService->set(SessionKeyEnum::ERRORS,"Wrong Password!");
                $this->redirect("unregister");
            }
        }
    }
}