<?php

namespace fl\tree;

use BadMethodCallException;
use JsonSerializable;
use RuntimeException;

class CategoryTree implements JsonSerializable
{
    /** @var CategoryTreeItem[] */
    protected array $items = [];

    /** @var CategoryTreeItem[] */
    protected array $children = [];


    public function __construct(string $csvFileName, bool $firstLineAsHeaders = true, string $separator = ',')
    {
        if (!(file_exists($csvFileName) && is_file($csvFileName)))
        {
            throw new BadMethodCallException("File '$csvFileName' not exist!");
        }

        $handle = fopen($csvFileName, 'rb');

        if (!$handle)
        {
            throw new RuntimeException("Can't open file $csvFileName");
        }

        if ($firstLineAsHeaders)
        {
            // $headers = fgetcsv($handle, null, $separator);
            fgets($handle);
        }

        while (($row = fgetcsv($handle, null, $separator)) !== false)
        {
            if (!count(array_filter($row)))
            {
                continue;
            }

            // добавить проверку массива, если csv будет формироваться ручками
            $item = new CategoryTreeItem(...$row);

            $this->items[$item->id] = $item;
        }

        fclose($handle);

        // если список категорий будет содержать livel и будет отсортирован по нему, то можно запихивать в while
        foreach ($this->items as $item)
        {
            if ($item->parent_id)
            {
                if (!array_key_exists($item->parent_id, $this->items))
                {
                    trigger_error("Not found category with id: $item->parent_id");

                    continue;
                }

                $parent = $this->items[$item->parent_id];

                $item->parent                = $parent;
                $parent->children[$item->id] = $item;
            }
            else
            {
                $this->children[$item->id] = $item;
            }
        }
    }


    public function jsonSerialize()
    {
        return $this->children;
    }
}
