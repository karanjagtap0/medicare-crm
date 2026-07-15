<?php

namespace App\Services\Crm;

use App\Interfaces\Crm\CustomerNoteRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CustomerNoteService
{
    public function __construct(private CustomerNoteRepositoryInterface $customerNoteRepository)
    {
    }

    public function getCustomerNotes(int $customerId, array $filters)
    {
        return $this->customerNoteRepository->getCustomerNotes($customerId, $filters);
    }

    public function getNote(int $id)
    {
        return $this->customerNoteRepository->getNote($id);
    }

    public function createNote(array $data)
    {
        $data['user_id'] = Auth::id(); // Assign the current user as the author of the note
        return $this->customerNoteRepository->createNote($data);
    }

    public function updateNote(int $id, array $data)
    {
        return $this->customerNoteRepository->updateNote($id, $data);
    }

    public function deleteNote(int $id)
    {
        return $this->customerNoteRepository->deleteNote($id);
    }
}
