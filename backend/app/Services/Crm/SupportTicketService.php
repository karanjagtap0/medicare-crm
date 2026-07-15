<?php

namespace App\Services\Crm;

use App\Interfaces\Crm\SupportTicketRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SupportTicketService
{
    public function __construct(private SupportTicketRepositoryInterface $supportTicketRepository)
    {
    }

    public function getTickets(array $filters)
    {
        return $this->supportTicketRepository->getTickets($filters);
    }

    public function getTicket(int $id)
    {
        return $this->supportTicketRepository->getTicket($id);
    }

    public function createTicket(array $data)
    {
        return $this->supportTicketRepository->createTicket($data);
    }

    public function updateTicketStatus(int $id, string $status)
    {
        return $this->supportTicketRepository->updateTicketStatus($id, $status);
    }

    public function addReply(int $ticketId, array $data)
    {
        $data['user_id'] = Auth::id();
        return $this->supportTicketRepository->addReply($ticketId, $data);
    }
}
