<?php

    namespace Cuisine\Cron;

    class Job{

        /**
         * String id for this job
         *
         * @var string
         */
        public $id = '';


        /**
         * Frequency on when this job is supposed to happen
         *
         * @var int
         */
        public $frequency = null;


        /**
         * Callback function
         *
         * @var function / string
         */
        private $callback = null;


        /**
         * Start timestamp
         *
         * @var int
         */
        private $start;



        /**
         * Make a job
         *
         * @return Cuisine\Cron\Job
         */
        public function make( $frequency = null, $callback = null )
        {
            if( $frequency !== null )
                $this->frequency = $frequency;

            if( $callback !== null )
                $this->callback = $callback;


            $this->start = time();
            $this->id = md5( $this->start.'-'.$this->frequency );

            return $this;
        }


        /**
         * Definitely set this job
         *
         * @return bool
         */
        public function set()
        {

            if( $this->frequency !== null && $this->callback !== null ){

                wp_schedule_event(
                        $this->start,
                        $this->frequency,
                        $this->callback
                );

                return true;
            }

            return false;
        }


        /**
         * Delete
         *
         * @param  [type] $id [description]
         * @return [type]     [description]
         */
        public function delete( $id )
        {

        }


        /*=============================================================*/
        /**             Wrapper functions                              */
        /*=============================================================*/

        /**
         * Returns a job for each minute
         *
         * @param  function $callback
         * @return Cuisine\Cron\Job
         */
        public function eachMinute( $callback )
        {
            $this->make( 'perMinute', $callback )->set();
        }


        /**
         * Returns a job for each fifteen minutes
         *
         * @param  function $callback
         * @return Cuisine\Cron\Job
         */
        public function eachFifteenMinutes( $callback )
        {
            $this->make( 'perQuarter', $callback )->set();
        }

        /**
         * Returns a job for each hour
         *
         * @param function $callback
         * @return Cuisine\Cron\Job
         */
        public function eachHour( $callback )
        {
            $this->make( 'perHour', $callback )->set();
        }

        /**
         * Returns a job for each half hour
         *
         * @param function $callback
         * @return Cuisine\Cron\Job
         */
        public function eachHalfHour( $callback )
        {
            $this->make( 'perHalfHour', $callback )->set();
        }


        /**
         * Returns a job for each day
         *
         * @param function $callback
         * @return Cuisine\Cron\Job
         */
        public function eachDay()
        {
            $this->make( 'perDay', $callback )->set();
        }


        /**
         * Returns a job for each week
         *
         * @param function $callback
         * @return Cuisine\Cron\Job
         */
        public function eachWeek()
        {
            $this->make( 'perWeek', $callback )->set();
        }

    }
