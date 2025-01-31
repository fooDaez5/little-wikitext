Code walkthough
===============

The code is organized in the following directories:
* [`bin`](./bin): Contains `markup2html`, a simple CLI tool to exercise the
  code.
* [`build`](./build): Contains tools used to generate documentation or support
  [repl.it]; it can be ignored.
* [`src`](./src): **Main source code for the library**
* [`tests`](./tests): [PHPUnit] test suite; in particular contains
  [`parserTests.txt`] (see below)
* [`tests/ParserTests`](./tests/ParserTests): Contains tools to parse
  [`parserTests.txt`]; you shouldn't need to dig into this.
* `vendor`: Third-party libraries managed by `composer`

The most important code to read is in `src/` and `tests/parserTests.txt`.

The code in `src/` falls into the following groups:

### Markup grammar
* [`Grammar.pegphp`] and [`Grammar`]: These contain a [wikipeg] grammar for
  the markup language ([`Grammar.pegphp`] is compiled into `Grammar.php`).
  This grammar builds an
  [Abstract Syntax Tree](https://en.wikipedia.org/wiki/Abstract_syntax_tree)
  from the input markup. **Understanding or modifying this code is not
  necessary to complete the tasks**.

### Abstract Syntax Tree (AST)
* [`Node`]: base class for the AST
* [`LeafNode`]: extends [`Node`] but is primarily a marker class to indicate
  nodes without children.
* [`Root`]: the single root node of the AST.  The Root will contain only
  [`Section`] nodes.
* [`Section`]: A (named or unnamed) section.  A Section starts with
  an optional [`Heading`], and contains only [`ListItem`] and [`Paragraph`]
  nodes as children.
* [`Heading`], [`ListItem`], [`Paragraph`]: contain inline content as children
* [`Inclusion`], [`Link`], [`Text`]: inline content.
  `Inclusion` and `Text` are leaf nodes.

### Visitor pattern
* [`Visitor`] defines a double-dispatch
  [visitor pattern](https://en.wikipedia.org/wiki/Visitor_pattern)
* [`TransformVisitor`]: a helper class providing default visitor behavior to
  make it easier to write AST-to-AST transformations.
* [`TraversalVisitor`]: a helper class providing default visitor behavior to
  make it easier to write visitors which traverse and analyze the AST.

### HTML and markup conversions
* [`ToHtmlVisitor`]: A visitor to convert an AST to HTML
* [`ToMarkupVisitor`]: A visitor to convert an AST back to source markup
* [`SectionMap`]: helper class to map section names to [`Section`] nodes
* [`LittleWikitext`]: An entry point class

### Testing infrastructure ###
* [`ValidHtmlTest`]: contains some helper functions to define and
  determine "HTML validity".  This can be surprisingly subtle to define
  properly, so we've provided an executable definition; there are comments
  inside the class discussing some of the choices made.
* [`LittleWikitextTest`]: a [PHPUnit] test case which simply runs the
  tests defined in [`parserTests.txt`].  There are comments in here
  to help you hookup your new functionality.
* [`parserTests.txt`]: collects specific test case input and outputs.
  You should find it easy to add new test cases in this file.

Usage
-----

The main entry point is found in `src/LittleWikitext.php`.  There is
a command-line script you can use to run short examples:

```sh
$ build/helper bin/markup2html sample.markup
````

But mainly you'll be running tests.

Running tests
-------------

To check that your development environment is properly set up, run:

    composer initial-test

This should pass with no errors in a fresh checkout of this library.

To run the main test suite, use:

    composer test

which runs the tests found in
[tests/parserTests.txt](./tests/parserTests.txt).  It will initially
show one failure; fixing this bug is your first task.

If you are using [repl.it], you can use the "Run" button to execute
this test suite.

There are also code style tests ([`phpcs`]) and static analysis tests
([`phan`], which sometimes gives false positives).  These are likely a
waste of time for these tasks, especially given the time constraints,
and your submission won't be judged against them.  These are run from
the `full-test` target.  Most code style issues can be automatically
fixed with the `fix` target.

Rebuilding the parser
---------------------
You will not need to modify or rebuild the parser for any of the tasks.

The parser is built using [wikipeg], a [PEG] parser generator.
Running wikipeg requires [node](https://nodejs.org/en/).

    npm install  # just once
    npm run wikipeg

---
[repl.it]: https://repl.it
[wikipeg]: https://www.npmjs.com/package/wikipeg
[PEG]: https://en.wikipedia.org/wiki/Parsing_expression_grammar
[`parserTests.txt`]: ./tests/parserTests.txt
[`Grammar.pegphp`]: ./src/Grammar.pegphp
[`Grammar`]: ./src/Grammar.php
[`Node`]: ./src/Node.php
[`LeafNode`]: ./src/LeafNode.php
[`Root`]: ./src/Root.php
[`Section`]: ./src/Section.php
[`Heading`]: ./src/Heading.php
[`ListItem`]: ./src/ListItem.php
[`Paragraph`]: ./src/Paragraph.php
[`Inclusion`]: ./src/Inclusion.php
[`Link`]: ./src/Link.php
[`Text`]: ./src/Text.php
[`Visitor`]: ./src/Visitor.php
[`TraversalVisitor`]: ./src/TraversalVisitor.php
[`TransformVisitor`]: ./src/TransformVisitor.php
[`ToHtmlVisitor`]: ./src/ToHtmlVisitor.php
[`ToMarkupVisitor`]: ./src/ToMarkupVisitor.php
[`SectionMap`]: ./src/SectionMap.php
[`LittleWikitext`]: ./src/LittleWikitext.php
[`ValidHtmlTest`]: ./tests/ValidHtmlTest.php
[`LittleWikitextTest`]: ./tests/LittleWikitextTest.php
[PHPUnit]: https://phpunit.de/documentation.html
[`phpcs`]: https://github.com/squizlabs/PHP_CodeSniffer
[`phan`]: https://github.com/phan/phan/wiki
[composer]: https://getcomposer.org/
