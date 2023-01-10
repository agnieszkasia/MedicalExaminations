<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Entity\Param;
use App\Form\ExamType;
use App\Form\ParamType;
use App\Repository\ExamRepository;
use App\Response\Object\Exam as ExamResponse;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExamController extends AbstractController
{
    #[Route('/exam', name: 'app_exam')]
    public function index(ExamRepository $examRepository): Response
    {
        $exams = $examRepository->findAll();

        $exam = new Exam();
        $form = $this->createForm(ExamType::class, $exam);

        return $this->render('exam/index.html.twig', [
            'exams' => $exams,
            'form' => $form->createView()
        ]);
    }

    #[Route('/exam/new', name: 'app_exam_new')]
    public function create(LoggerInterface $logger, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$request->isXmlHttpRequest()) {
            $logger->info('No results found');
            return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);
        }

        $exam = new Exam();
        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            $logger->error('Błąd dodawania badania');
            $entityManager->clear();
            $entityManager->flush();
            return new JsonResponse(['message' => 'Nie udało się dodać badania'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $entityManager->persist($exam);
        $entityManager->flush();

        $logger->info('Dodano badanie ID:' . $exam->getId());

        return new JsonResponse(new ExamResponse($exam), Response::HTTP_CREATED);
    }

    #[Route('/exam/{id}', name: 'app_exam_show')]
    public function show(ExamRepository $examRepository, int $id): Response
    {
        $exam = $examRepository->find($id);

        if (!$exam) {
            throw $this->createNotFoundException(
                'Nie znaleziono badania ID: ' . $id
            );
        }

        $param = new Param();
        $param->setExam($exam);
        $form = $this->createForm(ParamType::class, $param);

        return $this->render('exam/show.html.twig', [
            'exam' => $exam,
            'form' => $form->createView()
        ]);
    }

    #[Route('/exam/{id}/delete', name: 'app_exam_delete')]
    public function delete(LoggerInterface $logger, Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse('no results found', Response::HTTP_NOT_FOUND);
        }

        $exam = $entityManager->getRepository(Exam::class)->find($id);

        foreach ($exam->getParams() as $param) {
            $entityManager->remove($param);
        }

        $entityManager->remove($exam);
        $entityManager->flush();

        $logger->info('Usunięto badanie ID: ' . $id);

        return new JsonResponse($exam);
    }
}
