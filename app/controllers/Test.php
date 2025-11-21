<?php
class Test extends Controller {
    private $testModel;
    public function __construct() {
        $this-> testModel = $this->model('M_test');
    }
    public function index() {
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Test Model Page'
        ];

        $this->view('admin/clients/v_clients', $data);
    }

    public function test2(){
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Test Model Page'
        ];
        $this->view('admin/clients/v_clients', $data);
    }
}