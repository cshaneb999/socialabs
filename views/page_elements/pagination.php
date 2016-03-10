<?php

/**
 * Displays pagination
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

$count = Vars::get('count');
$offset = Vars::get('offset');
$limit = Vars::get('limit');
$url = Vars::get('url');

if (strpos($url, '?') !== false) {
    $chr = "&";
} else {
    $chr = "?";
}

if ($count >= $limit) {

    $total_pages = (int) ceil($count / $limit);
    $current_page = (int) ceil($offset / $limit) + 1;

    $pages = array();

    $start_page = max(min([$current_page - 2, $total_pages - 4]), 1);

    $prev_offset = $offset - $limit;
    if ($prev_offset < 1) {
        $prev_offset = null;
    }

    $pages['prev'] = array(
        'text' => "Previous",
        'href' => "{$chr}offset=" . ((int) $offset - (int) $limit)
    );

    if ($current_page == 1) {
        $pages['prev']['disabled'] = true;
    }

    if (1 < $start_page) {
        $pages[1] = array(
            "text" => "1",
            "href" => "{$chr}offset=0"
        );
    }

    if (1 < ($start_page - 2)) {
        $pages[] = ['text' => '...', 'disabled' => true];
    } elseif ($start_page == 3) {
        $pages[2] = array(
            'text' => '2',
            'href' => "{$chr}offset=" . ($limit * 2)
        );
    }

    $max = 1;
    for ($page = $start_page; $page <= $total_pages; $page++) {
        if ($max > 20) {
            break;
        }
        $href_offset = ((($max * (int) $limit) - ((int) $limit)) + ($start_page * $limit) - $limit);
        $href = "{$chr}offset=" . $href_offset;
        $pages[$page] = array(
            "text" => $page,
            "href" => $href,
            "class" => ($href_offset == $offset ? "active" : "")
        );
        $max++;
    }

    if ($total_pages > ($start_page + 6)) {
        $pages[] = array('text' => '...', 'disabled' => true);
    } elseif (($start_page + 5) == ($total_pages - 1)) {
        $pages[$total_pages - 1] = array();
    }

    if ($total_pages >= ($start_page + 5)) {
        $pages[$total_pages] = array(
            "text" => $total_pages,
            "href" => "{$chr}offset=" . (((int) $total_pages * (int) $limit) - (int) $limit)
        );
    }

    $next_offset = $offset + $limit;
    if ($next_offset >= $count) {
        $next_offset--;
    }

    $pages['next'] = array(
        'text' => "Next",
        'href' => "{$chr}offset=" . ($offset + $limit)
    );

    if ($current_page == $total_pages) {
        $pages['next']['disabled'] = true;
    }
    echo "<div style='clear:both;'></div><center>";
    echo "<ul class='pagination'>";
    foreach ($pages as $page) {
        $href = (isset($page['href']) ? $page['href'] : "");
        $class = NULL;
        if (isset($page['disabled'])) {
            $class .= "disabled";
        }
        if (isset($page['class']) && $page['class']) {
            $class .= " " . $page['class'];
        }
        echo "<li class='$class'><a href='{$url}{$href}'>{$page['text']}</a></li>";
    }
    echo "</ul>";
    echo "</center>";
}

