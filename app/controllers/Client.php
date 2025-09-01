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

    // Add this method to your existing Client.php controller

//view officers (guards)
public function officers() {
    // Sample data - replace with actual database queries
    $data = [
        'title' => 'View Guards',
        'sites' => [
            'All Sites',
            'People\'s Bank PLC - Colombo 2',
            'People\'s Bank PLC - Rajagiriya', 
            'People\'s Bank PLC - Panadura',
            'People\'s Bank PLC - Battaramulla',
            'People\'s Bank PLC - Kelaniya',
            'People\'s Bank PLC - Nugoda',
            'People\'s Bank PLC - Ambalangoda',
            'People\'s Bank PLC - Balangoda',
            'People\'s Bank PLC - Avissawella',
            'People\'s Bank PLC - Jaffna',
            'People\'s Bank PLC - Kandy',
            'People\'s Bank PLC - Jawla',
            'People\'s Bank PLC - Ampara'
        ],
        'guards' => [
            [
                'id' => 'RF321',
                'name' => 'Dasun Shanaka',
                'rank' => 'JSO',
                'status' => 'On Duty',
                'site' => 'People\'s Bank PLC - Colombo 2'
            ],
            [
                'id' => 'RF411',
                'name' => 'Chamika Karunaratne',
                'rank' => 'SSO',
                'status' => 'Off Duty',
                'site' => 'People\'s Bank PLC - Rajagiriya'
            ],
            [
                'id' => 'RF345',
                'name' => 'Charith Asalanka',
                'rank' => 'SSO',
                'status' => 'On Duty',
                'site' => 'People\'s Bank PLC - Panadura'
            ],
            [
                'id' => 'RF269',
                'name' => 'Kusal Mendis',
                'rank' => 'JSO',
                'status' => 'Off Duty',
                'site' => 'People\'s Bank PLC - Battaramulla'
            ],
            [
                'id' => 'RF105',
                'name' => 'Pathum Nissanka',
                'rank' => 'OIC',
                'status' => 'On Duty',
                'site' => 'People\'s Bank PLC - Kelaniya'
            ],
            [
                'id' => 'RF456',
                'name' => 'Binura Fernando',
                'rank' => 'SSO',
                'status' => 'On Duty',
                'site' => 'People\'s Bank PLC - Nugoda'
            ],
            [
                'id' => 'RF809',
                'name' => 'Matheesha Pathirana',
                'rank' => 'LSO',
                'status' => 'On Break',
                'site' => 'People\'s Bank PLC - Ambalangoda'
            ],
            [
                'id' => 'RF129',
                'name' => 'Maheesh Theekshana',
                'rank' => 'LSO',
                'status' => 'On Duty',
                'site' => 'People\'s Bank PLC - Balangoda'
            ],
            [
                'id' => 'RF768',
                'name' => 'Angelo Mathews',
                'rank' => 'JSO',
                'status' => 'On Duty',
                'site' => 'People\'s Bank PLC - Avissawella'
            ],
            [
                'id' => 'RF811',
                'name' => 'Pawan Rathnayaka',
                'rank' => 'JSO',
                'status' => 'Off Duty',
                'site' => 'People\'s Bank PLC - Jaffna'
            ],
            [
                'id' => 'RF564',
                'name' => 'Asela Gunarathna',
                'rank' => 'LSO',
                'status' => 'On Duty',
                'site' => 'People\'s Bank PLC - Kandy'
            ],
            [
                'id' => 'RF123',
                'name' => 'Sanath Jayasuriya',
                'rank' => 'SSO',
                'status' => 'On Duty',
                'site' => 'People\'s Bank PLC - Jawla'
            ],
            [
                'id' => 'RF176',
                'name' => 'Kamindu Mendis',
                'rank' => 'OIC',
                'status' => 'Off Duty',
                'site' => 'People\'s Bank PLC - Ampara'
            ]
        ]
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