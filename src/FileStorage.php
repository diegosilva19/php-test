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
     * Collection data
     *
     * @var array
     */
    protected static $fileResource = null;

    protected static $fileStoragePath;

    public static function openOrCreateFile(string $filePath)
    {
        self::$fileStoragePath = $filePath;

        if (self::$fileResource == null) {
            self::$fileResource = fopen($filePath, 'a+');
        }
    }

    public static function getContent()
    {
        $content= '';
        fseek(self::$fileResource, 0);
        while (($line = fgets(self::$fileResource)) !== false) {
            $content.= $line;
        }
        return unserialize($content);
    }

    public static function save(array $data)
    {
        ftruncate(self::$fileResource, 0);
        return fwrite(self::$fileResource, serialize($data));
    }

    public static function clear()
    {
        return ftruncate(self::$fileResource, 0);
    }

    public static function close()
    {
        $closeResult= fclose(self::$fileResource);
        self::$fileResource = null;
        return $closeResult;
    }

    public static function removeFile()
    {
        return unlink(self::$fileStoragePath);
    }
}
