<?php

/**
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
 * Main class to create and save entities
 */
class Entity {

    /**
     * Constructor populates metadata if passed a guid
     * 
     * @param int $guid Guid of entity
     */
    public function __construct($guid = false) {
        $default_access = Setting::get("default_access");
        if (!$default_access) {
            $default_access = "public";
        }
        if (!$this->access_id) {
            $this->access_id = $default_access;
        }
        if ($guid) {
            $this->populateMetadata($guid);
        }
    }

    public function exists($params) {
        $params['type'] = $this->type;
        $test = getEntity($params);
        if ($test) {
            return true;
        }
        return false;
    }

    /**
     * Saves an entity to the database
     * 
     * @global connection $con
     * @return int|boolean guid of saved entity, or false if save fails
     */
    public function save() {
        Dbase::createDefaultTables();
        if (!$this->owner_guid && loggedIn()) {
            if ($this->type != "systemvariable") {
                $this->owner_guid = getLoggedInUserGuid();
            }
        }
        $fields = array();
        $time = time();
        $ignore = array(
            "type",
            "guid",
            "default_icon"
        );
        if (!$this->access_id) {
            $default_access = Setting::get("default_access");
            if (!$default_access) {
                $default_access = "public";
            }
            $this->access_id = getInput("access_id") ? getInput("access_id") : $default_access;
        }
        $this->last_updated = $time;
        Dbase::addColumn('last_updated', strtolower($this->type));
        if (!$this->guid) {
            $this->time_created = $time;
            $query = "INSERT INTO `entities` (`type`) VALUES ('" . strtolower($this->type) . "')";
            $guid = Dbase::query($query);
            if ($guid == 0) {
                return false;
            }
            $this->guid = $guid;
        }

        Dbase::createTable(strtolower($this->type));
        $vars = get_object_vars($this);

        $query = "SELECT * FROM `" . strtolower($this->type) . "` WHERE `guid` = '$this->guid' LIMIT 1";
        $results = Dbase::getResults($query);
        if (!$results || ($results->num_rows == 0)) {
            $query = "INSERT INTO `" . strtolower($this->type) . "` (`guid`) VALUES ('$this->guid')";
            Dbase::query($query);
        }
        $query = "UPDATE `" . strtolower($this->type) . "` SET ";
        $columns = Dbase::getResultsArray("SHOW columns FROM `" . strtolower($this->type) . "`");
        foreach ($columns as $column) {
            $fields[] = $column['Field'];
        }
        foreach ($vars as $key => $value) {
            if (!in_array($key, $ignore)) {

                if (is_array($value) || is_object($value) || is_bool($value)) {
                    $value = Dbase::con()->real_escape_string(serialize($value));
                } else {
                    $value = nl2br($value);
                    $value = Dbase::con()->real_escape_string($value);
                }
                if (!in_array($key, $fields)) {
                    Dbase::addColumn($key, strtolower($this->type));
                }
                $query .= "`$key`='$value',";
            }
        }
        $query = rtrim($query, ",");
        $query .= " WHERE `guid` = '$this->guid'";
        Dbase::query($query);
        if ($this->type != "Plugin") {
            new Cache("entity_" . $this->guid, $this, "site");
        }
        return $this->guid;
    }

    /**
     * Deletes an entity from the database
     * 
     * @return boolean  true
     */
    public function delete() {
        $guid = $this->guid;
        $query = "DELETE FROM `" . strtolower($this->type) . "` WHERE `guid`='$this->guid'";
        Dbase::query($query);
        $query = "DELETE FROM `entities` WHERE `id`='$this->guid'";
        Dbase::query($query);
        $tables = Dbase::getAllTables();
        foreach ($tables as $table) {
            $query = "DELETE FROM $table WHERE `owner_guid` = '$this->guid'";
            Dbase::query($query);
            $query = "DELETE FROM $table WHERE `container_guid` = '$this->guid'";
            Dbase::query($query);
        }
        return true;
    }

    /**
     * Returns an icon for an entity
     * 
     * @param inut $thumbnail   Width of icon
     * @param string $class CSS class to wrap icon
     * @return string   Icon
     */
    public function icon($thumbnail = NULL, $class = NULL, $img_tag = true, $style = NULL) {
        $width = ($thumbnail != NULL ? "style='width:{$thumbnail}px'" : "");
        $class = ($class != NULL ? "class='$class'" : "class='img-responsive'");
        $tag = !$img_tag ? "" : "<img alt='$this->title' src='";
        if ($style) {
            $style = " style='$style' ";
        }
        $closing_tag = !$img_tag ? "" : "' $width $class $style/>";
        if ($this->icon) {
            return $tag . Image::getImageURL($this->icon, $thumbnail) . $closing_tag;
        } elseif ($this->default_icon) {
            return $tag . getSiteURL() . $this->default_icon . $closing_tag;
        } else {
            return $tag . getSiteURL() . "assets/img/avatars/icon_missing.png" . $closing_tag;
        }
    }

    /**
     * Getter
     * 
     * @param string $name
     * @return boolean false|mixed
     */
    public function __get($name) {
        if (isset($this->$name)) {
            if (isSerialized($this->name)) {
                return unserialize($this->name);
            } else {
                $value = $this->$name;
                return $value;
            }
        }
        return false;
    }

    /**
     * Gets all metadata for guid, and populates object
     *
     * @param int $guid Guid of object
     */
    public function populateMetadata($guid) {
        $this->guid = $guid;
        $query = "SELECT * FROM `entities` WHERE `guid` = '$guid' LIMIT 1";
        $results_array = Dbase::getResultsArray($query);
        $this->type = ucfirst($results_array[0]['type']);
        $type = strtolower($this->type);
        $query = "SELECT * FROM `$type` WHERE `guid`='$this->guid' LIMIT 1";
        $results = Dbase::getResultsArray($query);
        if (!empty($results)) {
            foreach ($results[0] as $key => $value) {
                if ($key != "id" && $key != "guid" && $key != "type") {
                    if (isSerialized(stripslashes($value))) {
                        $value = unserialize(stripslashes($value));
                    }
                    if (!is_array($value)) {
                        $this->$key = nl2br(stripslashes($value));
                    } else {
                        $this->$key = $value;
                    }
                }
            }
        }
    }

    /**
     * Used to view entity
     * 
     * @return string HTML display of entity
     */
    public function view() {
        return display("entity/" . $this->type, array(
            "guid" => $this->guid,
            "view_type" => "entity"
        ));
    }

    /**
     * Function to get entities
     * 
     * @param array $params Array of parameters to get objects by, including:
     *                      "limit" Limit of items to return
     *                      "offset" Offset of items to return
     *                      "type" Type of items to get
     *                      "metadata_name" Used when getting based on metadata, but only one metadata name=>value is required.
     *                      "metadata_value" Used with metadata_name
     *                      "operand" Operand to use with metadata_name and metadata_value, defaults to =
     *                      "metadata_name_value_pairs" array of "name","value" pairs.  metadata_name_value pairs cannot be used with metadata_name, metadata_value
     *                      "metadata_name_value_pairs_operand" operand to use between metadata_name_value_pairs (defaults to AND)
     *                      "count" if set to true, function returns count of objects as opposed to array of objects or guids
     * @param boolean $return_object Whether to return objects or guids as array (true to return objects, false to return guids)
     * @return mixed array Array of objects, or boolean false if no objects are found
     */
    static function getEntities($params, $return_object = true) {
        if (class_exists("SociaLabs\Debug")) {
            new Debug("getEntities", $params);
        }
        if (!isset($params['type'])) {
            return false;
        }

        // Declare Variables
        $results_array = array();
        $return = array();
        $reverse = NULL;
        $args = NULL;

        // Declare Defaults
        $defaults = array(
            "limit" => false,
            "offset" => 0,
            "order_by" => false,
            "order_reverse" => false,
            "ignore_access" => false
        );


        $params = array_merge($defaults, $params);

        if ($params['order_by']) {
            if ($params['order_reverse']) {
                $reverse = " DESC";
            } else {
                $reverse = " ASC";
            }
        }

        $count = (isset($params['count']) ? $params['count'] : false);
        if ($count) {
            $select = "count(*)";
        } else {
            $select = "*";
        }

        $type = strtolower($params['type']);

        $query = "SELECT $select FROM `$type`";

        if (isset($params['metadata_name']) && (isset($params['metadata_value']))) {
            if (is_array($params['metadata_value']) || is_bool($params['metadata_value']) || is_object($params['metadata_value'])) {
                $params['metadata_value'] = serialize($params['metadata_value']);
            }
            $operand = (isset($params['operand']) ? $params['operand'] : "=");
            if ($params['metadata_value'] == 'NULL' || $operand == "IN" || $operand == "NOT IN") {
                $query .= " WHERE `{$params['metadata_name']}` $operand {$params['metadata_value']}";
            } else {
                $query .= " WHERE `{$params['metadata_name']}` $operand '{$params['metadata_value']}'";
            }
        }
        $where = (strpos($query, "WHERE") > 0 ? " AND " : " WHERE ");
        if (isset($params['metadata_name_value_pairs'])) {
            $query .= $where . "(";
            $metadata_name_value_pairs_operand = (isset($params['metadata_name_value_pairs_operand']) ? $params['metadata_name_value_pairs_operand'] : "AND");
            foreach ($params['metadata_name_value_pairs'] as $pair) {
                $operand = (isset($pair['operand']) ? $pair['operand'] : "=");
                if (is_array($pair['value']) || is_bool($pair['value']) || is_object($pair['value'])) {
                    $pair['value'] = serialize($pair['value']);
                }
                if ($pair['value'] != "NULL" && ($operand != "IN" && $operand != "NOT IN")) {
                    $args .= "`{$pair['name']}` $operand '{$pair['value']}' $metadata_name_value_pairs_operand ";
                } else {
                    $args .= "`{$pair['name']}` $operand {$pair['value']} $metadata_name_value_pairs_operand ";
                }
            }
            $query .= substr($args, 0, -(strlen($metadata_name_value_pairs_operand) + 2));
            $query .= ")";
        }


        if ($params['order_by']) {
            $query .= " ORDER BY `{$params['order_by']}` $reverse";
        }

        if ($params['limit']) {
            $query .= " LIMIT {$params['offset']},{$params['limit']}";
        }

        $results = Dbase::getResults($query);
        if ($count && $results) {
            $row = $results->fetch_assoc();
            $num_rows = $row['count(*)'];
            return $num_rows;
        } elseif ($count && !$results) {
            return 0;
        }
        if ($results && $results->num_rows > 0) {
            if (function_exists("mysqli_fetch_all") && defined("MYSQL_ASSOC")) {
                $results_array = mysqli_fetch_all($results, MYSQL_ASSOC);
            } else {
                while ($row = $results->fetch_assoc()) {
                    $results_array[] = $row;
                }
            }
            foreach ($results_array as $result) {
                if ($return_object) {
                    $entity = getEntity($result['guid']);
                    if ($entity) {
                        $return[] = $entity;
                    }
                } else {
                    $return[] = $id_array['guid'];
                }
            }
            return $return;
        }
        return false;
    }

    /**
     * Returns an entity based on the given guid, or first entity based on params array (like get Entities, but returns only the first entity)
     * 
     * @param int $params Guid of the entity to return, or array of params to pass to getEntities
     * @return boolean|classname False if entity doesn't exist|Entity
     */
    static function getEntity($params, $ignore_access = false) {
        if (!is_array($params)) {
            $entity = Cache::get("entity_" . $params, "site");
            if (!$entity) {
                $type = self::getTypeFromGuid($params);
                if ($type) {
                    $type = ucfirst($type);
                    $classname = "SociaLabs\\" . ucfirst($type);
                    $entity = new $classname;
                    $entity->guid = $params;
                    $entity->populateMetadata($params);
                } else {
                    return false;
                }
            }

            if (class_exists("SociaLabs\User")) {
                if (loggedInUserCanViewEntity($entity, $ignore_access)) {
                    return $entity;
                } else {
                    return false;
                }
            } else {
                if (($entity->access_id == "system") || $entity->access_id == "public") {
                    return $entity;
                }
            }
        } else {
            $entities = getEntities($params);
            if (is_array($entities)) {
                if (class_exists("SociaLabs/User")) {
                    if (loggedInUserCanViewEntity($entities[0]) || getIgnoreAccess()) {
                        return $entities[0];
                    } else {
                        return false;
                    }
                } else {
                    if (!empty($entities)) {
                        if (is_object($entities[0])) {
                            if (($entities[0]->access_id == "system") || ($entities[0]->access_id = "public")) {
                                return $entities[0];
                            }
                        }
                    }
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Returns the type of an entity based on it's guid
     * 
     * @param int $guid
     * @return boolean|string false if no type|type
     */
    static function getTypeFromGuid($guid) {
        $query = "SELECT `type` FROM `entities` WHERE `guid` = '$guid'";
        $results = Dbase::getResultsArray($query);
        if (!empty($results)) {
            $result = $results[0];
            return $result['type'];
        } else {
            return false;
        }
    }

    /**
     * Checks if the owner of an element is logged in
     * 
     * @return boolean false if owner is not logged in|boolean true if owner is logged in
     */
    public function ownerIsLoggedIn() {
        $user_guid = getLoggedInUserGuid();
        if ($user_guid == $this->owner_guid) {
            return true;
        }
        return false;
    }

    /**
     * Provides a formatted list of entities based on same parameters as getEntities
     * 
     * @param array $params Array of parameters (see getEntities)
     * @return boolean false|formatted list of entities
     */
    static function listEntities($params) {
        $entities = getEntities($params); // false forces get entites to return guids instead of objects
        if ($entities) {
            $view_type = (isset($params['view_type']) ? $params['view_type'] : "list");
            $wrapper_class = (isset($params['wrapper_class']) ? $params['wrapper_class'] : NULL);
            $item_class = (isset($params['item_class']) ? $params['item_class'] : NULL);
            $link = (isset($params['link']) ? $params['link'] : NULL);
            $size = (isset($params['size']) ? $params['size'] : MEDIUM);
            return viewEntityList($entities, $view_type, $wrapper_class, $item_class, $link, $size);
        }
        return NULL;
    }

    /**
     * Renders a list of entities
     * 
     * @param array $entities Array of entities to view
     * @param string $view_type Type of view (list, gallery, etc.)
     * @param string $wrapper_class Class to wrap around list
     * @param string $item_class Class to warap around each item
     * @param boolean $link If true, elements are shown with links
     * @param string|boolean $size Some views accept a size argument to determine the size of the elements view
     * @return boolean false if no elements|string HTML rendered list of items
     */
    static function viewEntityList($entities, $view_type = "list", $wrapper_class = NULL, $item_class = NULL, $link = true, $size = "medium") {
        $return = NULL;
        if (!empty($entities)) {
            if ($wrapper_class) {
                $return .= "<div class='$wrapper_class '>";
            }
            foreach ($entities as $entity) {
                if (is_numeric($entity)) {
                    $entity = getEntity($entity);
                }
                if ($entity) {
                    $return .= display("entity/" . $entity->type, array(
                        "guid" => $entity->guid,
                        "view_type" => $view_type,
                        "item_class" => $item_class,
                        "link" => $link,
                        "size" => $size
                    ));
                }
            }
            if ($wrapper_class) {
                $return .= "</div>";
            }
            return $return;
        }
        return false;
    }

    /**
     * Creates avatar for entity
     * 
     * @param string $filename  Name of input field (defaults to 'avatar')
     * @param mixed $copy Icon to copy, or false to not copy
     */
    public function createAvatar($filename = "avatar", $copy = false) {
        if (!isset($_FILES[$filename]) || !$_FILES[$filename]["name"]) {
            return;
        }
        $file = new File;
        $file->owner_guid = getLoggedInUserGuid();
        $file->access_id = $this->access_id;
        $file_guid = $file->save();
        $filename = uploadFile($filename, $file_guid, array(
            "png",
            "jpg",
            "jpeg",
            "gif",
            "doc",
            "docx",
            "ods"
                ), $copy);
        $file = getEntity($file_guid);
        $file->filename = $filename;
        $file->save();

        $this->icon = $file->guid;
        $this->save();

        Image::createThumbnail($file->guid, TINY);
        Image::createThumbnail($file->guid, SMALL);
        Image::createThumbnail($file->guid, MEDIUM);
        Image::createThumbnail($file->guid, LARGE);
        Image::createThumbnail($file->guid, EXTRALARGE);
        Image::createThumbnail($file->guid, HUGE);
        return;
    }

    /**
     * Checks if user owns an object
     * 
     * @param object $object  Object to check
     * @return boolean  true if user owns object, false if not
     */
    static function owns($object) {
        if ($object->owner_guid == $this->guid || adminLoggedIn()) {
            return true;
        }
        return false;
    }

    /**
     * Checks if logged in user can delete entity
     * 
     * @param object $entity  Entity to check
     * @return boolean  true if can delete, false if can't
     */
    static function loggedInUserCanDelete($entity) {
        if ($entity->owner_guid) {
            if (getLoggedInUserGuid() == $entity->owner_guid) {
                return true;
            }
            if (adminLoggedIn()) {
                return true;
            }
        }
        return false;
    }

    public function getURL() {
        return false;
    }

}
