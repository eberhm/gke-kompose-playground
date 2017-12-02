<?php

namespace Api\Services;

class NotesService
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getOne($id)
    {
        return $this->db->fetchAssoc('SELECT * FROM notes WHERE id=?', [(int)$id]);
    }

    public function getAll()
    {
        return $this->db->fetchAll('SELECT * FROM notes');
    }

    function save($note)
    {
        $this->db->insert('notes', $note);
        return $this->db->lastInsertId();
    }

    function update($id, $note)
    {
        return $this->db->update('notes', $note, ['id' => $id]);
    }

    function delete($id)
    {
        return $this->db->delete('notes', array('id' => $id));
    }

}
