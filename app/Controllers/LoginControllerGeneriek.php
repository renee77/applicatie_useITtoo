<?php

namespace App\Controllers;

use App\Core\SessionManager;
use App\DAO\AccountDAO;

class LoginControllerGeneriek
{
    private AccountDAO $accountDAO;
    private SessionManager $session;

    public function __construct(AccountDAO $accountDAO, SessionManager $session)
    {
        $this->accountDAO = $accountDAO;
        $this->session = $session;
    }


}
