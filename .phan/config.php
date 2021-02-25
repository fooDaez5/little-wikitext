<?php
$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['directory_list'] = [
	'src',
	'tests',
	'vendor/wikimedia',
	'.phan/stubs',
];
$cfg['exclude_analysis_directory_list'][] = 'vendor/wikimedia';
$cfg['suppress_issue_types'] = [];

# Exclude peg-generated output
$cfg['exclude_file_list'][] = "src/Grammar.php";
$cfg['exclude_file_list'][] = "tests/ParserTests/Grammar.php";

return $cfg;
