<?php
class MobileRider extends Controller
{
    private $mobileRiderModel;
    private $userModel;
    private $advertisementModel;

    public function __construct()
    {
        // Check if user is logged in and has mobile rider role
        requireAuth('mobile rider');
        $this->advertisementModel = $this->model('M_advertisements');
        $this->mobileRiderModel = $this->model('M_mobileRider');
        $this->userModel = $this->model('M_users');
    }

    // Default action - redirect to dashboard
    public function index()
    {
        redirect('mobilerider/dashboard');
    }

    // Dashboard action
    public function dashboard()
    {
        $role = 'mobile rider';
        $advertisements = $this->advertisementModel->getAdvertisementsByRole($role);

        $notes = $this->getNotes();

        $data = [
            'title' => 'Dashboard',
            'advertisements' => $advertisements,
            'notes' => $notes,
        ];
        $this->view('mobilerider/v_dashboard', $data);
    }

    // Schedule action
    public function sites()
    {
        $data = [
            'title' => 'Sites',
        ];
        $this->view('mobilerider/v_sites', $data);
    }
    // Messages
    public function messages()
    {
        $data = [
            'title' => 'Messages',
        ];
        $this->view('mobilerider/v_messages', $data);
    }
    // Incidents
    public function incidents()
    {
        $data = [
            'title' => 'Incidents',
        ];
        $this->view('mobilerider/v_incidents', $data);
    }
    // Leave Requests
    public function leaverequests()
    {
        $data = [
            'title' => 'Leave Requests',
        ];
        $this->view('mobilerider/v_leaverequests', $data);
    }
    // Profile
    public function profile()
    {
        $data = [
            'title' => 'Profile',
        ];
        $this->view('mobilerider/v_profile', $data);
    }

    public function addNote()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $noteId = isset($_POST['noteId']) && !empty($_POST['noteId']) ? $_POST['noteId'] : null;

        // Check if it's an edit or new note
        if ($noteId) {
            // Edit existing note
            if ($this->mobileRiderModel->updateNoteById($noteId, $title, $content)) {
                header("Location: " . URL_ROOT . "/mobilerider/dashboard?notes=open");
                exit;
            } else {
                echo 'Update failed';
            }
        } else {
            // Add new note
            $data = [
                'title' => $title,
                'content' => $content,
                'userID' => $_SESSION['user_userID'],
            ];

            if ($this->mobileRiderModel->addNote($data)) {
                header("Location: " . URL_ROOT . "/mobilerider/dashboard?notes=open");
                exit;
            } else {
                echo 'Add failed';
            }
        }
    }
}

    public function getNotes() {
        $notes = $this->mobileRiderModel->getAllNotes();
        return $notes;
    }

    public function deleteNote(){
        $id = $_GET['id'];
        //echo $id;
        $this->mobileRiderModel->deleteNoteById($id);
        header(header: "Location:" . URL_ROOT . "/mobilerider/dashboard?notes=open");
    }

public function editNote()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $noteId = $_POST['noteId'];
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);

        if ($this->mobileRiderModel->updateNoteById($noteId, $title, $content)) {
            header("Location: " . URL_ROOT . "/mobilerider/dashboard?notes=open");
            exit;
        } else {
            echo 'Update failed';
        }
    }
}


}