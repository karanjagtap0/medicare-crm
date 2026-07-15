<?php

namespace App\Interfaces\Crm;

interface SupportTicketRepositoryInterface
{
    public function getTickets(array $filters);
    public function getTicket(int $id);
    public function createTicket(array $data);
    public function updateTicketStatus(int $id, string $status);
    public function addReply(int $ticketId, array $data);
}
