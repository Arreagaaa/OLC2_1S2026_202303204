<?php

class Tree
{
    public $root;
    public $dot = "graph {\n";

    public function inOrder(?Node $node)
    {
        if ($node !== null) {
            $this->inOrder($node->left);
            echo $node->label . " ";
            $this->inOrder($node->right);
        }
    }

    public function postOrder(?Node $node)
    {
        if ($node !== null) {
            $this->postOrder($node->left);
            $this->postOrder($node->right);
            echo $node->label . " ";

            $this->dot .= $node->id . '[label="' . $node->label . '"]' . "\n";

            if ($node->left !== null) {
                $this->dot .= $node->id . ' -- ' . $node->left->id . "\n";
            }
            if ($node->right !== null) {
                $this->dot .= $node->id . ' -- ' . $node->right->id . "\n";
            }
        }
    }
}
