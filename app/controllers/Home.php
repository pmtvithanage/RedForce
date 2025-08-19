<?php
class Home extends Controller {
    private $homeModel;

    public function __construct() {
        $this->homeModel = $this->model('M_home');
    }

    // Default action
    public function index() {
        $data = [
            'title' => 'Welcome to RedForce',
            'description' => 'This is the home page of the Security Officers Management System.'
        ];
        $this->view('home/index', $data);
    }
}