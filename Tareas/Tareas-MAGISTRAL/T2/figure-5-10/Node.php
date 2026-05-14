<?php

class Node
{
    public $id;
    public $label;
    public $left;
    public $right;

    public function __construct($id, $label, $left = null, $right = null)
    {
        $this->id = $id;
        $this->label = $label;
        $this->left = $left;
        $this->right = $right;
    }
}