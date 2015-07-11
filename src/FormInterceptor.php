<?php

namespace Hum2\TwigFormModule;

use Aura\Session\SessionFactory;
use Hum2\TwigFormModule\Exception\InvalidCsrfException;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

class FormInterceptor implements MethodInterceptor
{
    /**
     * @var SessionFactory
     */
    private $factory;

    /**
     * @param SessionFactory $factory
     */
    public function __construct(SessionFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        $session   = $this->factory->newInstance($_COOKIE);
        $csrfToken = $session->getCsrfToken();
        if (!$csrfToken->isValid($this->getActualToken())) {
            throw new InvalidCsrfException;
        }
        $csrfToken->regenerateValue();

        return $invocation->proceed();
    }

    /**
     * @return string
     */
    private function getActualToken()
    {
        if (!isset($_POST['__csrf_value'])) {
            return '';
        }

        return $_POST['__csrf_value'];
    }
}