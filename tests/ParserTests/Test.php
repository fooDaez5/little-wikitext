<?php
declare( strict_types = 1 );

namespace Wikimedia\LittleWikitext\Tests\ParserTests;

/**
 * Represents a parser test
 */
class Test extends Item {
	/* --- These are test properties from the test file --- */

	/** @var ?string This is the test name, not page title for the test */
	public $testName = null;

	/** @var array */
	public $options = [];

	/** @var ?string */
	public $config = null;

	/** @var array */
	public $sections = [];

	private const DIRECT_KEYS = [
		'type',
		'filename',
		'lineNumStart',
		'lineNumEnd',
		'testName',
		'options',
		'config',
	];

	/**
	 * @param array $testProperties key-value mapping of properties
	 * @param ?string $comment Optional comment describing the test
	 */
	public function __construct(
		array $testProperties,
		?string $comment = null
	) {
		parent::__construct( $testProperties, $comment );

		foreach ( $testProperties as $key => $value ) {
			if ( in_array( $key, self::DIRECT_KEYS, true ) ) {
				$this->$key = $value;
			} else {
				if ( isset( $this->sections[$key] ) ) {
					$this->error( "Duplicate test section", $key );
				}
				$this->sections[$key] = $value;
			}
		}
	}

	/**
	 * @param array $testFilter Test Filter as set in TestRunner
	 * @return bool if test matches the filter
	 */
	public function matchesFilter( $testFilter ): bool {
		if ( !$testFilter ) {
			// Trivial match
			return true;
		}
		if ( $this->testName === null ) {
			// No name, no match
			return false;
		}

		if ( !empty( $testFilter['regex'] ) ) {
			$regex = isset( $testFilter['raw'] ) ?
				   ( '/' . $testFilter['raw'] . '/' ) :
				   $testFilter['regex'];
			return (bool)preg_match( $regex, $this->testName );
		}

		if ( !empty( $testFilter['string'] ) ) {
			return strpos( $this->testName, $testFilter['raw'] ) !== false;
		}

		// Trivial match because of a bad test filter
		return true;
	}

	public function __toString(): string {
		return $this->testName ?? '<unknown test>';
	}
}
