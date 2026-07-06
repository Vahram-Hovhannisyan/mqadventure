<?php

return [
    // Working hours used to generate bookable time slots on the front-end.
    'open_time'  => env('BOOKING_OPEN_TIME', '08:00'),
    'close_time' => env('BOOKING_CLOSE_TIME', '20:00'),

    // Step between selectable start times, in minutes.
    'slot_interval' => (int) env('BOOKING_SLOT_INTERVAL', 30),
];
