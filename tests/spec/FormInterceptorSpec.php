<?php

namespace spec\Hum2\TwigFormModule;

use Aura\Session\SessionFactory;
use BEAR\Resource\ResourceObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ray\Aop\Arguments;
use Ray\Aop\MethodInvocation;
use Ray\Aop\ReflectiveMethodInvocation;

class FormInterceptorSpec extends ObjectBehavior
{
    function let(SessionFactory $factory)
    {
        $factory = new \Aura\Session\SessionFactory;
        $this->beConstructedWith($factory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Hum2\TwigFormModule\FormInterceptor');
    }

    function it_should_valid_csrf_value(MethodInvocation $invocation)
    {
        $factory               = new SessionFactory();
        $_POST['__csrf_value'] = $factory->newInstance($_COOKIE)->getCsrfToken()->getValue();
        $this->invoke($this->getInvocation())->shouldHaveType('spec\Hum2\TwigFormModule\FakeResource');
    }

    function it_should_not_valid_csrf_value(MethodInvocation $invocation)
    {
        $_POST['__csrf_value'] = '';
        $this->shouldThrow('Hum2\TwigFormModule\Exception\InvalidCsrfException')->duringInvoke($this->getInvocation());
    }

    private function getInvocation()
    {
        $mock = new FakeResource();
        return new ReflectiveMethodInvocation(
            $mock,
            new \ReflectionMethod($mock, 'onGet'),
            new Arguments([])
        );
    }
}

class FakeResource extends ResourceObject
{
    public function onGet($name = '')
    {
        return $this;
    }
}