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

class SiteCache {

    public $method;

    function __construct($key = false, $value = false) {
        if ($key) {
            if (extension_loaded("memcached")) {
                $memcache = self::memcacheInit();
                $memcache->set($key, $value);
            } else {
                new FileCache($key, $value);
            }
        }
    }

    static function memcacheInit() {
        global $memcache;
        if ($memcache) {
            return $memcache;
        }
        $memcache = new \Memcached;
        return $memcache;
    }

    static function get($key) {
        if (extension_loaded("memcached")) {
            $memcache = self::memcacheInit();
            return $memcache->get($key);
        } else {
            return FileCache::get($key);
        }
    }

    static function clear() {
        if (extension_loaded("memcached")) {
            $memcache = self::memcacheInit();
            $memcache->flush();
        } else {
            FileCache::clear();
        }
    }

}
