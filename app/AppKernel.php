<?php

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__ . '/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

class AppKernel extends Kernel
{

    use MicroKernelTrait;

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new AppBundle\AppBundle(),
        );

        return $bundles;
    }

    protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config.yml');
        $loader->load(__DIR__ . '/config/parameters.yml');
        $loader->load(__DIR__ . '/config/services.yml');
    }
    
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        // can configure routes here if needed later
    }
    
    public function getCacheDir()
    {
        return __DIR__ . '/../var/cache/' . $this->getEnvironment();
    }
    
    public function getLogDir()
    {
        return __DIR__.'/../var/logs';
    }    

}
