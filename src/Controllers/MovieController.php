<?php

// Namespace
namespace Turbo\Controllers;

// Use Libs
use \Turbo\Controllers\Controller;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

class MovieController extends \Turbo\Controllers\Controller
{

    public function getDetails(Request $request, Response $response, array $args)
    {
        $movieID = $request->getAttribute('id');

        $client = $this->tmdb;
        $repo = new \Tmdb\Repository\MovieRepository($client);
        $mData = $repo->load($movieID);

        $data = [
            'movieID' => $movieID,
            'results' => $mData,
        ];

        return $this->container->view->render($response, 'details.twig', $data);
    }

    public function topRated(Request $request, Response $response, array $args)
    {
        $pageNum = $request->getAttribute('pageNum');

        $client = $this->tmdb;
        $repo = new \Tmdb\Repository\MovieRepository($client);

        $topRated = $repo->getTopRated(array('page' => $pageNum));
        //$popular = $repo->getPopular();

        return $this->container->view->render($response, 'results.twig', $topRated);
    }
}
