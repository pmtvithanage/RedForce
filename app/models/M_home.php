<?php
class M_home {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getWelcomeMessage() {
        return "Hello, welcome to RedForce!";
    }
}