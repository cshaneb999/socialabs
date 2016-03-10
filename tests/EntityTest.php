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

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

class EntityTest extends \PHPUnit_Framework_TestCase {

    public function testSave() {

        // Count users
        $user_count = getEntities(array(
                    "type" => "User",
                    "count" => true
        ));

        // Create a new entity
        $entity = new Entity;
        $entity->type = "User";
        $entity->name = "Test User";
        $entity->email = "test@test.com";
        $entity->save();

        // Retrieve last entity
        $test_entity = getEntity(array(
                    "type" => "User",
                    "metadata_name" => "name",
                    "metadata_value" => "Test User"
        ));

        $new_user_count = getEntities(array(
                    "type" => "User",
                    "count" => true
        ));

        // Verify that new entity metadata matches retrieved metadata
        $this->assertEquals($entity->name, $test_entity->name);

        // Verify that new user count is 1 more than previous
        $this->assertEquals($new_user_count - 1, $user_count);

        // Verify that we can delete a user
        $user = getEntity(array(
            "type" => "User",
            "metadata_name" => "email",
            "metadata_value" => "test@test.com"
        ));

        // Verify that we loaded the correct user
        $this->assertEquals($entity->email, "test@test.com");

        $user->delete();

        $count = getEntities(array(
            "type" => "User",
            "count" => true
        ));

        // Verify that the user has been deleted
        $this->assertEquals($count + 1, $new_user_count);
    }

}
