<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
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

    public function getByMovie($movieId)
    {
        $startOfWeek = Carbon::parse(now());
        $endOfWeek = Carbon::parse(now())->addDays(7)->endOfDay();
        return $this->model->whereHas('movie', function ($query) use ($movieId) {
            $query->where('movie_id', $movieId);
        })
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->with(['movie:id,title', 'room:id,name', 'room.seats', 'cinema:id,name'])
            ->orderBy('start_time', 'asc')
            ->get()->map(function ($showtime) {
                $date = Carbon::parse($showtime->start_time);
                $dayType = $this->getDayType($date);
                $prices = $this->getPricesForShowtime($showtime->cinema->id, $dayType);

                $bookedSeatIds = DB::table('booking_details as bd')
                    ->join('bookings as b', 'bd.booking_id', '=', 'b.id')
                    ->where('b.showtime_id', $showtime->id)
                    ->pluck('bd.seat_id')
                    ->toArray();

                return [
                    'id' => $showtime->id,
                    'start_time' => $showtime->start_time,
                    'end_time' => $showtime->end_time,
                    'start_time_formatted' => $showtime->start_time_formatted,
                    'end_time_formatted' => $showtime->end_time_formatted,
                    'date' => $showtime->date,
                    'room' => [
                        'name' => $showtime->room->name,
                        'seats' => $showtime->room->seats->map(function ($seat) use ($prices, $bookedSeatIds) {
                            return [
                                'id' => $seat->id,
                                'seat_code' => $seat->seat_code,
                                'seat_type' => $seat->seat_type,
                                'is_sweetbox' => $seat->is_sweetbox,
                                'price' => $prices[$seat->seat_type] ?? 0,
                                'status' => in_array($seat->id, $bookedSeatIds) ? 'booked' : 'available'
                            ];
                        }),
                    ],
                    'cinema' => [
                        'id' => $showtime->cinema->id,
                        'name' => $showtime->cinema->name
                    ],
                    'movie' => [
                        'id' => $showtime->movie->id,
                        'title' => $showtime->movie->title,
                    ]
                ];
            });
    }
    public function getByCinema($cinemaId)
    {
        $startOfWeek = Carbon::parse(now());
        $endOfWeek = Carbon::parse(now())->addDays(7)->endOfDay();

        return $this->model->whereHas('room', function ($query) use ($cinemaId) {
            $query->where('cinema_id', $cinemaId);
        })
            ->whereBetween('start_time', [$startOfWeek, $endOfWeek])
            ->with(['room.seats', 'movie'])
            ->orderBy('start_time', 'asc')
            ->get();
    }
    public function getGroupedByCinema($cinemaId)
    {
        $showtimes = $this->getByCinema($cinemaId);
        $groupedShowtimes = $showtimes->groupBy('movie_id');

        $result = [];
        foreach ($groupedShowtimes as $movieShowtimes) {
            $movieData = (new MovieResource($movieShowtimes[0]->movie))->toArray(request());

            $movieData['showtimes'] = $movieShowtimes->map(function ($showtime) use ($cinemaId) {
                $date = Carbon::parse($showtime->start_time);
                $dayType = $this->getDayType($date);
                $prices = $this->getPricesForShowtime($cinemaId, $dayType);

                $bookedSeatIds = DB::table('booking_details as bd')
                    ->join('bookings as b', 'bd.booking_id', '=', 'b.id')
                    ->where('b.showtime_id', $showtime->id)
                    ->pluck('bd.seat_id')
                    ->toArray();

                return [
                    'id' => $showtime->id,
                    'start_time' => $showtime->start_time,
                    'end_time' => $showtime->end_time,
                    'start_time_formatted' => $showtime->start_time_formatted,
                    'end_time_formatted' => $showtime->end_time_formatted,
                    'date' => $showtime->date,
                    'room' => [
                        'name' => $showtime->room->name,
                        'seats' => $showtime->room->seats->map(function ($seat) use ($prices, $bookedSeatIds) {
                            return [
                                'id' => $seat->id,
                                'seat_code' => $seat->seat_code,
                                'seat_type' => $seat->seat_type,
                                'is_sweetbox' => $seat->is_sweetbox,
                                'price' => $prices[$seat->seat_type] ?? 0,
                                'status' => in_array($seat->id, $bookedSeatIds) ? 'booked' : 'available'
                            ];
                        }),
                    ],
                ];
            })->values()->toArray();

            $result[] = $movieData;
        }

        return $result;
    }
    /**
     * Determine if a date is a weekday, weekend or holiday
     * 
     * @param Carbon $date
     * @return string
     */
    private function getDayType(Carbon $date)
    {
        // Check if it's a holiday (you would need a holiday table or API for this)
        // This is a placeholder - implement your holiday check logic
        if ($this->isHoliday($date)) {
            return 'holiday';
        }

        // Check if it's a weekend (Saturday or Sunday)
        if ($date->isWeekend()) {
            return 'weekend';
        }

        // Otherwise it's a weekday
        return 'weekday';
    }

    /**
     * Check if a date is a holiday
     * 
     * @param Carbon $date
     * @return bool
     */
    private function isHoliday(Carbon $date)
    {
        // Implement your holiday check logic here
        // This could query a holidays table or use an API
        // For now, we'll return false as a placeholder
        return false;
    }

    /**
     * Get prices for all seat types for a specific cinema and day type
     * 
     * @param int $cinemaId
     * @param string $dayType
     * @return array
     */
    private function getPricesForShowtime($cinemaId, $dayType)
    {
        $prices = DB::table('seat_prices')
            ->where('cinema_id', $cinemaId)
            ->where('day_type', $dayType)
            ->get()
            ->pluck('price', 'seat_type')
            ->toArray();

        return $prices;
    }
}
