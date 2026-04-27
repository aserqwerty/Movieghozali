<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Interfaces\MovieRepositoryInterface;

class MovieService
{
    protected $movieRepository;
    protected $categoryRepository;

    public function __construct(MovieRepositoryInterface $movieRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getMoviesForHomepage($search = null)
    {
        return $this->movieRepository->getAllPaginated(6, $search);
    }

    public function getMovieById($id)
    {
        return $this->movieRepository->findById($id);
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }

    public function storeMovie(array $data, $file)
    {
        $randomName    = Str::uuid()->toString();
        $fileExtension = $file->getClientOriginalExtension() ?: 'jpg';
        $fileName      = $randomName . '.' . $fileExtension;

        $file->move(public_path('images'), $fileName);

        return $this->movieRepository->create([
            'id'          => $data['id'],
            'judul'       => $data['judul'],
            'category_id' => $data['category_id'],
            'sinopsis'    => $data['sinopsis'],
            'tahun'       => $data['tahun'],
            'pemain'      => $data['pemain'],
            'foto_sampul' => $fileName,
        ]);
    }

    public function getMoviesForAdmin()
    {
        return $this->movieRepository->getAllForAdmin(10);
    }

    public function updateMovie($id, array $data, $file = null)
    {
        $updateData = [
            'judul'       => $data['judul'],
            'sinopsis'    => $data['sinopsis'],
            'category_id' => $data['category_id'],
            'tahun'       => $data['tahun'],
            'pemain'      => $data['pemain'],
        ];

        if ($file) {
            $randomName    = Str::uuid()->toString();
            $fileExtension = $file->getClientOriginalExtension() ?: 'jpg';
            $fileName      = $randomName . '.' . $fileExtension;

            $file->move(public_path('images'), $fileName);

            // Hapus foto lama
            $movie = $this->movieRepository->findById($id);
            if (File::exists(public_path('images/' . $movie->foto_sampul))) {
                File::delete(public_path('images/' . $movie->foto_sampul));
            }

            $updateData['foto_sampul'] = $fileName;
        }

        return $this->movieRepository->update($id, $updateData);
    }

    public function deleteMovie($id)
    {
        $movie = $this->movieRepository->findById($id);

        if (File::exists(public_path('images/' . $movie->foto_sampul))) {
            File::delete(public_path('images/' . $movie->foto_sampul));
        }

        return $this->movieRepository->delete($id);
    }
}
