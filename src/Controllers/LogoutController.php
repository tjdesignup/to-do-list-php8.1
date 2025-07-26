<?php

namespace App\Controllers;

class LogoutController extends BaseController{
    public function index():void
    {
        $this->sessionService->destroySessions();
        $this->redirect("login.php");
    }
}