<?php

require_once __controllers . '/Controller.php';
require_once __models . '/Users.php';

class UsersController extends Controller {

    private Users $users;

    public function __construct()
    {
        $this->users = new Users();
    }

    public function index() {
        
        $users = $this->users->selectAll('users');
        
        $this->render('index', ['users' => $users]);
    }

    public function create() {

        $this->render('create');
    }

    public function edit($id) {
        
        $this->render('edit');
    }

    public function delete($id) {

        $this->render('edit',['id'=>$id]);
    }
}
