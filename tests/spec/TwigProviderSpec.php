<?php

namespace spec\Hum2\TwigFormModule;

use BEAR\AppMeta\AppMeta;
use BEAR\Sunday\Provide\Application\AppModule;
use Hum2\TwigFormModule\TwigModule;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ray\Di\Injector;

class TwigProviderSpec extends ObjectBehavior
{
    function let(\Twig_Environment $twig, Injector $injector)
    {
        $this->beConstructedWith($twig, $injector);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Hum2\TwigFormModule\TwigProvider');
    }
}