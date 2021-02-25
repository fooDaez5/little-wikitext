<?php

namespace Wikimedia\LittleWikitext;

class Grammar extends \WikiPEG\PEGParserBase {
	/**
	 * @param string $contents
	 * @return Root
	 */
	public static function load( string $contents ): Root {
	}

	/**
	 * @param string $input Input string
	 * @return Root Result of the parse
	 */
	public function parse( $input ): Root {
		return null;
	}
}
