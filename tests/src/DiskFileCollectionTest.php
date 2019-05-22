<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

class DiskFileCollectionTest extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanBeConstructed()
    {
        $collection = new DiskFileCollection('files/', 'storage.txt');
        return $collection;
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function objectCanPersistBeConstructed()
    {
        FileStorage::openOrCreateFile('files/', 'storage.txt');
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded()
    {
        $collection = new DiskFileCollection('files/', 'storage.txt');
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);
        $collection->set('index4', 6.5);
        $collection->set('index5', ['data']);
    }


    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function dataPersistCanBeRetrieved()
    {
        $collection = new DiskFileCollection('files/', 'storage.txt');
        $this->assertEquals(6.5, $collection->get('index4'));
    }

    /**
     * @test
     * @depends dataPersistCanBeRetrieved
     */
    public function inexistentIndexShouldReturnDefaultValue()
    {
        $collection = new DiskFileCollection('files/', 'storage.txt');

        $this->assertNull($collection->get('test1'));
        $this->assertEquals('defaultValue', $collection->get('test1', 'defaultValue'));
    }

    /**
     * @test
     * @depends inexistentIndexShouldReturnDefaultValue
     */
    public function collectionPersistsWithItemsShouldReturnValidCount()
    {
        $collection = new DiskFileCollection('files/', 'storage.txt');
        $collection->set('index2', true, 10);

        $this->assertEquals(5, $collection->count());
    }

    /**
     * @test
     * @depends collectionPersistsWithItemsShouldReturnValidCount
     */
    public function collectionPersistCanBeCleaned()
    {
        $collection = new DiskFileCollection('files/', 'storage.txt');
        $this->assertEquals(5, $collection->count());
        $collection->clean();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends collectionPersistCanBeCleaned
     */
    public function dataCannotBeRetrievedAfterExpires()
    {
        $collection = new DiskFileCollection('files/', 'storage.txt');
        $collection->set('index1', 'value', 2);
        sleep(3);
        $this->assertFalse($collection->has('index1'));
    }

    /**
     * @test
     * @depends dataCannotBeRetrievedAfterExpires
     */
    public function fileCollectionCanBeCloseAndTerminateFile()
    {
        $collection = new DiskFileCollection('files/', 'storage.txt');
        $this->assertTrue($collection->close());
        $this->assertTrue($collection->removeFile());
    }
}
