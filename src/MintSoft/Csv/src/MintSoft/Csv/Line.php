<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 17.11.14
 * Time: 14:46
 */

namespace Csv;

class Line implements Exportable
{
    /**
     * @var array
     */
    protected $source = [];

    /**
     * @param array $sources
     */
    public function __construct(array $sources = [])
    {
        $this->setSources($sources);
    }

    /**
     * @param array $sources
     *
     * @return $this
     */
    public function setSources(array $sources)
    {
        $this->source = [];
        foreach ($sources as $source) {
            $this->addSource($source);
        }

        return $this;
    }

    /**
     * @param Exportable $source
     * @param array      $rename
     *
     * @return $this
     */
    public function addSource($source, array $rename = [])
    {
        if ($source instanceof Exportable) {
            $sourceArray = $source->asArray();
        } elseif (is_array($source) || $source instanceof \Traversable) {
            $sourceArray = $source;
        }

        $this->source = array_merge($this->source, $this->renameArrayKeys($sourceArray, $rename));

        return $this;
    }

    /**
     * @param array $source
     * @param array $rename
     *
     * @return array
     */
    private function renameArrayKeys(array $source, array $rename)
    {
        foreach ($rename as $oldName => $newName) {
            if (!array_key_exists($oldName, $source)) {
                continue;
            }
            $source[$newName] = $source[$oldName];
            unset($source[$oldName]);
        }

        return $source;
    }

    /**
     * @return mixed
     */
    public function asArray()
    {
        return $this->source;
    }
}