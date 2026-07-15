<?php

namespace App\Interfaces\Crm;

interface CustomerNoteRepositoryInterface
{
    public function getCustomerNotes(int $customerId, array $filters);
    public function getNote(int $id);
    public function createNote(array $data);
    public function updateNote(int $id, array $data);
    public function deleteNote(int $id);
}
