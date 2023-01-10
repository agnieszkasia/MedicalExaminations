<?php

namespace App\Controller;

use App\Entity\Param;
use App\Form\ParamType;
use App\Repository\ExamRepository;
use App\Response\Object\Param as ParamResponse;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParamController extends AbstractController
{
    #[Route('/param/new', name: 'app_param_new')]
    public function create(ExamRepository $examRepository, LoggerInterface $logger, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$request->isXmlHttpRequest()) {
            $logger->info('No results found');
            return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);
        }

        $param = new Param();
        $form = $this->createForm(ParamType::class, $param);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return new JsonResponse(['message' => 'Nie udało się dodać parametru'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $entityManager->persist($param);
        $entityManager->flush();

        $logger->info('Dodano parametr do badania ID:' . $param->getExam()->getId());

        return new JsonResponse(new ParamResponse($param), Response::HTTP_CREATED);
    }

    #[Route('/param/{id}/delete', name: 'app_param_delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);
        }

        $param = $entityManager->getRepository(Param::class)->find($id);

        $entityManager->remove($param);
        $entityManager->flush();

        return new JsonResponse($param);
    }
}
