<?php
class Client extends Controller {
    private $homeModel;

    public function __construct() {
        $this->homeModel = $this->model('M_client');
    }

    // Default action
    public function index() {
        
        
    }

    // dashboard
    public function dashboard() {
        // Sample data - replace with actual database queries
        $data = [
            'title' => 'Dashboard',
            'stats' => [
                'sites' => 8,
                'officers' => 98,
                'incidents' => 8,
                'payment_due' => '12/21'
            ],
            'messages' => [
                [
                    'sender' => 'Admin - Red Force',
                    'message' => 'Dear Mr. Fernando, kindly note that we are assigning 2 officers tonight to the Kurun...',
                    'time' => '2 hours ago'
                ],
                [
                    'sender' => 'Supervisor - Red Force', 
                    'message' => 'Dear Mr. Fernando, this is regarding the weekly supervisioni have carried out the aud...',
                    'time' => '4 hours ago'
                ],
                [
                    'sender' => 'Admin - Red Force',
                    'message' => 'Dear Mr. Fernando, this is a kind reminder about the payment due for the year 2026...',
                    'time' => '1 day ago'
                ]
            ],
            'incidents' => [
                [
                    'type' => 'Attempted Robbery',
                    'location' => 'Kurunegala Branch',
                    'description' => '2 men wearing black tried to get into the vault but succesfully contained by the guar...',
                    'severity' => 'high'
                ],
                [
                    'type' => 'Attempted Robbery',
                    'location' => 'Negarea Eliya Branch', 
                    'description' => '2 men wearing black tried to get into the vault but succesfully contained by the guar...',
                    'severity' => 'high'
                ],
                [
                    'type' => 'Premise Officer Attacked',
                    'location' => 'Colombo 5',
                    'description' => 'During an inspection, an uneasy man got into a fight with one of the guards, both...',
                    'severity' => 'medium'
                ]
            ]
        ];
        
        $this->view('client/v_dashboard', $data);
    }

    //view officers
    public function officers() {
        $data = [
            'title' => 'View Officers',
        ];
        $this->view('client/v_officers', $data);
    }

    //requests
    public function requests() {
        $data = [
            'title' => 'Requests',
        ];
        $this->view('client/v_requests', $data);
    }

    //view history
    public function history() {
        $data = [
            'title' => 'View History',
        ];
        $this->view('client/v_history', $data);
    }

    //profile
    public function profile() {
        $data = [
            'title' => 'Profile',
        ];
        $this->view('client/v_profile', $data); 
    }

}