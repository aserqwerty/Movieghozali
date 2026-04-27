<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Interfaces\MovieRepositoryInterface;

class MovieRepository implements MovieRepositoryInterface
{
    public function getAllPaginated($perPage = 6, $search = null)
    {
        $query = Movie::latest();

        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('sinopsis', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById($id)
    {
        return Movie::findOrFail($id);
    }

    public function create(array $data)
    {
        return Movie::create($data);
    }

    public function update($id, array $data)
    {
        $movie = Movie::findOrFail($id);
        $movie->update($data);
        return $movie;
    }

    public function delete($id)
    {
        $movie = Movie::findOrFail($id);
        return $movie->delete();
    }

    public function getAllForAdmin($perPage = 10)
    {
        return Movie::latest()->paginate($perPage);
    }
}
