<?php

namespace App\Controllers;

use App\Enums\SessionKeyEnum;
use Exception;

class NoteController extends BaseController{

    public function index($noteId):void
    {   
        if(is_null($noteId)){
        $data = $this->sessionService->getAll(SessionKeyEnum::DATA,"UNSET");
        $data['buttonName'] = "Add note";
        $data['action'] = "saveNote";
        }
        else{
        $data = $this->baseRepo->getNoteById($noteId);
        $data['buttonName'] = "Update note";
        $data['action'] = "updateNote";
        }
        $this->render('dash.php',['data'=>$data],'note.php');
    }

    public function saveNote():void
    {
        if($this->isCsrfTokenValidOrRedirect("note")){
            $title = htmlspecialchars(trim($_POST['title']));
            $deadline = htmlspecialchars(trim($_POST['deadline']));
            $text = htmlspecialchars(trim($_POST['text']));
            $userId = $this->sessionService->get(SessionKeyEnum::USER_ID);
            try{
                $this->baseRepo->saveNote($userId,$title,$text,$deadline);
                $this->redirect("dashboard");
            }catch(Exception $e){
                $errorMessage = "Nepodařilo se uložit poznámku: " . $e->getMessage();
                $this->sessionService->set(SessionKeyEnum::ERRORS,$errorMessage);
                $data = ['title'=>$title,'deadline'=>$deadline,'content'=>$text];
                $this->sessionService->set(SessionKeyEnum::DATA,$data);
                $this->redirect("note"); 
            }
        }
    }

    public function updateNote($noteId):void
    {
        if($this->isCsrfTokenValidOrRedirect("note")){
            $title = htmlspecialchars(trim($_POST['title']));
            $deadline = htmlspecialchars(trim($_POST['deadline']));
            $text = htmlspecialchars(trim($_POST['text']));
            try{
                $this->baseRepo->updateNotebyId($noteId,$title,$deadline,$text);
                $this->redirect("dashboard");
            }catch(Exception $e){
                $errorMessage = "Nepodařilo se uložit poznámku: " . $e->getMessage();
                $this->sessionService->set(SessionKeyEnum::ERRORS,$errorMessage);
                $data = ['id'=>$noteId,'title'=>$title,'deadline'=>$deadline,'content'=>$text];
                $this->sessionService->set(SessionKeyEnum::DATA,$data);
                $this->redirect("note?id=$noteId");  
            }
        }
    }
}