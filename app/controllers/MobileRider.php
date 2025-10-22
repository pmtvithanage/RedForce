<?php
class MobileRider extends Controller
{
    private $mobileRiderModel;
    private $userModel;

    public function __construct()
    {
        // Check if user is logged in and has mobile rider role
        requireAuth('mobile rider');
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
        $notes = $this->getNotes();
        $data = [
            'title' => 'Dashboard',
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
        $incident_reports = $this->mobileRiderModel->getAllIncidents();
        // $this->show($incident_reports);
        $data = [
            'title' => 'Incidents',
            'incident_reports' => $incident_reports,
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

    public function getNotes()
    {
        $notes = $this->mobileRiderModel->getAllNotes();
        return $notes;
    }

    public function deleteNote()
    {
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

    public function addIncident()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Clean incoming data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Handle multiple severity checkboxes (convert to string)
            $severity = isset($_POST['severity']) ? implode(',', $_POST['severity']) : null;

            // Handle file uploads (optional)
            $uploadedFiles = [];
            if (!empty($_FILES['media_files']['name'][0])) {
                $uploadDir = APP_ROOT . '/public/uploads/incidents/';

                // Create directory if not exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                foreach ($_FILES['media_files']['tmp_name'] as $key => $tmp_name) {
                    $fileName = basename($_FILES['media_files']['name'][$key]);
                    $targetFile = $uploadDir . $fileName;

                    if (move_uploaded_file($tmp_name, $targetFile)) {
                        $uploadedFiles[] = $fileName;
                    }
                }
            }

            // Prepare data for model
            $data = [
                'user_id' => $_SESSION['user_id'],  // assuming logged-in user's ID stored in session
                'officer_name' => $_POST['officer_name'],
                'officer_role' => $_POST['officer_role'],
                'property_site' => $_POST['property_site'],
                'incident_type' => $_POST['incident_type'],
                'incident_date' => $_POST['incident_date'],
                'incident_time' => $_POST['incident_time'],
                'incident_description' => $_POST['incident_description'],
                'action_taken' => $_POST['action_taken'],
                'severity' => $severity,
                'follow_up_id' => $_POST['follow_up_id'] ?? null,
                'media_files' => !empty($uploadedFiles) ? implode(',', $uploadedFiles) : null
            ];

            // Load model
            // $this->incidentModel = $this->model('IncidentModel');

            // Insert record
            if ($this->mobileRiderModel->addIncident($data)) {
                flash('incident_message', 'Incident Report submitted successfully!');
                redirect('MobileRider/incidents'); // redirect to your incidents list
            } else {
                flash('incident_message', 'Error submitting report. Please try again.', 'alert alert-danger');
                $this->view('mobilerider/v_incidents', $data);
            }
        } else {
            // If not POST, just show form
            $data = [
                'title' => 'Incidents',
            ];
            $this->view('mobilerider/v_incidents', $data);
        }
    }

public function deleteIncident()
{
    // Validate ID
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        flash('incident_message', 'Invalid incident ID.', 'alert alert-danger');
        redirect('mobilerider/incidents');
        return;
    }

    $incidentId = $_GET['id'];

    // Fetch the incident first (to access media files)
    $incident = $this->mobileRiderModel->getIncidentById($incidentId);

    if (!$incident) {
        flash('incident_message', 'Incident not found.', 'alert alert-danger');
        redirect('mobilerider/incidents');
        return;
    }

    // Delete uploaded files if they exist
    if (!empty($incident->media_files)) {
        $files = explode(',', $incident->media_files);
        $uploadDir = APP_ROOT . '/public/uploads/incidents/';

        foreach ($files as $file) {
            $filePath = $uploadDir . trim($file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    // Delete incident record
    if ($this->mobileRiderModel->deleteIncidentById($incidentId)) {
        flash('incident_message', 'Incident deleted successfully!', 'alert alert-success');
    } else {
        flash('incident_message', 'Error deleting incident.', 'alert alert-danger');
    }

    redirect('mobilerider/incidents');
}


public function updateIncident()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize input
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $incidentId = $_POST['incident_id'] ?? null;
        if (!$incidentId) {
            flash('incident_message', 'Invalid Incident ID.', 'alert alert-danger');
            redirect('mobilerider/incidents');
            return;
        }

        // Handle multiple severity checkboxes
        $severity = isset($_POST['severity']) ? implode(',', $_POST['severity']) : null;

        // Handle new file uploads
        $uploadedFiles = [];
        if (!empty($_FILES['media_files']['name'][0])) {
            $uploadDir = APP_ROOT . '/public/uploads/incidents/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            foreach ($_FILES['media_files']['tmp_name'] as $key => $tmp_name) {
                $fileName = basename($_FILES['media_files']['name'][$key]);
                $targetFile = $uploadDir . $fileName;

                if (move_uploaded_file($tmp_name, $targetFile)) {
                    $uploadedFiles[] = $fileName;
                }
            }
        }

        // Fetch existing incident to preserve old media if not replaced
        $existingIncident = $this->mobileRiderModel->getIncidentById($incidentId);
        $mediaFiles = !empty($uploadedFiles)
            ? implode(',', $uploadedFiles)
            : ($existingIncident->media_files ?? null);

        // Prepare updated data
        $data = [
            'id' => $incidentId,
            'property_site' => $_POST['property_site'],
            'incident_type' => $_POST['incident_type'],
            'incident_description' => $_POST['incident_description'],
            'action_taken' => $_POST['action_taken'],
            'severity' => $severity,
            'media_files' => $mediaFiles
        ];

        // Call model to update
        if ($this->mobileRiderModel->updateIncident($data)) {
            flash('incident_message', 'Incident updated successfully!', 'alert alert-success');
        } else {
            flash('incident_message', 'Error updating incident.', 'alert alert-danger');
        }

        redirect('mobilerider/incidents');
    } else {
        flash('incident_message', 'Invalid request method.', 'alert alert-danger');
        redirect('mobilerider/incidents');
    }
}

    // public function show($data)
    // {
    //     echo '<pre>';
    //     print_r($data);
    //     echo '</pre>';
    // }
}

