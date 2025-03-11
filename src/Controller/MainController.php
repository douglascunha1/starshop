<?php

namespace App\Controller;

use App\Repository\StarshipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_homepage')]
    public function homepage(
        StarshipRepository $starshipRepository,
        HttpClientInterface $client,
        CacheInterface $cache,
    ): Response
    {
        $ships = $starshipRepository->findAll();
        $myShip = $ships[array_rand($ships)];

        # Primeiro argumento é a chave do cache e o segundo é uma função anônima que retorna os dados da requisição
        $issData = $cache->get('iss_location_data', function (ItemInterface $item) use ($client): array {
            $item->expiresAfter(5); # Expira após 5 segundos

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
