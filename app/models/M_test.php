<?php

class M_test {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function saveApplication($data){
        $this->db->query("INSERT INTO test (name, email, gender, description, image, reading_books, play_games, collect_stamps, watch_tv) 
                        VALUES (:name, :email, :gender, :description, :image, :reading_books, :play_games, :collect_stamps, :watch_tv)");
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image_name']);
        $this->db->bind(':reading_books', $data['reading_books']);
        $this->db->bind(':play_games', $data['play_games']);
        $this->db->bind(':collect_stamps', $data['collect_stamps']);
        $this->db->bind(':watch_tv', $data['watch_tv']);


        return $this->db->execute();
    }

    public function getApplications() {
        $this->db->query("SELECT name, email, gender, description, image, reading_books, play_games, collect_stamps, watch_tv FROM test");
        return $this->db->resultSet();
    }
}