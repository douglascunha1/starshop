<?php

namespace App\Controller;

use App\Entity\Starship;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_homepage')]
    public function homepage(
        EntityManagerInterface $em,
        HttpClientInterface $client,
        CacheInterface $issLocationPool,
    ): Response {
        $ships = $em->createQueryBuilder('SELECT s FROM App\Entity\Starship s')
            ->select('s')
            ->from(Starship::class, 's')
            ->getQuery()
            ->getResult();

        $myShip = $ships[array_rand($ships)];

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
