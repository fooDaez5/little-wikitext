<?php

namespace Wikimedia\LittleWikitext\Tests;

use Wikimedia\LittleWikitext\LittleWikitext;

/**
 * @coversDefaultClass \Wikimedia\LittleWikitext\LittleWikitext
 */
class LittleWikitextTest extends \PHPUnit\Framework\TestCase {

	public function provideSamples() {
		return [
			[
				'wt' => <<<EOF
== Fix a bug ==
* Understand bug report
* Write code
* Test

== WIP: QA ==
* Verify fix in beta cluster
* Resolve bug?
* Something more?
EOF,
				'html' => <<<EOF
<section>
<h2>Fix a bug</h2>
<ul>
<li>Understand bug report</li>
<li>Write code</li>
<li>Test</li>
</ul>
<p></p>
</section>
<section>
<h2>WIP: QA</h2>
<ul>
<li>Verify fix in beta cluster</li>
<li>Resolve bug?</li>
<li>Something more?</li>
</ul>
</section>
EOF,
			],
			[
				'wt' => <<<EOF
Level 0
* Level 1
** Level 2
*** Level 3
* Level 1
Level 0
EOF,
				'html' => <<<EOF
<p>Level 0</p>
<ul>
<li>Level 1</li>
<li><ul>
<li>Level 2</li>
<li><ul>
<li>Level 3</li>
</ul></li>
</ul></li>
<li>Level 1</li>
</ul>
<p>Level 0</p>
EOF,
			],
			[
				'wt' => <<<EOF
This is a [[Target|link to somewhere else]].
This is a template inclusion:
{{Next section}}
== Next section ==
This is the next section!
EOF,
				'html' => <<<EOF
<p>This is a <a href="./Target">link to somewhere else</a>.</p>
<p>This is a template inclusion:</p>
<p><template id="Next section"></template></p>
<section>
<h2>Next section</h2>
<p>This is the next section!</p>
</section>
EOF,
			],
		];
	}

	/**
	 * @dataProvider provideSamples
	 * @covers ::wt2ast
	 */
	public function testSample( $input, $output ) {
		// Ensure that input and output end in nl
		if ( substr( $input, -1 ) !== "\n" ) {
			$input .= "\n";
		}
		if ( substr( $output, -1 ) !== "\n" ) {
			$output .= "\n";
		}
		$ast = LittleWikitext::wt2ast( $input );
		$html = $ast->toHtml();
		$this->assertEquals( $output, $html );

		// Assert validity of HTML!
		// Use ::makeValidHtml and ::assertEquals here for better diagnostics
		// if the test fails.
		$this->assertEquals( ValidHtmlTest::makeValidHtml( $html ), $html );

		// Now convert the AST back to wikitext; this conversion should be
		// lossless.
		$wikitext = $ast->toWikitext();
		$this->assertEquals( $input, $wikitext );
	}
}
