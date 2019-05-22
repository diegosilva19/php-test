<?php

namespace Live\Collection;

/**
 * File
 *
 * @package Live\Collection
 */
class FileStorage
{
    /**
     * resource file
     *
     * @var resource
     */
    protected static $fileResource = null;

    /**
     * string of complete path to file
     *
     * @var string
     */
    protected static $fileStoragePath;

    /**
     * openOrCreateFile use to get a single pointer of file
     * @return void
     */
    public static function openOrCreateFile(string $filePath)
    {
        self::$fileStoragePath = $filePath;

        if (self::$fileResource == null) {
            self::$fileResource = fopen($filePath, 'a+');
        }
    }

    /**
     * getContent retrieve all content from file and apply unserialize to php
     * @return mixed
     */
    public static function getContent()
    {
        $content= '';
        fseek(self::$fileResource, 0);
        while (($line = fgets(self::$fileResource)) !== false) {
            $content.= $line;
        }
        return unserialize($content);
    }

    /**
     * save store the information of array set
     * @return bool
     */
    public static function save(array $data) : bool
    {
        ftruncate(self::$fileResource, 0);
        return fwrite(self::$fileResource, serialize($data));
    }

    /**
     * clear : erase all content in file
     * @return bool
     */
    public static function clear() : bool
    {
        return ftruncate(self::$fileResource, 0);
    }

    /**
     * close : close file and sets resource null in File::class
     * @return bool
     */
    public static function close() : bool
    {
        $closeResult= fclose(self::$fileResource);
        self::$fileResource = null;
        return $closeResult;
    }

    /**
     * removeFile : erase file from disk
     * @return bool
     */
    public static function removeFile() : bool
    {
        return unlink(self::$fileStoragePath);
    }
}
