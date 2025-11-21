<?php
class Test extends Controller {
    private $testModel;
    public function __construct() {
        $this-> testModel = $this->model('M_test');
    }
    public function index() {
        $data = [
            'title' => 'Reports',
            'pageTitle' => 'Test Model Page'
        ];

        $this->view('admin/reports/v_reports', $data);
    }

    public function addclients(){
        $data = [
            'title' => 'Clients',
            'pageTitle' => 'Add Clients'
        ];
        $this->view('admin/clients/v_addClient', $data);
    }
}