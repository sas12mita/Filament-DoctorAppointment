<?php

namespace App\Services;

use Carbon\Carbon;

class AppointmentService
{
    /**
     * Generate time slots between start and end times.
     *
     * @param string $startTime The start time (e.g., '09:00').
     * @param string $endTime The end time (e.g., '17:00').
     * @param int $slotDuration Slot duration in minutes.
     * @return array The array of time slots.
     */
    public function generateTimeSlots(string $startTime, string $endTime, int $slotDuration = 15): array
    {
        $slots = [];
        $current = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        while ($current->lessThan($end)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($slotDuration);
        }

        return $slots;
    }
}