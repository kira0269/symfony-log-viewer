<?php

namespace Kira0269\LogViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('@LogViewer/dashboard/index.html.twig', [
            'blocks' => $this->getParameter('kira_log_viewer.dashboard.blocks')
        ]);
    }
}