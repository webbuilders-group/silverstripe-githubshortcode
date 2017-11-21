<?php
use SilverStripe\View\Parsers\ShortcodeParser;
use WebbuildersGroup\GitHubShortCode\GitHubShortCode;

define('GITHUBSHORTCODE_BASE', basename(dirname(__FILE__)));

//Enable the parser
ShortcodeParser::get_active()->register('github', array(GitHubShortCode::class, 'parse'));
?>