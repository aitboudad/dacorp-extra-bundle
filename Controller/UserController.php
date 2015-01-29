<?php

/*
 * This file is part of the Dacorp Extra Bundle
 *
 * (c) Jean-Christophe Meillaud
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dacorp\ExtraBundle\Controller;

use Dacorp\ExtraBundle\Entity\User as DacorpUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Dacorp\ExtraBundle\Form\Type\UserFormType;

/**
 * User controller for managin edit action. Strongly linked with Picture Bundle, should be moved there.
 *
 * @author jmeyo Jean-Christophe Meillaud
 */
class UserController extends Controller
{

    /**
     * Simple action to get a csrf Token
     * @return Response
     */
    public function getCsrfTokenAction()
    {
        return new Response($this->container->get('form.csrf_provider')
            ->generateCsrfToken('authenticate'));
    }

    /*
     * show own user profile
     */
    public function showOwnProfileAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($user instanceof DacorpUser) {
            $chosenAvatarFilename = ($user->getCurrentAvatar()) ? $em->getRepository('DacorpExtraBundle:DacorpMedia')->find($user->getCurrentAvatar())->getFilename() : null;
        }

        return $this->render('DacorpExtraBundle:User:showOwnProfile.html.twig', array(
            'user' => $user,
            'avatar' => $chosenAvatarFilename,
        ));
    }

    /*
     * show a specific user profile
     */
    public function showProfileAction(DacorpUser $user)
    {
        $em = $this->getDoctrine()->getManager();

        if ($user instanceof DacorpUser) {
            $chosenAvatarFilename = ($user->getCurrentAvatar()) ? $em->getRepository('DacorpExtraBundle:DacorpMedia')->find($user->getCurrentAvatar())->getFilename() : null;
        }

        return $this->render('DacorpExtraBundle:User:showUserProfile.html.twig', array(
            'user' => $user,
            'avatar' => $chosenAvatarFilename,
        ));
    }

    /*
     * edit own user profile
     */
    public function editOwnProfileAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(new UserFormType(), $user);

        $form->handleRequest($request);
        if ($form->isValid()) {
            //not implemented
            return $this->redirect($this->generateUrl('show_own_profile'));
        } else {
            $this->get('session')->getFlashBag()->add('warning', $this->get('translator')->trans('flash.message.form-invalid'));
        }

        return $this->render('DacorpExtraBundle:User:edit-user.html.twig', array(
            'form' => $form->createView(),
            'avatar' => "nothing",
            'editId' => 'TBD',
            'existingFiles' => array('TBD'),
            'selectable' => true,
            'isNew' => false,
            'chosenAvatar' => $user->getCurrentAvatar(),
        ));
    }

    public function registerOpenIdAction()
    {
        /* Important, may be the main redirection */
        $securityContext = $this->get('security.context');

        // redirect to home if user is already logged in
        if ($securityContext->isGranted('ROLE_AUTHENTICATED')) {
            return $this->render('DacorpExtraBundle::home.html.twig');
        }

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->render('DacorpExtraBundle:User:register.html.twig', array(
                'csrf_token' => $csrfToken,
            )
        );
    }
}
