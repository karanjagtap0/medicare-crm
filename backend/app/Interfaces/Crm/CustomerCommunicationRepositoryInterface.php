<?php

namespace App\Interfaces\Crm;

interface CustomerCommunicationRepositoryInterface
{
    public function getCommunications(array $filters);
    public function getCommunication(int $id);
    public function createCommunication(array $data);
}
