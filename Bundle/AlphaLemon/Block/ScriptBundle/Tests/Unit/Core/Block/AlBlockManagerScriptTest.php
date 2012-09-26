<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\ScriptBundle\Core\Block\AlBlockManagerScript;

/**
 * AlBlockManagerTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AlBlockManagerScriptTest extends TestCase
{
    protected function setUp()
    {
        $factoryRepository = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface');
        $eventsHandler = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface');
        $this->blockManager = new AlBlockManagerScript($eventsHandler, $factoryRepository);
    }

    public function testDefaultValue()
    {
        $expectedValue = array(
            'HtmlContent' => '',
            'InternalJavascript' => '',
            'ExternalJavascript' => ''
        );
        $this->assertEquals($expectedValue, $this->blockManager->getDefaultValue());
    }

    public function testHtmlContentDisplaysTheContentWhenAnyJavascriptTagExists()
    {
        $block = $this->initBlock('A fancy javascript');
        $this->blockManager->set($block);
        $this->assertEquals('A fancy javascript<script type="text/javascript">$(document).ready(function(){$(\'#block_2\').data(\'block\', $(\'#block_2\').html());});</script>', $this->blockManager->getHtmlCmsActive());
    }

    public function testHtmlContentDisplaysAWarningWhenAtLeastOneJavascriptTagExists()
    {
        $block = $this->initBlock('<script>A fancy javascript</script>');
        $this->blockManager->set($block);
        $this->assertEquals('A script content is not rendered in editor mode<script type="text/javascript">$(document).ready(function(){$(\'#block_2\').data(\'block\', $(\'#block_2\').html());});</script>', $this->blockManager->getHtmlCmsActive());
    }

    public function testHideInEditMode()
    {
        $this->assertTrue($this->blockManager->getHideInEditMode());
    }

    public function testReloadSuggested()
    {
        $this->assertTrue($this->blockManager->getReloadSuggested());
    }
    
    private function initBlock($htmlContent)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
            ->method('getHtmlContent')
            ->will($this->returnValue($htmlContent));
        
        $block->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(2));
        
        return $block;
    }
            
}
