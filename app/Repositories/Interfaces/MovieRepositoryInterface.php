<?php

namespace App\Repositories\Interfaces;

interface MovieRepositoryInterface
{
    public function getAllPaginated($perPage = 6, $search = null);

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getAllForAdmin($perPage = 10);
}
