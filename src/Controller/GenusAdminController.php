<?php

namespace App\Controller;

use App\Service\MessageManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Form\GenusFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Genus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @IsGranted("ROLE_MANAGE_GENUS")
 * @Route("/admin")
 */
class GenusAdminController extends Controller
{

    /**
     * @Route("/genus", name="admin_genus_list")
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @return Response
     */
    public function indexAction(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if(!$authorizationChecker->isGranted('ROLE_ADMIN')){
            throw $this->createAccessDeniedException('GET OUT!');
        }

        $em = $this->getDoctrine()->getManager();
        $genuses = $em->getRepository(Genus::class)->findAllPublishedOrderedByRecentlyActive();

        return $this->render('admin/genus/list.html.twig', ['genuses'=>$genuses]);
    }

    /**
     * @Route("/genus/new", name="admin_genus_new")
     * @param Request $request
     * @return Response
     */

    public function newAction(Request $request)
    {
        $form = $this->createForm(GenusFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $form = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($form);
            $em->flush();

            $this->addFlash(
                'success',
                sprintf('Genus created by you: %s!', $this->getUser()->getEmail())
            );

            return $this->redirectToRoute('admin_genus_list');
        }

        return $this->render('admin/genus/new.html.twig', [
            'genusForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/genus/{id}/edit", name="admin_genus_edit")
     */
    public function editAction(Request $request, Genus $genus, MessageManager $messageManager)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(GenusFormType::class, $genus);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $form = $form->getData();

            $genus->setName($form->getName());
            $genus->setSubFamily($form->getSubFamily());
            $genus->setSpeciesCount($form->getSpeciesCount());
            $genus->setFunFact($form->getFunFact());
            $genus->setIsPublished($form->getIsPublished());
            $genus->setFirstDiscoveredAt($form->getFirstDiscoveredAt());

            $em->persist($genus);
            $em->flush();

            $this->addFlash('success',
                $messageManager->getEncouragingMessage());

            return $this->redirectToRoute('admin_genus_list');
        }elseif($form->isSubmitted()){
            $this->addFlash(
                'error',
                $messageManager->getDiscouragingMessage()
            );
        }

        return $this->render('admin/genus/edit.html.twig', [
           'genusForm' => $form->createView()
        ]);
    }
}
