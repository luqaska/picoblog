<?php

/**
 * [Modified] Slimdown - A very basic regex-based Markdown parser.
 * Author: Johnny Broadway <johnny@johnnybroadway.com>
 * Website: https://gist.github.com/jbroadway/2836900
 * License: MIT
 */
class Slimdown {
	public static $rules = array (
		'/(?<!!)\[([^\[]+)\]\(([^\)]+)\)/' => '<a class=\'l\' href=\'\2\'>\1</a>',  // links
		'/(\*\*|__)(.*?)\1/' => '<strong>\2</strong>',            // bold
		'/(\*|_)(.*?)\1/' => '<em>\2</em>',                       // emphasis
		'/\~\~(.*?)\~\~/' => '<del>\1</del>',                     // del
		'/\:\"(.*?)\"\:/' => '<q>\1</q>',                         // quote
		'/`(.*?)`/' => '<code>\1</code>',                         // inline code
		'/(?:!\[([^\[]+)\]\(([^\)]+)\))/' => '<br><a href=\'\2\' target=\'\_blank\'><img class=\'cimg\' src=\'\2\' alt=\'\1\' loading=\'lazy\'></a>',
		'/==(.*?)==/' => '<mark>\1</mark>',
    '/#(\w+)?/' => '<a class="l" href="?tag=$1" class="tag">#${1}</a>',
	);

	/**
	 * Add a rule.
	 */
	public static function add_rule ($regex, $replacement) {
		self::$rules[$regex] = $replacement;
	}

	/**
	 * Render some Markdown into HTML.
	 */
	public static function render ($text) {
		$text = "\n" . $text . "\n";
		foreach (self::$rules as $regex => $replacement) {
			if (is_callable ( $replacement)) {
				$text = preg_replace_callback ($regex, $replacement, $text);
			} else {
				$text = preg_replace ($regex, $replacement, $text);
			}
		}
		return trim ($text);
	}
}
