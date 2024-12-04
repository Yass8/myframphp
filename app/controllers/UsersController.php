<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Users.php';

class UsersController extends Controller {

    private Users $model;

    public function __construct()
    {
        $this->model = new Users();
    }

    public function index() {
        $this->render('index');
    }

    public function create() {
        $data = ['nom'=>'ali','email'=>'ali@y.fr','password'=>'pass1234'];
        // $this->model->create('Users',$data);

        $this->render('create');
    }

    public function edit($id) {
        $this->render('edit',['id'=>$id]);
    }

    public function delete($id) {
         $this->render('edit',['id'=>$id]);
    }
}
