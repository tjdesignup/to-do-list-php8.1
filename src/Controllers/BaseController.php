<?php

namespace App\Controllers;

use App\Repositories\BaseRepository;
use App\Services\SessionService;
use App\Services\ValidationService;
use App\Enums\SessionKeyEnum;

abstract class BaseController{

    protected ?string $content = "";
    protected BaseRepository $baseRepo;
    protected SessionService $sessionService;
    protected ValidationService $validationService;

    public function __construct(BaseRepository $baseRepo,SessionService $sessionService, ValidationService $validationService)
    {
        $this->baseRepo = $baseRepo;
        $this->sessionService = $sessionService;
        $this->validationService = $validationService;
    }

    protected function render(string $template, array $params = [],?string $content = null): void
    {
        $params = $this->getDefaultParams($params);

        $templatePath = __DIR__ . '/../Templates/'.trim($template);
        $contentPath = (!is_null($content)) ? __DIR__ . '/../Templates/'.trim($content) : null;
        
        if(!file_exists($templatePath)){;
            $this->errorPath("Template $templatePath does not exist.");
        }

        if(!is_null($contentPath) && is_string($contentPath) && !file_exists($contentPath)){
            $this->errorPath("Content $contentPath does not exist.");
        }

        if(!is_null($contentPath))
        { 
            extract($params);
            ob_start();
            include $contentPath;
            $params['content'] = ob_get_clean();
        }

        extract($params);
        ob_start();
        include $templatePath;
        $this->content = ob_get_clean();
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    protected function isCsrfTokenValidOrRedirect(string $headerLocation): mixed
    {
        $csrfToken = trim($_POST['csrfToken']);
        $csrfTokenSession = $this->sessionService->getCsrfTokenSession("UNSET");
        if($this->validationService->isCsrfTokenValidated($csrfTokenSession,$csrfToken))
        {
            return true;
        }else{
            $this->sessionService->set(SessionKeyEnum::ERRORS,"CSRF Token was not possible validated!");
            header("Location: /$headerLocation");
            exit;
        }
    }

    protected function redirect(string $headerLocation):void
    {
        header("Location: /$headerLocation");
        exit;
    }

    private function errorPath(string $message):void
    {
        http_response_code(500);
        echo $message;
        exit;
    }

    private function getDefaultParams(array $params):array
    {
        if(!isset($params['csrfToken'])){
            $params['csrfToken'] = $csrfToken = $this->sessionService->setCsrfTokenSession();
        }
        if(!isset($params['errors'])){
            $params['errors'] =  $this->sessionService->getAll(SessionKeyEnum::ERRORS,"UNSET");
        }
        if(!isset($params['message'])){
            $params['message'] =  $this->sessionService->get(SessionKeyEnum::MESSAGE,"UNSET");
        }  
        return $params;
    }
}