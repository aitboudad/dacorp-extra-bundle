<?php

/*
 * This file is part of the Dacorp Extra Bundle
 *
 * (c) Jean-Christophe Meillaud
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dacorp\ExtraBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DacorpExtraExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if (isset($config['dacorp_media_class'])) {
            $container->setParameter('dacorp_extra.dacorp_media_class', $config['dacorp_media_class']);
        }
        $container->setParameter('dacorp_extra.social_networks', $config['social_networks']);

        if ($config['use_uploader']) {
            $loader->load('services/service_uploader.yml');
        }

        if ($config['use_acl']) {
            $loader->load('services/service_acl.yml');
        }

        $loader->load('services.yml');
        $loader->load('services_forms.yml');
        $loader->load('services_manager.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $config = array();

        if (!isset($bundles['PunkAveFileUploaderBundle'])) {
            $config['use_uploader'] = false;
        }

        if (!isset($bundles['ProblematicAclManagerBundle'])) {
            $config['use_acl'] = false;
        }

        $container->prependExtensionConfig('dacorp_extra', $config);
    }
}
