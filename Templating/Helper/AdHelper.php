<?php

/*
 * This file is part of the Dacorp Extra Bundle
 *
 * (c) Jean-Christophe Meillaud
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dacorp\ExtraBundle\Templating\Helper;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class AdHelper extends Helper
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $adsRepository;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(EngineInterface $templating, Container $container)
    {
        $this->templating  = $templating;

        $this->container = $container;

//        $configDirectories = array($filename);
//        $locator = new FileLocator($configDirectories);
//        $adXmlFile = $locator->locate('adsrepository.xml', null, false);

        $this->adsRepository = new Crawler();
        $kernel = $this->container->get('kernel');
        $path = $kernel->locateResource('@DacorpExtraBundle/Resources/config/adsrepository.xml');
        $this->adsRepository->addXmlContent(file_get_contents($path));
    }

    public function getAdSense($parameters)
    {
        $renderParameters = array();
        $renderParameters['size'] = $parameters['size'];
        $renderParameters['adContent'] = htmlspecialchars_decode($this->adsRepository->filter('ad_'.$parameters['size'])->text());
        $renderParameters['adContentBack'] = $this->adsRepository->filter('ad_'.$parameters['size'].'_back')->text();

        return $this->templating->render('DacorpExtraBundle:Widgets:adSense.html.twig', $renderParameters);
    }

    public function getName()
    {
        return 'adHelpers';
    }
}
