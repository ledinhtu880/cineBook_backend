@extends('layouts.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Danh Sách Ghế Theo Rạp</h2>

        @foreach($cinemas as $cinema)
            <div class="card mb-4">
                <div class="card-header">
                    <h4>{{ $cinema->name }}</h4>
                </div>
                <div class="card-body">
                    @foreach($cinema->rooms as $room)
                            <div class="mb-3">
                                <h5>{{ $room->name }} ({{ $room->seat_rows }} x {{ $room->seat_columns }})</h5>
                                <div class="seat-layout">
                                    @php
                                        $seats = $room->seats->groupBy(function ($seat) {
                                            return substr($seat->seat_code, 0, 1); // Lấy hàng (A, B, C...)
                                        });
                                    @endphp
                                    @foreach($seats as $row => $rowSeats)
                                        <div class="seat-row">
                                            @foreach($rowSeats as $seat)
                                                <span class="seat {{ $seat->seat_type }}">
                                                    {{ $seat->seat_code }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .seat-layout {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .seat-row {
            display: flex;
            gap: 5px;
        }

        .seat {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
        }

        .normal {
            background-color: #ccc;
        }

        .vip {
            background-color: #ff9800;
            color: white;
        }

        .sweetbox {
            background-color: #e91e63;
            color: white;
        }
    </style>
@endsection