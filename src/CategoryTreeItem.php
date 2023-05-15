<?php


namespace fl\tree;

use JsonSerializable;

class CategoryTreeItem implements JsonSerializable
{
    public int    $id;
    public int    $parent_id;
    public string $name;

    public ?CategoryTreeItem $parent = null;

    /** @var self[] */
    public array $children = [];


    public function __construct($id, $parent_id, $name)
    {
        $this->id        = (int) $id;
        $this->parent_id = (int) $parent_id;
        $this->name      = $name;
    }


    public function jsonSerialize()
    {
        $result = [
            'name' => $this->name,
        ];

        if (count($this->children))
        {
            $result['children'] = $this->children;
        }

        return $result;
    }
}
