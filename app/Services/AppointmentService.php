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
     * @param bool $excludePastSlots Exclude past time slots for the current day.
     * @return array The array of time slots.
     */
    // public function generateTimeSlots(string $startTime, string $endTime, int $slotDuration = 15, bool $excludePastSlots = false): array
    // {
    //     $slots = [];
    //     $current = Carbon::parse($startTime);
    //     $end = Carbon::parse($endTime);

    //     // Get the current time
    //     $now = Carbon::now();

    //     while ($current->lessThan($end)) {
    //         // Skip past slots if $excludePastSlots is true
    //         if (!$excludePastSlots || $current->greaterThanOrEqualTo($now)) {
    //             $slots[] = $current->format('H:i');
    //         }
    //         $current->addMinutes($slotDuration);
    //     }

    //     return $slots;
    // }



    public function generateTimeSlots(string $startTime, string $endTime, int $slotDuration = 15, bool $excludePastSlots = false, string $appointmentDate = null): array
    {
        $slots = [];
        $current = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        // Get the current time
        $now = Carbon::now();

        // If appointment date is provided and it's today, exclude past slots
        $isToday = $appointmentDate && Carbon::parse($appointmentDate)->isToday();

        while ($current->lessThan($end)) {
            // Skip past slots if $excludePastSlots is true
            if ($isToday) {
                if ($current->greaterThanOrEqualTo($now)) {
                    $slots[] = $current->format('H:i');
                }
            } else {
                $slots[] = $current->format('H:i');
            }

            $current->addMinutes($slotDuration);
        }

        return $slots;
    }

}
