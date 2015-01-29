<?php

/*
 * This file is part of the Dacorp Extra Bundle
 *
 * (c) Jean-Christophe Meillaud
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dacorp\ExtraBundle\Twig;

class SocialBar extends \Twig_Extension
{

    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'dacorp_social_bar';
    }

    public function getFunctions()
    {
        return array(
            'socialButtonsBox' => new \Twig_Function_Method($this, 'getSocialButtonsBox', array('is_safe' => array('html'), 'type' => 'box')),
            'socialButtonsInline' => new \Twig_Function_Method($this, 'getSocialButtonsInline', array('is_safe' => array('html'), 'type' => 'horizontal')),
            'facebookButton' => new \Twig_Function_Method($this, 'getFacebookLikeButton', array('is_safe' => array('html'))),
            'twitterButton' => new \Twig_Function_Method($this, 'getTwitterButton', array('is_safe' => array('html'))),
            'googlePlusButton' => new \Twig_Function_Method($this, 'getGooglePlusButton', array('is_safe' => array('html'))),
            'pinterestButton' => new \Twig_Function_Method($this, 'getPinterestButton', array('is_safe' => array('html'))),
        );
    }

    public function getSocialButtons($parameters = array(), $type = 'box')
    {
        $renderParameters = array();

        foreach (array('facebook', 'twitter', 'googleplus', 'pinterest') as $buttonType) {
            // no parameters were defined, keeps default values
            if (!array_key_exists($buttonType, $parameters)) {
                $renderParameters[$buttonType] = array();
                // parameters are defined, overrides default values
            } elseif (is_array($parameters[$buttonType])) {
                $renderParameters[$buttonType] = $parameters[$buttonType];
                // the button is not displayed
            } else {
                $renderParameters[$buttonType] = false;
            }
        }

        if (isset($parameters['url'])) {
            $renderParameters['facebook']['url'] = $parameters['url'];
            $renderParameters['twitter']['url'] = $parameters['url'];
            $renderParameters['googleplus']['url'] = $parameters['url'];
            $renderParameters['pinterest']['url'] = $parameters['url'];
        }

        if ($type == 'box') {
            $renderParameters['facebook']['layout'] = 'box_count';
            $renderParameters['twitter']['count'] = 'vertical';
            $renderParameters['googleplus']['size'] = 'tall';
            // $renderParameters['pinterest']['url']=$parameters['url'];
        } else {
            $renderParameters['facebook']['layout'] = 'button_count';
            $renderParameters['twitter']['count'] = 'horizontal';
            $renderParameters['googleplus']['size'] = 'medium';
            // $renderParameters['pinterest']['url']=$parameters['url'];
        }

        $renderParameters['pinterest'] = false;
        // get the helper service and display the template
        return $this->container->get('dacorp.socialBarHelper')->socialButtons($renderParameters);
    }

    public function getSocialButtonsBox($parameters = array())
    {
        return $this->getSocialButtons($parameters, 'box');
    }

    public function getSocialButtonsInline($parameters = array())
    {
        return $this->getSocialButtons($parameters, 'inline');
    }
    //https://developers.facebook.com/docs/reference/plugins/like/
    public function getFacebookLikeButton($parameters = array())
    {
        // default values, you can override the values by setting them
        $parameters = $parameters + array(
            'url' => null,
            'locale' => 'en_US',
            'send' => false,
            'width' => 60,
            'showFaces' => false,
            'layout' => 'box_count',
        );

        return $this->container->get('dacorp.socialBarHelper')->facebookButton($parameters);
    }

    public function getTwitterButton($parameters = array())
    {
        $parameters = $parameters + array(
            'url' => null,
            'locale' => 'en',
            'message' => 'I want to share that page with you',
            'text' => 'Tweet',
            'via' => 'justmoovParis',
            'count' => 'horizontal',
            'tag' => false,

        );

        return $this->container->get('dacorp.socialBarHelper')->twitterButton($parameters);
    }

    public function getGooglePlusButton($parameters = array())
    {
        $parameters = $parameters + array(
            'url' => null,
            'locale' => 'en',
            'size' => 'tall',
            'annotation' => 'bubble',
            'width' => '40',
        );

        return $this->container->get('dacorp.socialBarHelper')->googlePlusButton($parameters);
    }

    public function getPinterestButton($parameters = array())
    {
        $parameters = $parameters + array(
            'url' => null,
        );

        return $this->container->get('dacorp.socialBarHelper')->pinterestButton($parameters);
    }
}
