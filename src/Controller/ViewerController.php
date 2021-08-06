<?php

namespace Kira0269\LogViewerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ViewerController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('@LogViewer/viewer/index.html.twig');
    }

    public function ajax(): JsonResponse
    {
        $logs = [
            [
                'date' => '2021-08-05T13:11:50.632049+00:00',
                'context' => 'security',
                'level' => 'DEBUG',
                'description' => 'Checking for authenticator support',
                'body' => '{"firewall_name":"main","authenticators":0}'
            ],
            [
                'date' => '2021-08-05T13:11:50.632049+00:00',
                'context' => 'security',
                'level' => 'DEBUG',
                'description' => 'Checking for authenticator support',
                'body' => '{"firewall_name":"main","authenticators":0}'
            ],
            [
                'date' => '2021-08-05T13:11:50.632049+00:00',
                'context' => 'security',
                'level' => 'DEBUG',
                'description' => 'Checking for authenticator support',
                'body' => '{"firewall_name":"main","authenticators":0}'
            ],
            [
                'date' => '2021-08-05T13:11:50.632049+00:00',
                'context' => 'security',
                'level' => 'DEBUG',
                'description' => 'Checking for authenticator support',
                'body' => '{"firewall_name":"main","authenticators":0}'
            ],
            [
                'date' => '2021-08-05T13:11:50.632049+00:00',
                'context' => 'security',
                'level' => 'DEBUG',
                'description' => 'Checking for authenticator support',
                'body' => '{"firewall_name":"main","authenticators":0}'
            ]
        ];
        return new JsonResponse($logs);
    }
}