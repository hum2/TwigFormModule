<?php

namespace Hum2\TwigFormModule;

use Hum2\TwigForm\FormTokenParser;
use Ray\Di\Di\Named;
use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;

class TwigProvider implements ProviderInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var InjectorInterface
     */
    private $injector;

    /**
     * @param \Twig_Environment $twig
     * @Named("twig=origin")
     */
    public function __construct(\Twig_Environment $twig, InjectorInterface $injector)
    {
        $this->twig     = $twig;
        $this->injector = $injector;
    }

    /**
     * @return \Twig_Environment
     */
    public function get()
    {
        $class = $this->injector->getInstance(FormTokenParser::class);
        $this->twig->addTokenParser($class);

        return $this->twig;
    }
}