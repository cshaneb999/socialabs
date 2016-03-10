<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SociaLabs {

    /**
     * Class to create Cron entity
     */
    class Cron extends Entity {

        /**
         * Creates Cron entity
         */
        public function __construct() {
            $this->type = "Cron";
            $this->access_id = "system";
        }

        /**
         * Runs cron
         * 
         * @param string $interval  Name of cron to run
         * @param boolean $ignore_last_run  Whether or not to ignore last time cron was ran
         */
        static function run($interval, $ignore_last_run = true) {
            $phpbin = PHP_BINDIR . "/php";
            if ($ignore_last_run) {
                shell_exec('echo $phpbin -q ' . SITEPATH . 'cron/' . $interval . '.php | at now');

                return;
            }
            $entity = getEntity(array(
                "type" => "Cron",
                "metadata_name" => "interval",
                "metadata_value" => $interval
            ));
            if (!$entity) {
                $entity = new Cron;
                $entity->interval = $interval;
                $entity->save();
            }
            $last_ran = $entity->last_ran;
            if (!$last_ran) {
                $last_ran = 0;
            }
            switch ($interval) {
                case "minute":
                    $seconds = 60;
                    break;
                case "five":
                    $seconds = 60 * 5;
                    break;
                case "fifteen":
                    $seconds = 60 * 15;
                    break;
                case "hour":
                    $seconds = 60 * 60;
                    break;
                case "week":
                default:
                    $seconds = 60 * 60 * 24 * 7;
                    break;
            }
            $time = time();
            if ($time > $last_ran + $seconds) {
                $shell = $phpbin . ' -q ' . SITEPATH . 'cron/' . $interval . '.php > /dev/null 2>/dev/null &';
                shell_exec($shell);
                $last_ran = $time;
                $entity = getEntity(array(
                    "type" => "Cron",
                    "metadata_name" => "interval",
                    "metadata_value" => $interval
                ));
                if ($entity) {
                    $entity->last_ran = $last_ran;
                    $entity->save();
                }
            }
            return;
        }

    }

}