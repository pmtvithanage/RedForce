<?php
class M_mobilerider
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function addNote($data)
    {
        $this->db->query("INSERT INTO notes ( userID, title, content, created_at) VALUES (:userID, :title, :content, NOW())");

        // Bind values
        $this->db->bind(":userID", $data['userID']);
        $this->db->bind(":title", $data['title']);
        $this->db->bind(":content", $data['content']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllNotes() {
        $this->db->query("SELECT * FROM notes");
        return $this->db->resultSet();
    }

   public function deleteNoteById($id) {
    $this->db->query("DELETE FROM notes WHERE id = :id");
    $this->db->bind(':id', $id);
    return $this->db->execute();
}

public function updateNoteById($id, $title, $content) {
    $this->db->query("UPDATE notes SET title = :title, content = :content WHERE id = :id");
    $this->db->bind(":id", $id);        // Changed from :userID to :id
    $this->db->bind(":title", $title);
    $this->db->bind(":content", $content);
    return $this->db->execute();
}



}
