<?php
namespace App\Controllers;

use App\DAO\UserDAO;

class UserController {
    private UserDAO $userDAO;

    public function __construct() {
        $this->userDAO = new UserDAO();
    }

    public function index(): void {
        $users = $this->userDAO->findAll();
        require '../app/Views/user/index.php';
    }

    public function show(int $id): void {
        $user = $this->userDAO->findById($id);
        require '../app/Views/user/detail.php';
    }
}
