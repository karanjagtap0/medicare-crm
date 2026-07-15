<?php

namespace App\Repositories\Crm;

use App\Interfaces\Crm\SupportTicketRepositoryInterface;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;

class SupportTicketRepository implements SupportTicketRepositoryInterface
{
    public function getTickets(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        $query = SupportTicket::with(['customer', 'assignedTo']);
        
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getTicket(int $id)
    {
        return SupportTicket::with(['customer', 'assignedTo', 'replies.user'])->findOrFail($id);
    }

    public function createTicket(array $data)
    {
        // Generate a random ticket number (e.g. TKT-12345678)
        $data['ticket_number'] = 'TKT-' . strtoupper(uniqid());
        
        return SupportTicket::create($data);
    }

    public function updateTicketStatus(int $id, string $status)
    {
        $ticket = $this->getTicket($id);
        $ticket->update(['status' => $status]);
        return $ticket;
    }

    public function addReply(int $ticketId, array $data)
    {
        $data['support_ticket_id'] = $ticketId;
        return SupportTicketReply::create($data);
    }
}
