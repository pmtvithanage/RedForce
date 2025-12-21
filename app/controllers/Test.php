<?php
class Test extends Controller {
    private $testModel;
    public function __construct() {
        $this-> testModel = $this->model('M_test');
    }
    public function index() {
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Test Model Page'
        ];

        $this->view('admin/officers/v_officers', $data);
    }

    public function jobs(){
        $data = [
            'title' => 'Officers',
            'pageTitle' => 'Jobs Page'
        ];
        $this->view('admin/officers/v_jobs', $data);
    }
}