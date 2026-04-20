<?php

class M_test {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function saveApplication($data){
        $name = $data['name'] ?? ($data['username'] ?? '');
        $email = $data['email'] ?? ($data['contact_email'] ?? '');
        $gender = $data['gender'] ?? ($data['gender_identity'] ?? '');
        $description = $data['description'] ?? ($data['message'] ?? '');
        $imageName = $data['image_name'] ?? '';
        $readingBooks = !empty($data['reading_books']) ? 1 : 0;
        $playGames = !empty($data['play_games']) ? 1 : 0;
        $collectStamps = !empty($data['collect_stamps']) ? 1 : 0;
        $watchTv = !empty($data['watch_tv']) ? 1 : 0;

        $this->db->query("INSERT INTO test (name, email, gender, description, image, reading_books, play_games, collect_stamps, watch_tv) 
                        VALUES (:name, :email, :gender, :description, :image, :reading_books, :play_games, :collect_stamps, :watch_tv)");
        
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':gender', $gender);
        $this->db->bind(':description', $description);
        $this->db->bind(':image', $imageName);
        $this->db->bind(':reading_books', $readingBooks);
        $this->db->bind(':play_games', $playGames);
        $this->db->bind(':collect_stamps', $collectStamps);
        $this->db->bind(':watch_tv', $watchTv);


        return $this->db->execute();
    }

    public function getApplications() {
        $this->db->query("SELECT name, email, gender, description, image, reading_books, play_games, collect_stamps, watch_tv FROM test");
        return $this->db->resultSet();
    }
}