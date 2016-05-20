<?php

namespace Coyl\JiraApiRestClientBundle\DependencyInjection;

use chobie\Jira\Api\Authentication\Anonymous;
use chobie\Jira\Api\Authentication\Basic;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader;

class JiraApiRestClientExtension extends ConfigurableExtension
{
    /**
     * @var Loader\XmlFileLoader
     */
    private $loader;

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $this->loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $this->loader->load('services.xml');

        $definition = new Definition();
        if ($mergedConfig['auth']['type'] === 'anonymous') {
            $definition->setClass('chobie\Jira\Api\Authentication\Anonymous');
        } elseif ($mergedConfig['auth']['type'] === 'basic') {
            $definition->setClass('chobie\Jira\Api\Authentication\Basic');
            $definition->setArguments([$mergedConfig['auth']['username'], $mergedConfig['auth']['password']]);
        } else {
            throw new InvalidConfigurationException('Auth type must be anonymous or basic');
        }
        $container->getDefinition('jira_api_rest_client')->replaceArgument(0, $mergedConfig['endpoint']);
        $container->getDefinition('jira_api_rest_client')->replaceArgument(1, $definition);
    }
}