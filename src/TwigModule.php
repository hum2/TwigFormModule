<?php

namespace Hum2\TwigFormModule;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\AppMeta\AppMeta;
use BEAR\Resource\RenderInterface;
use Madapaja\TwigModule\Annotation\TwigOptions;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Madapaja\TwigModule\TwigRenderer;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

class TwigModule extends AbstractModule
{
    /**
     * @var AbstractAppMeta
     */
    private $appMeta;

    /**
     * @var array
     */
    private $paths;

    /**
     * @var array
     */
    private $options;

    /**
     * @param array $paths Twig template paths
     * @param array $options Twig_Environment options
     * @see http://twig.sensiolabs.org/api/master/Twig_Environment.html
     */
    public function __construct(AbstractAppMeta $appMeta, $paths = [], $options = [])
    {
        $this->appMeta = $appMeta;
        $this->paths   = $paths;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(AbstractAppMeta::class)->toInstance(new AppMeta('Hum2\TwigFormModule'));
        $this->bind(RenderInterface::class)->to(TwigRenderer::class)->in(Scope::SINGLETON);
        if ($this->paths) {
            $this->bind()->annotatedWith(TwigPaths::class)->toInstance($this->paths);
            $this->bind()->annotatedWith(TwigOptions::class)->toInstance($this->options);
        }
        $this
            ->bind(Twig_LoaderInterface::class)
            ->annotatedWith('twig_loader')
            ->toConstructor(
                Twig_Loader_Filesystem::class,
                'paths=Madapaja\TwigModule\Annotation\TwigPaths'
            );

        $this->overrideTwigEnvironment();

        // Intercepted Page Resource
        $this->bindInterceptor(
            $this->matcher->startsWith($this->appMeta->name . '\Page'),
            $this->matcher->logicalOr(
                $this->matcher->startsWith('onPost'),
                $this->matcher->startsWith('onPut'),
                $this->matcher->startsWith('onDelete')
            ),
            [FormInterceptor::class]
        );
    }

    private function overrideTwigEnvironment()
    {
        $this
            ->bind(Twig_Environment::class)
            ->annotatedWith('origin')
            ->toConstructor(
                Twig_Environment::class,
                'loader=twig_loader,options=Madapaja\TwigModule\Annotation\TwigOptions'
            )
            ->in(Scope::SINGLETON);
        $this
            ->bind(Twig_Environment::class)
            ->toProvider(TwigProvider::class)
            ->in(Scope::SINGLETON);
    }
}
