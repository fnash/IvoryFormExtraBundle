<?php

/*
 * This file is part of the Ivory Form Extra package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\FormExtraBundle\Tests\DependencyInjection\Compiler;

use Ivory\FormExtraBundle\DependencyInjection\Compiler\TemplatingCompilerPass;
use Ivory\FormExtraBundle\Tests\AbstractTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class TemplatingCompilerPassTest extends AbstractTestCase
{
    /**
     * @var TemplatingCompilerPass
     */
    private $compilerPass;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->compilerPass = new TemplatingCompilerPass();
    }

    public function testPhpTemplating()
    {
        $containerBuilder = $this->createContainerBuilderMock();
        $containerBuilder
            ->expects($this->exactly(2))
            ->method('hasDefinition')
            ->will($this->returnValueMap([
                ['templating.engine.php', false],
                ['twig', true],
            ]));

        $containerBuilder
            ->expects($this->once())
            ->method('removeDefinition')
            ->with($this->identicalTo('ivory_form_extra.templating.helper'));

        $this->compilerPass->process($containerBuilder);
    }

    public function testTwigTemplating()
    {
        $containerBuilder = $this->createContainerBuilderMock();
        $containerBuilder
            ->expects($this->exactly(2))
            ->method('hasDefinition')
            ->will($this->returnValueMap([
                ['templating.engine.php', true],
                ['twig', false],
            ]));

        $containerBuilder
            ->expects($this->once())
            ->method('removeDefinition')
            ->with($this->identicalTo('ivory_form_extra.twig.extension'));

        $this->compilerPass->process($containerBuilder);
    }

    /**
     * @return ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createContainerBuilderMock()
    {
        return $this->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['hasDefinition', 'removeDefinition'])
            ->getMock();
    }
}
