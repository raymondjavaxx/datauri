<?php

use \datauri\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase {

	public function testParseBase64() {
		$uri = <<<EOD
data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUA
AAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO
9TXL0Y4OHwAAAABJRU5ErkJggg==
EOD;

		$data = Parser::parse($uri);
		$this->assertEquals('image/png', $data->contentType);
		$this->assertEquals(file_get_contents(dirname(__DIR__) . '/data/dot.png'), $data->content);
	}

	public function testParseRFC1738 () {
		$data = <<<EOD
data:image/png,%89PNG%0D%0A%1A%0A%00%00%00%0DIHDR%00%00%00%05%00%00%00%05%08%06
%00%00%00%8Do%26%E5%00%00%00%1CIDAT%08%D7c%F8%FF%FF%3F%C3%7F%06%20%05%C3%20%12
%84%D01%F1%82X%CD%04%00%0E%F55%CB%D1%8E%0E%1F%00%00%00%00IEND%AEB%60%82
EOD;

		$data = Parser::parse($data);
		$this->assertEquals('image/png', $data->contentType);
		$this->assertEquals(file_get_contents(dirname(__DIR__) . '/data/dot.png'), $data->content);
	}
}
