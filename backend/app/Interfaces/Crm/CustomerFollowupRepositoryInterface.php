<?php

namespace App\Interfaces\Crm;

interface CustomerFollowupRepositoryInterface
{
    public function getFollowups(array $filters);
    public function getFollowup(int $id);
    public function createFollowup(array $data);
    public function updateFollowup(int $id, array $data);
    public function deleteFollowup(int $id);
}
