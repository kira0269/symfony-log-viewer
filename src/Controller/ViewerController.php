<?php

namespace Kira0269\LogViewerBundle\Controller;

use Kira0269\LogViewerBundle\LogParser\LogParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ViewerController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('@LogViewer/viewer/index.html.twig', [
            'groups' => $this->getParameter('kira_log_viewer.groups'),
        ]);
    }

    public function ajax(LogParserInterface $logParser): JsonResponse
    {
        $logs = $logParser->parseLogs(\DateTime::createFromFormat('Y-m-d', '2021-07-28'), true);

        return $this->json($logs);
    }
}
