<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MovieService
{
    public function getMoviesForHomepage($search = null)
    {
        $query = Movie::latest();
        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('sinopsis', 'like', '%' . $search . '%');
        }
        return $query->paginate(6)->withQueryString();
    }

    public function getMovieById($id)
    {
        return Movie::findOrFail($id);
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    public function storeMovie(array $data, $file)
    {
        $randomName = Str::uuid()->toString();
        $fileExtension = $file->getClientOriginalExtension() ?: 'jpg';
        $fileName = $randomName . '.' . $fileExtension;

        $file->move(public_path('images'), $fileName);

        return Movie::create([
            'id' => $data['id'],
            'judul' => $data['judul'],
            'category_id' => $data['category_id'],
            'sinopsis' => $data['sinopsis'],
            'tahun' => $data['tahun'],
            'pemain' => $data['pemain'],
            'foto_sampul' => $fileName,
        ]);
    }

    public function getMoviesForAdmin()
    {
        return Movie::latest()->paginate(10);
    }

    public function updateMovie($id, array $data, $file = null)
    {
        $movie = Movie::findOrFail($id);

        $updateData = [
            'judul' => $data['judul'],
            'sinopsis' => $data['sinopsis'],
            'category_id' => $data['category_id'],
            'tahun' => $data['tahun'],
            'pemain' => $data['pemain'],
        ];

        if ($file) {
            $randomName = Str::uuid()->toString();
            $fileExtension = $file->getClientOriginalExtension() ?: 'jpg';
            $fileName = $randomName . '.' . $fileExtension;

            $file->move(public_path('images'), $fileName);

            if (File::exists(public_path('images/' . $movie->foto_sampul))) {
                File::delete(public_path('images/' . $movie->foto_sampul));
            }

            $updateData['foto_sampul'] = $fileName;
        }

        $movie->update($updateData);
        return $movie;
    }

    public function deleteMovie($id)
    {
        $movie = Movie::findOrFail($id);

        if (File::exists(public_path('images/' . $movie->foto_sampul))) {
            File::delete(public_path('images/' . $movie->foto_sampul));
        }

        return $movie->delete();
    }
}
