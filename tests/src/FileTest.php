<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    /**
     * @test
     */
    public function fileCanBeCreateAndStoreInMemory()
    {
        $directory= 'files/';
        $fileName= 'storage.txt';
        FileStorage::openOrCreateFile($directory . $fileName);
        $this->assertTrue(FileStorage::close($directory . $fileName));
        FileStorage::openOrCreateFile($directory . $fileName);
    }
}
