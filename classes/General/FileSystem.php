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

class FileSystem {

    /**
     * Creates file path
     * 
     * @param string $pathname  Path
     * @param int $mode Access mode (0777)
     * @return boolean  true if path is made, false if not
     */
    static function makePath($pathname, $mode = 0777) {
        if (is_dir($pathname) || empty($pathname)) {
            return true;
        }

        if (is_file($pathname)) {
            return false;
        }
        $pathname = rtrim($pathname, DIRECTORY_SEPARATOR);
        $nextPathname = substr($pathname, 0, strrpos($pathname, DIRECTORY_SEPARATOR));

        if (makePath($nextPathname, $mode)) {
            if (!file_exists($pathname)) {
                $old = umask(0);
                if (mkdir($pathname, $mode, true)) {
                    umask($old);
                    return true;
                }
                umask($old);
                return false;
            }
        }
        return false;
    }

    /**
     * Shortcut function to upload file
     * 
     * @param string $filename Name of uploaded file form element
     * @param int $file_guid Guid of filestore item to reference file
     * @param string[] $allowed_extensions Array of allowed extensions.  Other extentions will not be uploaded
     * @return false|string false if upload fails|string Name of uploaded file 
     */
    static function uploadFile($filename, $file_guid, $allowed_extensions = array("png", "jpg", "jpeg", "gif", "doc", "docx", "ods")) {

        if (!$filename || !$file_guid) {
            return false;
        }

        $file_entity = getEntity($file_guid);

        $target_dir = getDataPath() . "files" . "/" . $file_guid . "/";

        makePath($target_dir, 0777);

        $name = basename($_FILES[$filename]["name"]);
        $name = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $name);
        $name = preg_replace("([\.]{2,})", '', $name);
        $target_file = $target_dir . $name;

        $file_entity->path = $target_file;
        $file_entity->extension = pathinfo($target_file, PATHINFO_EXTENSION);
        $file_entity->save();
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        if (!empty($allowed_extensions) && is_array($allowed_extensions)) {
            if (!in_array(strtolower($imageFileType), $allowed_extensions)) {
                $file_entity->delete();
                new SystemMessage("Allowed file types: " . implode(" ", $allowed_extensions));
                forward();
            }
        }
        $error = move_uploaded_file($_FILES[$filename]["tmp_name"], $target_file);
        $finfo = \finfo_open(FILEINFO_MIME_TYPE);
        $mime = \finfo_file($finfo, $target_file);
        \finfo_close($finfo);
        if ($mime == "image/jpeg" || $mime == "image/jpg" || $mime == "image/gif") {
            Image::fixImageRotation($target_file);
        }
        $file_entity->file_location = $target_file;
        $file_entity->mime_type = $mime;
        $file_entity->filename = $name;
        $file_entity->save();
        return $name;
    }

    static function serveFilePartial($fileName, $fileTitle = null, $contentType = 'application/octet-stream') {
        if (!file_exists($fileName)) {
                    throw New \Exception(sprintf('File not found: %s', $fileName));
        }
        if (!is_readable($fileName)) {
                    throw New \Exception(sprintf('File not readable: %s', $fileName));
        }
        ### Remove headers that might unnecessarily clutter up the output
        header_remove('Cache-Control');
        header_remove('Pragma');
        ### Default to send entire file
        $byteOffset = 0;
        $byteLength = $fileSize = filesize($fileName);
        header('Accept-Ranges: bytes', true);
        header(sprintf('Content-Type: %s', $contentType), true);
        if ($fileTitle) {
                    header(sprintf('Content-Disposition: attachment; filename="%s"', $fileTitle));
        }
        ### Parse Content-Range header for byte offsets, looks like "bytes=11525-" OR "bytes=11525-12451"
        if (isset($_SERVER['HTTP_RANGE']) && preg_match('%bytes=(\d+)-(\d+)?%i', $_SERVER['HTTP_RANGE'], $match)) {
            ### Offset signifies where we should begin to read the file
            $byteOffset = (int) $match[1];
            ### Length is for how long we should read the file according to the browser, and can never go beyond the file size
            if (isset($match[2])) {
                $finishBytes = (int) $match[2];
                $byteLength = $finishBytes + 1;
            } else {
                $finishBytes = $fileSize - 1;
            }

            $cr_header = sprintf('Content-Range: bytes %d-%d/%d', $byteOffset, $finishBytes, $fileSize);

            header("HTTP/1.1 206 Partial content");
            header($cr_header); ### Decrease by 1 on byte-length since this definition is zero-based index of bytes being sent
        }
        $byteRange = $byteLength - $byteOffset;
        header(sprintf('Content-Length: %d', $byteRange));
        header(sprintf('Expires: %s', date('D, d M Y H:i:s', time() + 60 * 60 * 24 * 90) . ' GMT'));
        $buffer = ''; ### Variable containing the buffer
        $bufferSize = 512 * 16; ### Just a reasonable buffer size
        $bytePool = $byteRange; ### Contains how much is left to read of the byteRange
        if (!$handle = fopen($fileName, 'r')) {
                    throw New \Exception(sprintf("Could not get handle for file %s", $fileName));
        }
        if (fseek($handle, $byteOffset, SEEK_SET) == -1) {
                    throw New \Exception(sprintf("Could not seek to byte offset %d", $byteOffset));
        }
        while ($bytePool > 0) {
            $chunkSizeRequested = min($bufferSize, $bytePool); ### How many bytes we request on this iteration
            ### Try readin $chunkSizeRequested bytes from $handle and put data in $buffer
            $buffer = fread($handle, $chunkSizeRequested);
            ### Store how many bytes were actually read
            $chunkSizeActual = strlen($buffer);
            ### If we didn't get any bytes that means something unexpected has happened since $bytePool should be zero already
            if ($chunkSizeActual == 0) {
                ### For production servers this should go in your php error log, since it will break the output
                trigger_error('Chunksize became 0', E_USER_WARNING);
                break;
            }
            ### Decrease byte pool with amount of bytes that were read during this iteration
            $bytePool -= $chunkSizeActual;
            ### Write the buffer to output
            print $buffer;
            ### Try to output the data to the client immediately
            flush();
        }
        exit();
    }

}
