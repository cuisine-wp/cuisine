<?php


namespace Cuisine\Cron;

class Queue{


    /**
     * Returns all possible intervals
     *
     * @return array
     */
    public static function getIntervals()
    {

        $quarter = 60 * 15;
        $hour = 60 * 60;
        $halfHour = $hour / 2;
        $day = $hour * 24;
        $week = $day * 7;

        return array(

            'perMinute'     => [ 'interval' => 60, 'display' => __( 'Per Minute', 'cuisine' ) ],
            'perQuarter'    => [ 'interval' => $quarter, 'display' => __( 'Per fifteen minutes', 'cuisine' ) ],
            'perHour'       => [ 'interval' => $hour, 'display' => __( 'Per Hour', 'cuisine' ) ],
            'perHalfHour'   => [ 'interval' => $halfHour, 'display' => __( 'Per Half Hour', 'cuisine' ) ],
            'perDay'        => [ 'interval' => $day, 'display' => __( 'Per Day', 'cuisine' ) ],
            'perWeek'       => [ 'interval' => $week, 'display' => __( 'Per Week', 'cuisine' ) ]
        );
    }

}