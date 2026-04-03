<?php

namespace App\Repositories;

use App\Models\Trip;

class TripRepository 
{
    public function all($perpage)
    {
        return Trip::with(['user'])->latest()->paginate($perpage);
    }

    public function find($id)
    {
        return Trip::findOrFail($id);
    }

    public function create(array $data)
    {
        return Trip::create($data);
    }

    public function update($id, array $data)
    {
        $trip = $this->find($id);
        $trip->update($data);
        return $trip;
    }

    public function delete($id)
    {
        return Trip::destroy($id);
    }
}
