<?php




/* File-scope initializer */
namespace Wikimedia\LittleWikitext;


class Grammar extends \WikiPEG\PEGParserBase {
  // initializer
  
  	/**
  	 * @param string $contents
  	 * @return Root
  	 */
  	public static function load( string $contents ): Root {
  		$g = new Grammar();
  		// Ensure every line is terminated with a newline
  		$ast = $g->parse( "$contents\n" );
  		// But strip the trailing blank line if one was added
  		$children = $ast->getChildren();
  		$last = $children[count($children)-1];
  		if ($last instanceof Paragraph && $last->toWikitext() === '') {
  			array_pop($children);
  			return new Root($children);
  		}
  		return $ast;
  	}
  

  // cache init
  

  // expectations
  protected $expectations = [
    0 => ["type" => "end", "description" => "end of input"],
    1 => ["type" => "literal", "value" => "==", "description" => "\"==\""],
    2 => ["type" => "literal", "value" => "*", "description" => "\"*\""],
    3 => ["type" => "class", "value" => "[\\r]", "description" => "[\\r]"],
    4 => ["type" => "class", "value" => "[\\n]", "description" => "[\\n]"],
    5 => ["type" => "literal", "value" => "\x0a", "description" => "\"\\n\""],
    6 => ["type" => "literal", "value" => "[[", "description" => "\"[[\""],
    7 => ["type" => "literal", "value" => "|", "description" => "\"|\""],
    8 => ["type" => "literal", "value" => "]]", "description" => "\"]]\""],
    9 => ["type" => "literal", "value" => "{{", "description" => "\"{{\""],
    10 => ["type" => "literal", "value" => "}}", "description" => "\"}}\""],
    11 => ["type" => "class", "value" => "[^\\n\\r]", "description" => "[^\\n\\r]"],
  ];

  // actions
  private function a0($children) {
  
  	return new Root( $children );
  
  }
  private function a1($t) {
   return $t; 
  }
  private function a2($txt) {
  
  	return new Heading( Node::trim( $txt ) );
  
  }
  private function a3($level, $txt) {
  
  	if ( count( $level ) === 0 ) {
  		// Plain text, not a list item
  		return new Paragraph( Node::normalize( $txt ) );
  	} else {
  		return new ListItem( count( $level ), Node::trim( $txt ) );
  	}
  
  }
  private function a4($c) {
   return new Text( $c ); 
  }
  private function a5($c1) {
   return $c1; 
  }
  private function a6($target, $c2) {
   return $c2; 
  }
  private function a7($target, $caption) {
  
  	$target = implode( '', $target );
  	return new Link( $target, $caption );
  
  }
  private function a8($c) {
   return $c; 
  }
  private function a9($target) {
  
  	$target = implode( '', $target );
  	return new Inclusion( $target );
  
  }

  // generated
  private function parsestart($silence) {
    $p2 = $this->currPos;
    $r3 = [];
    for (;;) {
      // start choice_1
      $r4 = $this->parseheading($silence);
      if ($r4!==self::$FAILED) {
        goto choice_1;
      }
      $r4 = $this->parselist($silence);
      choice_1:
      if ($r4!==self::$FAILED) {
        $r3[] = $r4;
      } else {
        break;
      }
    }
    // children <- $r3
    // free $r4
    $r1 = $r3;
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a0($r3);
    }
    return $r1;
  }
  private function parseheading($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->discardsol($silence);
    if ($r4===self::$FAILED) {
      $r1 = self::$FAILED;
      goto seq_1;
    }
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "==", $this->currPos, 2, false) === 0) {
      $r5 = "==";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(1);}
      $r5 = self::$FAILED;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r6 = [];
    for (;;) {
      $p8 = $this->currPos;
      // start seq_2
      $p9 = $this->currPos;
      $p10 = $this->currPos;
      if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "==", $this->currPos, 2, false) === 0) {
        $r11 = "==";
        $this->currPos += 2;
      } else {
        $r11 = self::$FAILED;
      }
      if ($r11 === self::$FAILED) {
        $r11 = false;
      } else {
        $r11 = self::$FAILED;
        $this->currPos = $p10;
        $r7 = self::$FAILED;
        goto seq_2;
      }
      // free $p10
      $r12 = $this->parseinline($silence);
      // t <- $r12
      if ($r12===self::$FAILED) {
        $this->currPos = $p9;
        $r7 = self::$FAILED;
        goto seq_2;
      }
      $r7 = true;
      seq_2:
      if ($r7!==self::$FAILED) {
        $this->savedPos = $p8;
        $r7 = $this->a1($r12);
        $r6[] = $r7;
      } else {
        break;
      }
      // free $p9
    }
    if (count($r6) === 0) {
      $r6 = self::$FAILED;
    }
    // txt <- $r6
    if ($r6===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    // free $r7
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "==", $this->currPos, 2, false) === 0) {
      $r7 = "==";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(1);}
      $r7 = self::$FAILED;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r13 = $this->discardeol($silence);
    if ($r13===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a2($r6);
    }
    // free $p3
    return $r1;
  }
  private function parselist($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    $r4 = $this->discardsol($silence);
    if ($r4===self::$FAILED) {
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $p5 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "==", $this->currPos, 2, false) === 0) {
      $r6 = "==";
      $this->currPos += 2;
    } else {
      $r6 = self::$FAILED;
    }
    if ($r6 === self::$FAILED) {
      $r6 = false;
    } else {
      $r6 = self::$FAILED;
      $this->currPos = $p5;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    // free $p5
    $r7 = [];
    for (;;) {
      if (($this->input[$this->currPos] ?? null) === "*") {
        $this->currPos++;
        $r8 = "*";
        $r7[] = $r8;
      } else {
        if (!$silence) {$this->fail(2);}
        $r8 = self::$FAILED;
        break;
      }
    }
    // level <- $r7
    // free $r8
    $r8 = [];
    for (;;) {
      $r9 = $this->parseinline($silence);
      if ($r9!==self::$FAILED) {
        $r8[] = $r9;
      } else {
        break;
      }
    }
    // txt <- $r8
    // free $r9
    $r9 = $this->discardeol($silence);
    if ($r9===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a3($r7, $r8);
    }
    // free $p3
    return $r1;
  }
  private function discardsol($silence) {
  
    return '';
  }
  private function parseinline($silence) {
    // start choice_1
    $r1 = $this->parselink($silence);
    if ($r1!==self::$FAILED) {
      goto choice_1;
    }
    $r1 = $this->parsetemplate($silence);
    if ($r1!==self::$FAILED) {
      goto choice_1;
    }
    $p2 = $this->currPos;
    $r3 = $this->parsenotnl($silence);
    // c <- $r3
    $r1 = $r3;
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a4($r3);
    }
    choice_1:
    return $r1;
  }
  private function discardeol($silence) {
    // start choice_1
    // start seq_1
    $p2 = $this->currPos;
    $r3 = $this->input[$this->currPos] ?? '';
    if ($r3 === "\x0d") {
      $this->currPos++;
    } else {
      $r3 = self::$FAILED;
      if (!$silence) {$this->fail(3);}
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r4 = $this->input[$this->currPos] ?? '';
    if ($r4 === "\x0a") {
      $this->currPos++;
    } else {
      $r4 = self::$FAILED;
      if (!$silence) {$this->fail(4);}
      $r4 = null;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      goto choice_1;
    }
    // free $p2
    if (($this->input[$this->currPos] ?? null) === "\x0a") {
      $this->currPos++;
      $r1 = "\x0a";
    } else {
      if (!$silence) {$this->fail(5);}
      $r1 = self::$FAILED;
    }
    choice_1:
    return $r1;
  }
  private function parselink($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "[[", $this->currPos, 2, false) === 0) {
      $r4 = "[[";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(6);}
      $r4 = self::$FAILED;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = [];
    for (;;) {
      $p7 = $this->currPos;
      // start seq_2
      $p8 = $this->currPos;
      $p9 = $this->currPos;
      if (($this->input[$this->currPos] ?? null) === "|") {
        $this->currPos++;
        $r10 = "|";
      } else {
        $r10 = self::$FAILED;
      }
      if ($r10 === self::$FAILED) {
        $r10 = false;
      } else {
        $r10 = self::$FAILED;
        $this->currPos = $p9;
        $r6 = self::$FAILED;
        goto seq_2;
      }
      // free $p9
      $r11 = $this->parsenotnl($silence);
      // c1 <- $r11
      if ($r11===self::$FAILED) {
        $this->currPos = $p8;
        $r6 = self::$FAILED;
        goto seq_2;
      }
      $r6 = true;
      seq_2:
      if ($r6!==self::$FAILED) {
        $this->savedPos = $p7;
        $r6 = $this->a5($r11);
        $r5[] = $r6;
      } else {
        break;
      }
      // free $p8
    }
    if (count($r5) === 0) {
      $r5 = self::$FAILED;
    }
    // target <- $r5
    if ($r5===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    // free $r6
    if (($this->input[$this->currPos] ?? null) === "|") {
      $this->currPos++;
      $r6 = "|";
    } else {
      if (!$silence) {$this->fail(7);}
      $r6 = self::$FAILED;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r12 = [];
    for (;;) {
      $p8 = $this->currPos;
      // start seq_3
      $p9 = $this->currPos;
      $p14 = $this->currPos;
      // start choice_1
      if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "[[", $this->currPos, 2, false) === 0) {
        $r15 = "[[";
        $this->currPos += 2;
        goto choice_1;
      } else {
        $r15 = self::$FAILED;
      }
      if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "]]", $this->currPos, 2, false) === 0) {
        $r15 = "]]";
        $this->currPos += 2;
      } else {
        $r15 = self::$FAILED;
      }
      choice_1:
      if ($r15 === self::$FAILED) {
        $r15 = false;
      } else {
        $r15 = self::$FAILED;
        $this->currPos = $p14;
        $r13 = self::$FAILED;
        goto seq_3;
      }
      // free $p14
      $r16 = $this->parseinline($silence);
      // c2 <- $r16
      if ($r16===self::$FAILED) {
        $this->currPos = $p9;
        $r13 = self::$FAILED;
        goto seq_3;
      }
      $r13 = true;
      seq_3:
      if ($r13!==self::$FAILED) {
        $this->savedPos = $p8;
        $r13 = $this->a6($r5, $r16);
        $r12[] = $r13;
      } else {
        break;
      }
      // free $p9
    }
    // caption <- $r12
    // free $r13
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "]]", $this->currPos, 2, false) === 0) {
      $r13 = "]]";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(8);}
      $r13 = self::$FAILED;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a7($r5, $r12);
    }
    // free $p3
    return $r1;
  }
  private function parsetemplate($silence) {
    $p2 = $this->currPos;
    // start seq_1
    $p3 = $this->currPos;
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "{{", $this->currPos, 2, false) === 0) {
      $r4 = "{{";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(9);}
      $r4 = self::$FAILED;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r5 = [];
    for (;;) {
      $p7 = $this->currPos;
      // start seq_2
      $p8 = $this->currPos;
      $p9 = $this->currPos;
      if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "}}", $this->currPos, 2, false) === 0) {
        $r10 = "}}";
        $this->currPos += 2;
      } else {
        $r10 = self::$FAILED;
      }
      if ($r10 === self::$FAILED) {
        $r10 = false;
      } else {
        $r10 = self::$FAILED;
        $this->currPos = $p9;
        $r6 = self::$FAILED;
        goto seq_2;
      }
      // free $p9
      $r11 = $this->parsenotnl($silence);
      // c <- $r11
      if ($r11===self::$FAILED) {
        $this->currPos = $p8;
        $r6 = self::$FAILED;
        goto seq_2;
      }
      $r6 = true;
      seq_2:
      if ($r6!==self::$FAILED) {
        $this->savedPos = $p7;
        $r6 = $this->a8($r11);
        $r5[] = $r6;
      } else {
        break;
      }
      // free $p8
    }
    if (count($r5) === 0) {
      $r5 = self::$FAILED;
    }
    // target <- $r5
    if ($r5===self::$FAILED) {
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    // free $r6
    if ($this->currPos >= $this->inputLength ? false : substr_compare($this->input, "}}", $this->currPos, 2, false) === 0) {
      $r6 = "}}";
      $this->currPos += 2;
    } else {
      if (!$silence) {$this->fail(10);}
      $r6 = self::$FAILED;
      $this->currPos = $p3;
      $r1 = self::$FAILED;
      goto seq_1;
    }
    $r1 = true;
    seq_1:
    if ($r1!==self::$FAILED) {
      $this->savedPos = $p2;
      $r1 = $this->a9($r5);
    }
    // free $p3
    return $r1;
  }
  private function parsenotnl($silence) {
    $r1 = self::charAt($this->input, $this->currPos);
    if ($r1 !== '' && !($r1 === "\x0a" || $r1 === "\x0d")) {
      $this->currPos += strlen($r1);
    } else {
      $r1 = self::$FAILED;
      if (!$silence) {$this->fail(11);}
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
        case "start":
          $result = $this->parsestart(false);
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

