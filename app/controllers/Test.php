<?php
class Test extends Controller {
    private $testModel;
    public function __construct() {
        $this-> testModel = $this->model('M_test');
    }
    public function index() {
        $data = [
            'title' => 'Test Model',
            'pageTitle' => 'Test Model Page'
        ];

        $this->view('v_test', $data);
    }

    public function test2(){
        $data = [
            'title' => 'Test Model 2',
            'pageTitle' => 'Test Model Page 2'
        ];
        $this->view('v_test2', $data);
    }
}