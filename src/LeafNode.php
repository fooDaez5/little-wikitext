<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext;

/**
 * A leaf node has no children.
 */
abstract class LeafNode extends Node {
	protected function __construct() {
		parent::__construct( [] );
	}
}
