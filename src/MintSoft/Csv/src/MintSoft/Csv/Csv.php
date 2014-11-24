<?php
/**
 * Created by PhpStorm.
 * User: sokool
 * Date: 17.11.14
 * Time: 14:37
 */
namespace Csv;

class Csv
{
    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var Exportable[]
     */
    protected $lines = [];

    /**
     * @var CSV[]
     */
    protected $merge = [];

    /**
     * @var array
     */
    protected $lineTemplate = [];

    public static $separator = ';';

    public function __construct(array $columns)
    {
        $this->columns = $columns;
        foreach ($columns as $key => $v) {
            $this->lineTemplate[$key] = null;
        }
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param $fileName
     *
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @param $lines
     *
     * @return $this
     */
    public function setLines($lines)
    {
        if (!is_array($lines) && !$lines instanceof \Traversable) {
            throw new \InvalidArgumentException(sprintf(
                '%s expects an array or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($lines) ? get_class($lines) : gettype($lines))
            ));
        }

        foreach ($lines as $potentialLine) {
            $this->addLine($potentialLine);
        }

        return $this;
    }

    /**
     * @param Exportable $line
     *
     * @return $this
     */
    public function addLine(Exportable $line)
    {
        $this->lines[] = $line->asArray();

        return $this;
    }

    /**
     * @param CSV $csv
     *
     * @return $this
     */
    public function merge(Csv $csv)
    {
        $this->merge[] = $csv;

        return $this;
    }

    /**
     * @param array $elements
     *
     * @return string
     */
    protected function renderLine(array $elements)
    {
        $mergedLine = array_merge($this->lineTemplate, array_intersect_key($elements, $this->lineTemplate));

        return '"' . implode('"' . self::$separator . '"', $mergedLine) . "\"\n";
    }

    /**
     * @return string
     */
    public function __toString()
    {
        //Render lines of CSV, first COLUMNS then LINES
        $csvString = $this->renderLine($this->columns);
        foreach ($this->lines as $line) {
            $csvString .= $this->renderLine($line);
        }

        //Merge CSV files
        foreach ($this->merge as $csv) {
            $csvString .= (string) $csv;
        }

        return $csvString;
    }
}