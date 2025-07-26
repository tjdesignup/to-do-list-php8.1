<?php

namespace App\Enums;

enum SessionKeyEnum: string {
    case ERRORS = 'ERRORS';
    case MESSAGE = 'MESSAGE';
    case USER_ID = 'USER_ID';
    case IS_AUTHENTICATED = 'IS_AUTHENTICATED';
    case CSRF_TOKEN = 'CSRF_TOKEN';
    case DATA = 'DATA';
}