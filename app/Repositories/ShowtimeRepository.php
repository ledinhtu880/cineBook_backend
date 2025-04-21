<?php

namespace App\Repositories;

use App\Models\Showtime;
use App\Http\Resources\MovieResource;
use Carbon\Carbon;

class ShowtimeRepository
{
    protected $model;

    public function __construct(Showtime $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $showtime = $this->model->findOrFail($id);
        $showtime->update($data);

        return $showtime;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function getByCinema($cinemaId)
    {
        $startOfWeek = Carbon::parse(now());
        $endOfWeek = Carbon::parse(now())->addDays(7)->endOfDay();

        return $this->model->whereHas('room', function ($query) use ($cinemaId) {
            $query->where('cinema_id', $cinemaId);
        })
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->with(relations: ['room'])
            ->orderBy('start_time', 'asc')
            ->get();
    }
    public function getGroupedByCinema($cinemaId)
    {
        $showtimes = $this->getByCinema($cinemaId);

        $groupedShowtimes = $showtimes->groupBy('movie_id');

        $result = [];
        foreach ($groupedShowtimes as $movieId => $movieShowtimes) {
            $movieData = (new MovieResource($movieShowtimes[0]->movie))->toArray(request());

            $movieData['showtimes'] = $movieShowtimes->map(function ($showtime) {
                $date = Carbon::parse($showtime->start_time)->format('Y-m-d');
                $showtime->start_time = Carbon::parse($showtime->start_time)->format('H:i');
                $showtime->end_time = Carbon::parse($showtime->end_time)->format('H:i');

                return [
                    'id' => $showtime->id,
                    'start_time' => $showtime->start_time,
                    'end_time' => $showtime->end_time,
                    'date' => $date,
                    'room' => [
                        'name' => $showtime->room->name,
                        'seats' => $showtime->room->seats
                    ],
                    'price' => $showtime->price,
                ];
            })->values()->toArray();


            $result[] = $movieData;
        }

        return $result;
    }
}
