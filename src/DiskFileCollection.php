<?php

namespace Live\Collection;

/**
 * DiskFile collection
 *
 * @package Live\Collection
 */
class DiskFileCollection implements CollectionInterface
{
    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    protected $resourceFile;

    /**
     * Constructor
     */
    public function __construct(string $directory, string $fileName)
    {
        FileStorage::openOrCreateFile($directory . $fileName);
        $this->data = FileStorage::getContent();

        if ($this->data == false) {
            $this->data = [];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        if (!$this->has($index)) {
            return $defaultValue;
        }
        return $this->data[$index]['value'];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, $secondsToExpire = 5)
    {
        $this->data[$index] = ['value'=> $value, 'expires'=> time() + $secondsToExpire ];
        $this->save();
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        return array_key_exists($index, $this->data) && time() <= $this->data[$index]['expires'];
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        $this->data = [];
        FileStorage::clear();
    }

    public function save()
    {
        return FileStorage::save($this->data);
    }

    public function close()
    {
        return FileStorage::close();
    }

    public function removeFile()
    {
        return FileStorage::removeFile();
    }
}
