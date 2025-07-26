<?php

namespace App\Controllers;

use App\Enums\SessionKeyEnum;

class DashController extends BaseController{
   
    public function index(int $pageNumber):void
    {   
        session_regenerate_id(true);
        $userId = $this->sessionService->get(SessionKeyEnum::USER_ID);
        $notes = $this->baseRepo->getPaginatedNotesByUserId($userId,$pageNumber);
        $this->render('dash.php',['notes'=>$notes],'noteBox.php');
    }

    public function deleteAllNotes():void
    {
        if($this->isCsrfTokenValidOrRedirect("dashboard")){
            $userId = (int)$this->sessionService->get(SessionKeyEnum::USER_ID);
            $this->baseRepo->deleteAllNotes($userId);
            $this->redirect("dashboard");
        }
    }
}