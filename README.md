[![Run on Repl.it](https://repl.it/badge/github/fooDaez5/little-wikitext)](https://repl.it/github/fooDaez5/little-wikitext)

LittleWikitext
=====================

LittleWikitext is a simple parser for something not unlike wikitext.

Markup language and HTML output
-------------------------------
Informal markup spec:
* A document has a set of sections, as well as possibly an unnamed initial
section.
* Each section is made up of a heading and some content.  The content can be
list items of various nesting depths, or paragraphs of text.  There are also
two types of inline content: links and inclusions.
* The heading is marked up as below. Whitespace around the heading is ignored.
```
== TODO HEADING ==
```
* A list item is marked up as given below. Whitespace after the star(s) is ignored.
```
* this is a list item
** this is a nested list item
```
* A link looks like this: `[[Target|caption]]`
* An inclusion looks like this: `{{Target}}`

See [`sample.markup`](./sample.markup) for an example.

Informal HTML output spec:
* Sections are wrapped in a `<section>` tag
* Headings are wrapped in a `<h2>` tag
* Lists are generated using `<ul>` lists
* Paragraphs are surrounded by `<p>` tags
* Links correspond to `<a>` tags, with the target in the `href` attribute
* Inclusions correspond to empty `<template>` tags, with the target in the
`id` attribute

See [`sample.html`](./sample.html) for the output given for the sample markup.

Code
----
The code you will need is in [`src`], `tests`/[`LittleWikitextTest.php`], and
`tests`/[`parserTests.txt`].  There is an in-depth walkthough in
[WALKTHROUGH.md](./WALKTHROUGH.md).

Tasks
-----
There are two tasks to complete.  We do not expect you to spend more
than 60 minutes on these.  You will be evaluated primarily on task 1,
your test cases for task 2, and your ability to discuss the design
issues during the followup technical interview, not on the quantity of
code you can write in 60 minutes.

### Task 1

The command `build/helper composer test` will run a test suite, which
will fail with one error.  The test cases can be found in
`tests`/[`parserTests.txt`].  Fix the code so that the test passes.

### Task 2

We would like to implement **inclusions**: for every instance of
`{{Foo}}` in the input wikitext, we should replace it in the output
with the contents of the section titled `Foo`.
XXX improve the wording here, don't assume Foo is a metavariable XXX

Please provide *test cases* which demonstrate the behavior of the new
feature.  If you have time, you may implement code to make some or all
of the tests pass, but you should consider the implementation mostly
as an aid to writing good tests and thinking through the specification
issues.  Be sure your test cases demonstrate not just basic
functionality but also interesting corner cases.

Please ensure that the output you expect is always valid HTML; we’ve
provided code in
[`tests/ValidHtmlTest.php`](./tests/ValidHtmlTest.php) to help you
check.  This will entail some choices about how certain constructs are
rendered; be prepared to describe and justify your choices.

You may also find the code in [`SectionMap`] useful.

We will note that our markup language, like wikitext, is made for
humans not machines: simply crashing with a "syntax error" message is
not a good idea. (Crashing without a message is even worse!)  It is
often helpful to honor the intent of the author of the markup and
provide output that is useful to readers, even if the markup input is
“incorrect”. Similarly, consider how to maximize the expressiveness of
your markup language and the power of the inclusion mechanism. There
isn’t a single “right” answer, so be prepared to discuss your choices
during the followup interview, especially alternatives that differ
from your submitted implementation.

### Wrapping up

Please do not fork the provided github repository or submit pull
requests, as they may be visible to other applicants.

Instead zip or tar up the code directory and upload it to greenhouse.
If you are using [repl.it](https://repl.it/), the "three vertical
dots" menu at the top right of the Files panel has an option for
‘Download as zip’ which should work.

We are aware the time constraint won’t permit much polish.
This tech task is ultimately about communication, both reading
and writing.  We’re not going to put your submission into production,
but we *are* going to read it and talk to you about it during the
followup interview.

License
-------

This code is distributed under the MIT license; see
[LICENSE](./LICENSE) for more info.

---
[repl.it]: https://repl.it
[wikipeg]: https://www.npmjs.com/package/wikipeg
[PEG]: https://en.wikipedia.org/wiki/Parsing_expression_grammar
[`src`]: ./src
[`parserTests.txt`]: ./tests/parserTests.txt
[`LittleWikitextTest.php`]: ./tests/LittleWikitextTest.php
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
