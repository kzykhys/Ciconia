<?php
use Ciconia\Ciconia;
use Ciconia\Renderer\RendererInterface;
use Ciconia\Renderer\HtmlRenderer;

/**
 * Tests Ciconia\Extensions\Core\BlockQuoteExtension
 *
 * @author Martin Wind <mawi1988@gmail.com>
 */
class BlockQuoteExtensionsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * get an block quote aware HtmlRenderer 
	 * 
	 * @return RendererInterface
	 */
	protected function getBlockQuoteLevelRenderer()
	{
		$renderer = $this->getMock('Ciconia\Renderer\HtmlRenderer', array('renderBlockQuote'));
		$renderer->expects($this->any())
			->method('renderBlockQuote')
			->will($this->returnCallback(
					function($content, $options) {
						return '<blockquote data-level="'.$options['level'].'">'.$content.'</blockquote>';
					}
			));
		return $renderer;
	}
	
	/**
	 * run an simple block quote aware test
	 * 
	 * @param string $name
	 * @param string $markdown
	 * @param string $expectedHtml
	 */
	protected function runBlockQuoteLevelTest($name, $markdown, $expectedHtml)
	{
		$renderer = $this->getBlockQuoteLevelRenderer();
		$ciconia = new Ciconia($renderer);
		$html = str_replace("\n", '', $ciconia->render($markdown));
		$expectedHtml = str_replace(array("\n", "\t"), '', $expectedHtml);
		$this->assertEquals($expectedHtml, $html, sprintf('%s failed', $name));
	}
	
	/**
	 * Test an level 2 block quote
	 */
	public function testBlockQuoteLevel2()
	{
		$this->runBlockQuoteLevelTest('block qoute level 2',
'> level 1 line 1 
level 1 line 2
>> level 2 line 1',

'<blockquote data-level="1">
	<p>level 1 line 1 level 1 line 2</p>
	<blockquote data-level="2">
		<p>level 2 line 1</p>
	</blockquote>
</blockquote>');
	}
	
	/**
	 * Test an level 3 block quote
	 */
	public function testBlockQuoteLevel2Multiline()
	{
		$this->runBlockQuoteLevelTest('block qoute level 2 multiline',
'> level 1 line 1 
> level 1 line 2
>> level 2 line 1 
>> level 2 line 2
>>> level 3 line 1',
		
'<blockquote data-level="1">
	<p>level 1 line 1 level 1 line 2</p>
	<blockquote data-level="2">
		<p>level 2 line 1 level 2 line 2</p>
		<blockquote data-level="3">
			<p>level 3 line 1</p>
		</blockquote>
	</blockquote>
</blockquote>');
		}
	
	/**
	 * Test an nested block quote 
	 */
	public function testBlockQuoteLevelNested()
	{
		$this->runBlockQuoteLevelTest('nested block quote and level',
'> level 1 line 1
> > level 2 line 1 
> > level 2 line 1
> > > level 3 line 1 
> > > level 3 line 1',
		
'<blockquote data-level="1">
	<p>level 1 line 1</p>
	<blockquote data-level="2">
		<p>level 2 line 1 level 2 line 1</p>
		<blockquote data-level="3">
			<p>level 3 line 1 level 3 line 1</p>
		</blockquote>
	</blockquote>
</blockquote>');
	}
}