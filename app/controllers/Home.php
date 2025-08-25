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

    // get services
    public function service() {
        $this->view('home/v_services');
    }

    // job applications
    public function premise_officer() {
        $this->view('home/v_premise_officer_job');
    }
    public function care_taker() {
        $this->view('home/v_care_taker_job');
    }
    public function mobile_rider() {
        $this->view('home/v_mobile_rider_job');
    }

}