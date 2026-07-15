<?php

namespace App\Repositories\Crm;

use App\Interfaces\Crm\CustomerNoteRepositoryInterface;
use App\Models\CustomerNote;

class CustomerNoteRepository implements CustomerNoteRepositoryInterface
{
    public function getCustomerNotes(int $customerId, array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        $query = CustomerNote::with('user')->where('customer_id', $customerId);
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getNote(int $id)
    {
        return CustomerNote::findOrFail($id);
    }

    public function createNote(array $data)
    {
        return CustomerNote::create($data);
    }

    public function updateNote(int $id, array $data)
    {
        $note = $this->getNote($id);
        $note->update($data);
        return $note;
    }

    public function deleteNote(int $id)
    {
        $note = $this->getNote($id);
        return $note->delete();
    }
}
