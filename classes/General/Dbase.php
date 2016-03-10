<?php

/**
 * Class used to create and manipulate database elements
 *
 * PHP version 5
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 SociaLabs
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 *
 * @author     Shane Barron <admin@socialabs.co>
 * @author     Aaustin Barron <aaustin@socialabs.co>
 * @copyright  2015 SociaLabs
 * @license    http://opensource.org/licenses/MIT MIT
 * @version    1
 * @link       http://socialabs.co
 */

namespace SociaLabs;

/**
 * Class to handle all database interactions
 */
class Dbase {

    /**
     * Connects to database
     * 
     * @return \mysqli   Mysqli database connection object
     */
    static function con() {
        global $con;
        if (!$con) {
            $con = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        }
        return $con;
    }

    /**
     * Runs a mysqli query
     *
     * @param string $query Query to run
     * @return boolean next entity guid
     */
    static function query($query) {
        new Debug("dbase_query", $query);
        $con = self::con();
        if ($con->query($query)) {
            return $con->insert_id;
        }
    }

    /**
     * Gets results from mysqli query
     *
     * @global connection $con Mysqli connection
     * @param string $query Mysqli query to run
     * @return mysqli_results Results of query
     */
    static function getResults($query) {
        $results = self::con()->query($query);
        return $results;
    }

    /**
     * Gets results array from mysqli query
     *
     * @param string $query Mysqli query to run
     * @return array Results array
     */
    static function getResultsArray($query) {
        $results_array = array();
        $results = self::getResults($query);
        if ($results) {
            if (function_exists("mysqli_fetch_all") && defined("MYSQL_ASSOC")) {
                $results_array = mysqli_fetch_all($results, MYSQL_ASSOC);
            } else {
                while ($row = $results->fetch_assoc()) {
                    $results_array[] = $row;
                }
            }
        }
        return $results_array;
    }

    /**
     * Creates a mysql table
     *
     * @param string $table Name of table to create
     * @return boolean true
     */
    static function createTable($table) {
        $tables = self::getAllTables();
        if ($table != "systemvariable") {
            $table = strtolower($table);
            if (!in_array($table, $tables)) {
                $query = "CREATE TABLE IF NOT EXISTS `$table` (`guid` INT(12) UNSIGNED PRIMARY KEY,`last_updated` VARCHAR(20)) ENGINE = MyISAM;";
                Dbase::query($query);
                $tables[] = $table;
                if (class_exists("SociaLabs\\Cache")) {
                    new Cache("tables", $tables, "site");
                }
            }
        }
        return true;
    }

    /**
     * Creates default system tables
     */
    static function createDefaultTables() {
        $tables = Cache::get("tables", "site");
        if (!$tables) {
            $tables = array();
        }
        if (!is_array($tables)) {
            $tables = array();
        }
        if (!in_array("entities", $tables)) {
            $query = "CREATE TABLE IF NOT EXISTS `entities` (guid INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,type VARCHAR(20), last_updated VARCHAR(50)) ENGINE = MyISAM;";
            Dbase::query($query);
            $tables[] = "entities";
        }
        if (!in_array("systemvariable", $tables)) {
            $query = "CREATE TABLE IF NOT EXISTS `systemvariable` (guid INT(6) PRIMARY KEY,last_updated VARCHAR(50),name text,value text,access_id VARCHAR(50),time_created VARCHAR(50),owner_guid varchar(1)) ENGINE = MyISAM;";
            Dbase::query($query);
            $tables[] = "systemvariable";
        }
        new Cache("tables", $tables, "site");
    }

    /**
     * Adds column to table
     *
     * @param string $column Name of column to add
     * @param string $type
     * @return boolean true
     */
    static function addColumn($column, $type) {
        $type = strtolower($type);
        $table_columns = Cache::get("table_columns_$type", "site");
        if (!is_array($table_columns)) {
            $table_columns = array();
        }

        if (!in_array($column, $table_columns)) {
            $query = "ALTER TABLE `{$type}` ADD `{$column}` VARCHAR(50);";
            Dbase::query($query);
            $table_columns[] = $column;
            new Cache("table_columns_$type", $table_columns, "site");
        }

        return true;
    }

    /**
     * Returns array of all database tables
     * 
     * @param boolean $cache  Whether or not to ignore the cached values
     * @return array  Array of tables
     */
    static function getAllTables($cache = true) {
        if ($cache) {
            if (class_exists("SociaLabs\\Cache")) {
                $tables = Cache::get("tables", "site");
                if ($tables) {
                    return $tables;
                }
            }
        }
        $query = "show tables";
        $result = self::getResultsArray($query);
        $table_array = array();
        foreach ($result as $table) {
            foreach ($table as $key => $name) {
                $table_array[] = $name;
            }
        }
        return $table_array;
    }

}
