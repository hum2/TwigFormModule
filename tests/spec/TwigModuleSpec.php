<?php

namespace spec\Hum2\TwigFormModule;

use BEAR\AppMeta\AppMeta;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TwigModuleSpec extends ObjectBehavior
{
    function let(AppMeta $appMeta)
    {
        $this->beConstructedWith($appMeta);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Hum2\TwigFormModule\TwigModule');
    }
}