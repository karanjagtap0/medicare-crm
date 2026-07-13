<?php

namespace App\Interfaces\User;

interface UserRepositoryInterface
{
    public function UserLists(array $filters);

    public function Users(array $data);

    public function GetUser(int $id);

    public function UpdateUser(int $id, array $data);

    public function DeleteUser(int $id);

}
