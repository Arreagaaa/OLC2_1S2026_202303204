<?php

class Tree
{
	public $root;
	public $dot = "graph {\n";
	private $visitedInOrder = [];
	private $visitedPostOrder = [];

	public function inOrder(?Node $node)
	{
		if ($node !== null && !isset($this->visitedInOrder[$node->id])) {
			$this->inOrder($node->left);
			echo $node->label . " ";
			$this->visitedInOrder[$node->id] = true;
			$this->inOrder($node->right);
		}
	}
	public function postOrder(?Node $node)
	{
		if ($node !== null  && !isset($this->visitedPostOrder[$node->id])) {
			$this->postOrder($node->left);
			$this->postOrder($node->right);

			$this->visitedPostOrder[$node->id] = true;
			echo $node->label . " ";

			$this->dot .= $node->id . ' [label="' . $node->label . '"];' . "\n";
			if ($node->left !== null) {
				$this->dot .= $node->id . '--' . $node->left->id . ";\n";
			}
			if ($node->right !== null) {
				$this->dot .= $node->id . '--' . $node->right->id . ";\n";
			}
		}
	}
}
