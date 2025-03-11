<?php

namespace App\Controller;

use App\Entity\Starship;
use App\Repository\StarshipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_homepage')]
    public function homepage(
        StarshipRepository $repository,
        HttpClientInterface $client,
        CacheInterface $issLocationPool,
        Request $request,
    ): Response {
        // Busca todos os registros da entidade Starship
        $ships = $repository->findIncomplete();
        $ships->setMaxPerPage(5); // Seta a quantidade de registros por página
        $ships->setCurrentPage($request->query->get('page', 1)); // Seta a página atual, caso não exista, seta como 1
        $myShip = $repository->findMyShip();

        // Primeiro argumento é a chave do cache e o segundo é uma função anônima que retorna os dados da requisição
        $issData = $issLocationPool->get('iss_location_data', function () use ($client): array {
            $response = $client->request('GET', 'https://api.wheretheiss.at/v1/satellites/25544');

            return $response->toArray();
        });

        return $this->render('main/homepage.html.twig', [
            'myShip' => $myShip,
            'ships' => $ships,
            'issData' => $issData,
        ]);
    }
}
