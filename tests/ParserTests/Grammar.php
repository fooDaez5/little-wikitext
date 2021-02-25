<?php




/* File-scope initializer */
namespace Wikimedia\LittleWikitext\Tests\ParserTests;


class Grammar extends \WikiPEG\PEGParserBase {
  // initializer
  
  	/** @var string */
  	private $filename = '';
  	/** @var int */
  	private $lineNum = 1;
  
  	/**
  	 * @param string $filename
  	 * @return array
  	 */
  	public static function load( string $filename ) {
  		$g = new Grammar();
  		$g->filename = $filename;
  		$contents = file_get_contents( $filename ) ?: '';
  		if ( substr( $contents, -1 ) !== "\n" ) {
  			# ensure that the file is terminated with a newline
  			# to match `end_section` rule (and other uses of `eol`)
  			$contents .= "\n";
  		}
  		return $g->parse( $contents );
  	}
  

  // cache init
  

  // expectations
  protected $expectations = [
    0 => ["type" => "end", "description" => "end of input"],
    1 => ["type" => "literal", "value" => "#", "description" => "\"#\""],
    2 => ["type" => "class", "value" => "[^\\r\\n]", "description" => "[^\\r\\n]"],
    3 => ["type" => "literal", "value" => "!!", "description" => "\"!!\""],
    4 => ["type" => "literal", "value" => "test", "description" => "\"test\""],
    5 => ["type" => "class", "value" => "[^ \\t\\r\\n]", "description" => "[^ \\t\\r\\n]"],
    6 => ["type" => "literal", "value" => "options", "description" => "\"options\""],
    7 => ["type" => "literal", "value" => "end", "description" => "\"end\""],
    8 => ["type" => "class", "value" => "[\\r]", "description" => "[\\r]"],
    9 => ["type" => "class", "value" => "[\\n]", "description" => "[\\n]"],
    10 => ["type" => "literal", "value" => "\x0a", "description" => "\"\\n\""],
    11 => ["type" => "class", "value" => "[ \\t]", "description" => "[ \\t]"],
    12 => ["type" => "class", "value" => "[^ \\t\\r\\n=!]", "description" => "[^ \\t\\r\\n=!]"],
    13 => ["type" => "literal", "value" => "=", "description" => "\"=\""],
    14 => ["type" => "literal", "value" => ",", "description" => "\",\""],
    15 => ["type" => "literal", "value" => "[[", "description" => "\"[[\""],
    16 => ["type" => "class", "value" => "[^\\]\\r\\n]", "description" => "[^\\]\\r\\n]"],
    17 => ["type" => "literal", "value" => "]]", "description" => "\"]]\""],
    18 => ["type" => "class", "value" => "[\\\"]", "description" => "[\\\"]"],
    19 => ["type" => "class", "value" => "[^\\\\\\\"\\r\\n]", "description" => "[^\\\\\\\"\\r\\n]"],
    20 => ["type" => "literal", "value" => "\\", "description" => "\"\\\\\""],
    21 => ["type" => "class", "value" => "[^ \\t\\r\\n\\\"\\'\\[\\]=,!\\{]", "description" => "[^ \\t\\r\\n\\\"\\'\\[\\]=,!\\{]"],
    22 => ["type" => "literal", "value" => "{", "description" => "\"{\""],
    23 => ["type" => "class", "value" => "[^\\\"\\{\\}\\r\\n]", "description" => "[^\\\"\\{\\}\\r\\n]"],
    24 => ["type" => "literal", "value" => "}", "description" => "\"}\""],
  ];

  // actions
  private function a0() {
   return $this->lineNum; 
  }
  private function a1($l, $c) {
  
  	$c['filename'] = $this->filename;
  	$c['lineNumStart'] = $l;
  	$c['lineNumEnd'] = $this->lineNum;
  	return $c;
  
  }
  private function a2($l) {
   return [ 'type' => 'line', 'text' => $l ]; 
  }
  private function a3($text) {
   return [ 'type' => 'comment', 'text' => $text ]; 
  }
  private function a4($testName, $sections) {
  
  	$test = [
  		'type' => 'test',
  		'testName' => $testName
  	];
  
  	foreach ( $sections as $section ) {
  		$test[$section['name']] = $section['text'];
  	}
  	// pegjs parser handles item options as follows:
  	//   item option             value of item.options.parsoid
  	//    <none>                          undefined
  	//    parsoid                             ""
  	//    parsoid=wt2html                  "wt2html"
  	//    parsoid=wt2html,wt2wt        ["wt2html","wt2wt"]
  	//    parsoid={"modes":["wt2wt"]}    {modes:['wt2wt']}
  
  	return $test;
  
  }
  private function a5($line) {
  
  	return $line;
  
  }
  private function a6($c) {
  
  	return implode($c);
  
  }
  private function a7($lines) {
  
  	$lines[] = ''; // Ensure \n after last line
  	return implode("\n", $lines);
  
  }
  private function a8($c) {
   return implode( $c ); 
  }
  private function a9($name, $text) {
  
  	return [ 'name' => $name, 'text' => $text ];
  
  }
  private function a10($opts) {
  
  	$o = [];
  	if ( $opts && count($opts) > 0 ) {
  		foreach ( $opts as $opt ) {
  			$o[$opt['k']] = $opt['v'];
  		}
  	}
  
  	return [ 'name' => 'options', 'text' => $o ];
  
  }
  private function a11() {
   $this->lineNum++; return "\n"; 
  }
  private function a12($o, $rest) {
  
  	$result = [ $o ];
  	if ( $rest && count( $rest ) > 0 ) {
  		$result = array_merge( $result, $rest );
  	}
  	return $result;
  
  }
  private function a13($k, $v) {
  
  	return [ 'k' => strtolower( $k ), 'v' => $v ?? '' ];
  
  }
  private function a14($ovl) {
  
  	return count( $ovl ) === 1 ? $ovl[0] : $ovl;
  
  }
  private function a15($v, $ovl) {
   return $ovl; 
  }
  private function a16($v, $rest) {
  
  	$result = [ $v ];
  	if ( $rest && count( $rest ) > 0 ) {
  		$result = array_merge( $result, $rest );
  	}
  	return $result;
  
  }
  private function a17($v) {
  
  	if ( $v[0] === '"' || $v[0] === '{' ) { // } is needed to make pegjs happy
  		return json_decode( $v, true );
  	}
  	return $v;
  
  }
  private function a18($v) {
  
  	// Perhaps we should canonicalize the title?
  	// Protect with JSON.stringify just in case the link target starts with
  	// double-quote or open-brace.
  	return json_encode( implode( $v ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
  
  }
  private function a19($c) {
   return "\\" . $c; 
  }
  private function a20($v) {
  
  	return '"' . implode( $v ) . '"';
  
  }
  private function a21($v) {
  
  	return implode( $v );
  
  }
  private function a22($v) {
  
  	return "{" . implode( $v ) . "}";
  
  }

  // generated
  private function parsetestfile($silence) {
    $r1 = [];
    for (;;) {
      $r2 = $this->parselined_chunk($silence);
      if ($r2!==self::$FAILED) {
        $r1[] = $r2;
      } else {
        break;
      }
    }
    if (count($r1) === 0) {
      $r1 = self::$FAILED;
    }
    // free $r2
    return $r1;
  }
  private function parselined_chunk($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $p5 = $this->currPos;
    $r4 = '';
    // l <- $r4
    if ($r4!==self::$FAILED) {
      $this->savedPos = $p5;
      $r4 = $this->a0();
    } else {
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r6 = $this->parsechunk($silence);
    // c <- $r6
    if ($r6===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a1($r4, $r6);
    }
    // free $p3
    return $r1;
  }
  private function parsechunk($silence) {
    // start choice_1
    $r1 = $this->parsecomment($silence);
    if ($r1!==self::$FAILED) {
      goto choice_1;
    }
    $r1 = $this->parsetest($silence);
    if ($r1!==self::$FAILED) {
      goto choice_1;
    }
    $p2 = $this->currPos;
    $r3 = $this->parseline($silence);
    // l <- $r3
    $r1 = $r3;
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a2($r3);
    }
    choice_1:
    return $r1;
  }
  private function parsecomment($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    if (($this->input[$this->currPos] ?? null) === "#") {
      $this->currPos++;
      $r4 = "#";
    } else {
      if (!$silence) {$this->fail(1);}
      $r4 = self::$FAILED;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = $this->parserest_of_line($silence);
    // text <- $r5
    if ($r5===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a3($r5);
    }
    // free $p3
    return $r1;
  }
  private function parsetest($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->discardstart_test($silence);
    if ($r4===self::$FAILED) {
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = $this->parsetext($silence);
    // testName <- $r5
    if ($r5===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r6 = [];
    for (;;) {
      // start choice_1
      $r7 = $this->parsesection($silence);
      if ($r7!==self::$FAILED) {
        goto choice_1;
      }
      $r7 = $this->parseoption_section($silence);
      choice_1:
      if ($r7!==self::$FAILED) {
        $r6[] = $r7;
      } else {
        break;
      }
    }
    // sections <- $r6
    // free $r7
    $r7 = $this->discardend_section($silence);
    if ($r7===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a4($r5, $r6);
    }
    // free $p3
    return $r1;
  }
  private function parseline($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $p4 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "!!", $this->currPos, 2, false) === 0) {
      $r5 = "!!";
      $this->currPos += 2;
    } else {
      $r5 = self::$FAILED;
    }
    if ($r5 === self::$FAILED) {
      $r5 = false;
    } else {
      $r5 = self::$FAILED;
      $this->currPos = $p4;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    // free $p4
    $r6 = $this->parserest_of_line($silence);
    // line <- $r6
    if ($r6===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a5($r6);
    }
    // free $p3
    return $r1;
  }
  private function parserest_of_line($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = [];
    for (;;) {
      $r5 = self::charAt($this->input, $this->currPos);
      if ($r5 !== '' && !($r5 === "\x0d" || $r5 === "\x0a")) {
        $this->currPos += strlen($r5);
        $r4[] = $r5;
      } else {
        $r5 = self::$FAILED;
        if (!$silence) {$this->fail(2);}
        break;
      }
    }
    // c <- $r4
    // free $r5
    $r5 = $this->discardeol($silence);
    if ($r5===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a6($r4);
    }
    // free $p3
    return $r1;
  }
  private function discardstart_test($silence) {
    // start seq_1
    $p1 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "!!", $this->currPos, 2, false) === 0) {
      $r3 = "!!";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(3);}
      $r3 = self::$FAILED;
      $r2 = self::$FAILED;
      goto seq_1;
    }
    $r4 = $this->discardwhitespace($silence);
    if ($r4===self::$FAILED) {
      $r4 = null;
    }
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "test", $this->currPos, 4, false) === 0) {
      $r5 = "test";
      $this->currPos += 4;
    } else {
      if (!$silence) {$this->fail(4);}
      $r5 = self::$FAILED;
      $this->currPos = $p1;
      $r2 = self::$FAILED;
      goto seq_1;
    }
    $r6 = $this->discardwhitespace($silence);
    if ($r6===self::$FAILED) {
      $r6 = null;
    }
    $r7 = $this->discardeol($silence);
    if ($r7===self::$FAILED) {
      $this->currPos = $p1;
      $r2 = self::$FAILED;
      goto seq_1;
    }
    $r2 = true;
    seq_1:
    // free $r2,$p1
    return $r2;
  }
  private function parsetext($silence) {
    $p2 = $this->currPos;
    $r3 = [];
    for (;;) {
      $r4 = $this->parseline($silence);
      if ($r4!==self::$FAILED) {
        $r3[] = $r4;
      } else {
        break;
      }
    }
    // lines <- $r3
    // free $r4
    $r1 = $r3;
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a7($r3);
    }
    return $r1;
  }
  private function parsesection($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "!!", $this->currPos, 2, false) === 0) {
      $r4 = "!!";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(3);}
      $r4 = self::$FAILED;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = $this->discardwhitespace($silence);
    if ($r5===self::$FAILED) {
      $r5 = null;
    }
    $p6 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "end", $this->currPos, 3, false) === 0) {
      $r7 = "end";
      $this->currPos += 3;
    } else {
      $r7 = self::$FAILED;
    }
    if ($r7 === self::$FAILED) {
      $r7 = false;
    } else {
      $r7 = self::$FAILED;
      $this->currPos = $p6;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    // free $p6
    $p6 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "options", $this->currPos, 7, false) === 0) {
      $r8 = "options";
      $this->currPos += 7;
    } else {
      $r8 = self::$FAILED;
    }
    if ($r8 === self::$FAILED) {
      $r8 = false;
    } else {
      $r8 = self::$FAILED;
      $this->currPos = $p6;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    // free $p6
    $p6 = $this->currPos;
    $r10 = [];
    for (;;) {
      if (strcspn($this->input, " \x09\x0d\x0a", $this->currPos, 1) !== 0) {
        $r11 = self::consumeChar($this->input, $this->currPos);
        $r10[] = $r11;
      } else {
        $r11 = self::$FAILED;
        if (!$silence) {$this->fail(5);}
        break;
      }
    }
    if (count($r10) === 0) {
      $r10 = self::$FAILED;
    }
    // c <- $r10
    // free $r11
    $r9 = $r10;
    // name <- $r9
    if ($r9!==self::$FAILED) {
      $this->savedPos = $p6;
      $r9 = $this->a8($r10);
    } else {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r11 = $this->discardrest_of_line($silence);
    if ($r11===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r12 = $this->parsetext($silence);
    // text <- $r12
    if ($r12===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a9($r9, $r12);
    }
    // free $p3
    return $r1;
  }
  private function parseoption_section($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "!!", $this->currPos, 2, false) === 0) {
      $r4 = "!!";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(3);}
      $r4 = self::$FAILED;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = $this->discardwhitespace($silence);
    if ($r5===self::$FAILED) {
      $r5 = null;
    }
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "options", $this->currPos, 7, false) === 0) {
      $r6 = "options";
      $this->currPos += 7;
    } else {
      if (!$silence) {$this->fail(6);}
      $r6 = self::$FAILED;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r7 = $this->discardwhitespace($silence);
    if ($r7===self::$FAILED) {
      $r7 = null;
    }
    $r8 = $this->discardeol($silence);
    if ($r8===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r9 = $this->parseoption_list($silence);
    if ($r9===self::$FAILED) {
      $r9 = null;
    }
    // opts <- $r9
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a10($r9);
    }
    // free $p3
    return $r1;
  }
  private function discardend_section($silence) {
    // start seq_1
    $p1 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "!!", $this->currPos, 2, false) === 0) {
      $r3 = "!!";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(3);}
      $r3 = self::$FAILED;
      $r2 = self::$FAILED;
      goto seq_1;
    }
    $r4 = $this->discardwhitespace($silence);
    if ($r4===self::$FAILED) {
      $r4 = null;
    }
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "end", $this->currPos, 3, false) === 0) {
      $r5 = "end";
      $this->currPos += 3;
    } else {
      if (!$silence) {$this->fail(7);}
      $r5 = self::$FAILED;
      $this->currPos = $p1;
      $r2 = self::$FAILED;
      goto seq_1;
    }
    $r6 = $this->discardwhitespace($silence);
    if ($r6===self::$FAILED) {
      $r6 = null;
    }
    $r7 = $this->discardeol($silence);
    if ($r7===self::$FAILED) {
      $this->currPos = $p1;
      $r2 = self::$FAILED;
      goto seq_1;
    }
    $r2 = true;
    seq_1:
    // free $r2,$p1
    return $r2;
  }
  private function discardeol($silence) {
    $p2 = $this->currPos;
    // start choice_1
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->input[$this->currPos] ?? '';
    if ($r4 === "\x0d") {
      $this->currPos++;
    } else {
      $r4 = self::$FAILED;
      if (!$silence) {$this->fail(8);}
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = $this->input[$this->currPos] ?? '';
    if ($r5 === "\x0a") {
      $this->currPos++;
    } else {
      $r5 = self::$FAILED;
      if (!$silence) {$this->fail(9);}
      $r5 = null;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      goto choice_1;
    }
    // free $p3
    if (($this->input[$this->currPos] ?? null) === "\x0a") {
      $this->currPos++;
      $r1 = "\x0a";
    } else {
      if (!$silence) {$this->fail(10);}
      $r1 = self::$FAILED;
    }
    choice_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a11();
    }
    return $r1;
  }
  private function discardwhitespace($silence) {
    $r1 = self::$FAILED;
    for (;;) {
      $r2 = $this->input[$this->currPos] ?? '';
      if ($r2 === " " || $r2 === "\x09") {
        $this->currPos++;
        $r1 = true;
      } else {
        $r2 = self::$FAILED;
        if (!$silence) {$this->fail(11);}
        break;
      }
    }
    // free $r2
    return $r1;
  }
  private function discardrest_of_line($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = [];
    for (;;) {
      $r5 = self::charAt($this->input, $this->currPos);
      if ($r5 !== '' && !($r5 === "\x0d" || $r5 === "\x0a")) {
        $this->currPos += strlen($r5);
        $r4[] = $r5;
      } else {
        $r5 = self::$FAILED;
        if (!$silence) {$this->fail(2);}
        break;
      }
    }
    // c <- $r4
    // free $r5
    $r5 = $this->discardeol($silence);
    if ($r5===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a6($r4);
    }
    // free $p3
    return $r1;
  }
  private function parseoption_list($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->parsean_option($silence);
    // o <- $r4
    if ($r4===self::$FAILED) {
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = self::$FAILED;
    for (;;) {
      // start choice_1
      $r6 = $this->input[$this->currPos] ?? '';
      if ($r6 === " " || $r6 === "\x09") {
        $this->currPos++;
        goto choice_1;
      } else {
        $r6 = self::$FAILED;
        if (!$silence) {$this->fail(11);}
      }
      $r6 = $this->discardeol($silence);
      choice_1:
      if ($r6!==self::$FAILED) {
        $r5 = true;
      } else {
        break;
      }
    }
    if ($r5===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    // free $r6
    $r6 = $this->parseoption_list($silence);
    if ($r6===self::$FAILED) {
      $r6 = null;
    }
    // rest <- $r6
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a12($r4, $r6);
    }
    // free $p3
    return $r1;
  }
  private function parsean_option($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->parseoption_name($silence);
    // k <- $r4
    if ($r4===self::$FAILED) {
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = $this->parseoption_value($silence);
    if ($r5===self::$FAILED) {
      $r5 = null;
    }
    // v <- $r5
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a13($r4, $r5);
    }
    // free $p3
    return $r1;
  }
  private function parseoption_name($silence) {
    $p2 = $this->currPos;
    $r3 = [];
    for (;;) {
      if (strcspn($this->input, " \x09\x0d\x0a=!", $this->currPos, 1) !== 0) {
        $r4 = self::consumeChar($this->input, $this->currPos);
        $r3[] = $r4;
      } else {
        $r4 = self::$FAILED;
        if (!$silence) {$this->fail(12);}
        break;
      }
    }
    if (count($r3) === 0) {
      $r3 = self::$FAILED;
    }
    // c <- $r3
    // free $r4
    $r1 = $r3;
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a6($r3);
    }
    return $r1;
  }
  private function parseoption_value($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->discardwhitespace($silence);
    if ($r4===self::$FAILED) {
      $r4 = null;
    }
    if (($this->input[$this->currPos] ?? null) === "=") {
      $this->currPos++;
      $r5 = "=";
    } else {
      if (!$silence) {$this->fail(13);}
      $r5 = self::$FAILED;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r6 = $this->discardwhitespace($silence);
    if ($r6===self::$FAILED) {
      $r6 = null;
    }
    $r7 = $this->parseoption_value_list($silence);
    // ovl <- $r7
    if ($r7===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a14($r7);
    }
    // free $p3
    return $r1;
  }
  private function parseoption_value_list($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->parsean_option_value($silence);
    // v <- $r4
    if ($r4===self::$FAILED) {
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $p6 = $this->currPos;
    // start seq_2
    $p7 = $this->currPos;
    $r8 = $this->discardwhitespace($silence);
    if ($r8===self::$FAILED) {
      $r8 = null;
    }
    if (($this->input[$this->currPos] ?? null) === ",") {
      $this->currPos++;
      $r9 = ",";
    } else {
      if (!$silence) {$this->fail(14);}
      $r9 = self::$FAILED;
      $this->currPos = $p7;
      $r5 = self::$FAILED;
      goto seq_2;
    }
    $r10 = $this->discardwhitespace($silence);
    if ($r10===self::$FAILED) {
      $r10 = null;
    }
    $r11 = $this->parseoption_value_list($silence);
    // ovl <- $r11
    if ($r11===self::$FAILED) {
      $this->currPos = $p7;
      $r5 = self::$FAILED;
      goto seq_2;
    }
    $r5 = true;
    seq_2:
    if ($r5!==self::$FAILED) {
      $this->savedPos = $p6;
      $r5 = $this->a15($r4, $r11);
    } else {
      $r5 = null;
    }
    // free $p7
    // rest <- $r5
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a16($r4, $r5);
    }
    // free $p3
    return $r1;
  }
  private function parsean_option_value($silence) {
    $p2 = $this->currPos;
    // start choice_1
    $r3 = $this->parselink_target_value($silence);
    if ($r3!==self::$FAILED) {
      goto choice_1;
    }
    $r3 = $this->parsequoted_value($silence);
    if ($r3!==self::$FAILED) {
      goto choice_1;
    }
    $r3 = $this->parseplain_value($silence);
    if ($r3!==self::$FAILED) {
      goto choice_1;
    }
    $r3 = $this->parsejson_value($silence);
    choice_1:
    // v <- $r3
    $r1 = $r3;
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a17($r3);
    }
    return $r1;
  }
  private function parselink_target_value($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "[[", $this->currPos, 2, false) === 0) {
      $r4 = "[[";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(15);}
      $r4 = self::$FAILED;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = [];
    for (;;) {
      if (strcspn($this->input, "]\x0d\x0a", $this->currPos, 1) !== 0) {
        $r6 = self::consumeChar($this->input, $this->currPos);
        $r5[] = $r6;
      } else {
        $r6 = self::$FAILED;
        if (!$silence) {$this->fail(16);}
        break;
      }
    }
    // v <- $r5
    // free $r6
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "]]", $this->currPos, 2, false) === 0) {
      $r6 = "]]";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(17);}
      $r6 = self::$FAILED;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a18($r5);
    }
    // free $p3
    return $r1;
  }
  private function parsequoted_value($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->input[$this->currPos] ?? '';
    if ($r4 === "\"") {
      $this->currPos++;
    } else {
      $r4 = self::$FAILED;
      if (!$silence) {$this->fail(18);}
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = [];
    for (;;) {
      // start choice_1
      if (strcspn($this->input, "\\\"\x0d\x0a", $this->currPos, 1) !== 0) {
        $r6 = self::consumeChar($this->input, $this->currPos);
        goto choice_1;
      } else {
        $r6 = self::$FAILED;
        if (!$silence) {$this->fail(19);}
      }
      $p7 = $this->currPos;
      // start seq_2
      $p8 = $this->currPos;
      if (($this->input[$this->currPos] ?? null) === "\\") {
        $this->currPos++;
        $r9 = "\\";
      } else {
        if (!$silence) {$this->fail(20);}
        $r9 = self::$FAILED;
        $r6 = self::$FAILED;
        goto seq_2;
      }
      $r10 = self::charAt($this->input, $this->currPos);
      // c <- $r10
      if ($r10 !== '' && !($r10 === "\x0d" || $r10 === "\x0a")) {
        $this->currPos += strlen($r10);
      } else {
        $r10 = self::$FAILED;
        if (!$silence) {$this->fail(2);}
        $this->currPos = $p8;
        $r6 = self::$FAILED;
        goto seq_2;
      }
      $r6 = true;
      seq_2:
      if ($r6!==self::$FAILED) {
        $this->savedPos = $p7;
        $r6 = $this->a19($r10);
      }
      // free $p8
      choice_1:
      if ($r6!==self::$FAILED) {
        $r5[] = $r6;
      } else {
        break;
      }
    }
    // v <- $r5
    // free $r6
    $r6 = $this->input[$this->currPos] ?? '';
    if ($r6 === "\"") {
      $this->currPos++;
    } else {
      $r6 = self::$FAILED;
      if (!$silence) {$this->fail(18);}
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a20($r5);
    }
    // free $p3
    return $r1;
  }
  private function parseplain_value($silence) {
    $p2 = $this->currPos;
    $r3 = [];
    for (;;) {
      if (strcspn($this->input, " \x09\x0d\x0a\"'[]=,!{", $this->currPos, 1) !== 0) {
        $r4 = self::consumeChar($this->input, $this->currPos);
        $r3[] = $r4;
      } else {
        $r4 = self::$FAILED;
        if (!$silence) {$this->fail(21);}
        break;
      }
    }
    if (count($r3) === 0) {
      $r3 = self::$FAILED;
    }
    // v <- $r3
    // free $r4
    $r1 = $r3;
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a21($r3);
    }
    return $r1;
  }
  private function parsejson_value($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    if (($this->input[$this->currPos] ?? null) === "{") {
      $this->currPos++;
      $r4 = "{";
    } else {
      if (!$silence) {$this->fail(22);}
      $r4 = self::$FAILED;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = [];
    for (;;) {
      // start choice_1
      if (strcspn($this->input, "\"{}\x0d\x0a", $this->currPos, 1) !== 0) {
        $r6 = self::consumeChar($this->input, $this->currPos);
        goto choice_1;
      } else {
        $r6 = self::$FAILED;
        if (!$silence) {$this->fail(23);}
      }
      $r6 = $this->parsequoted_value($silence);
      if ($r6!==self::$FAILED) {
        goto choice_1;
      }
      $r6 = $this->parsejson_value($silence);
      if ($r6!==self::$FAILED) {
        goto choice_1;
      }
      $r6 = $this->parseeol($silence);
      choice_1:
      if ($r6!==self::$FAILED) {
        $r5[] = $r6;
      } else {
        break;
      }
    }
    // v <- $r5
    // free $r6
    if (($this->input[$this->currPos] ?? null) === "}") {
      $this->currPos++;
      $r6 = "}";
    } else {
      if (!$silence) {$this->fail(24);}
      $r6 = self::$FAILED;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a22($r5);
    }
    // free $p3
    return $r1;
  }
  private function parseeol($silence) {
    $p2 = $this->currPos;
    // start choice_1
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->input[$this->currPos] ?? '';
    if ($r4 === "\x0d") {
      $this->currPos++;
    } else {
      $r4 = self::$FAILED;
      if (!$silence) {$this->fail(8);}
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = $this->input[$this->currPos] ?? '';
    if ($r5 === "\x0a") {
      $this->currPos++;
    } else {
      $r5 = self::$FAILED;
      if (!$silence) {$this->fail(9);}
      $r5 = null;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      goto choice_1;
    }
    // free $p3
    if (($this->input[$this->currPos] ?? null) === "\x0a") {
      $this->currPos++;
      $r1 = "\x0a";
    } else {
      if (!$silence) {$this->fail(10);}
      $r1 = self::$FAILED;
    }
    choice_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a11();
    }
    return $r1;
  }

  public function parse($input, $options = []) {
    $this->initInternal($input, $options);
    $startRule = $options['startRule'] ?? '(DEFAULT)';
    $result = null;

    if (!empty($options['stream'])) {
      switch ($startRule) {
        
        default:
          throw new \WikiPEG\InternalError("Can't stream rule $startRule.");
      }
    } else {
      switch ($startRule) {
        case '(DEFAULT)':
        case "testfile":
          $result = $this->parsetestfile(false);
          break;
        default:
          throw new \WikiPEG\InternalError("Can't start parsing from rule $startRule.");
      }
    }

    if ($result !== self::$FAILED && $this->currPos === $this->inputLength) {
      return $result;
    } else {
      if ($result !== self::$FAILED && $this->currPos < $this->inputLength) {
        $this->fail(0);
      }
      throw $this->buildParseException();
    }
  }
}

