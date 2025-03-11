<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Starship;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StarshipController extends AbstractController
{
    #[Route('/starships/{slug}', name: 'app_starship_show')]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] // Mapeia o parâmetro da rota para o atributo da entidade
        Starship $ship,
    ): Response {
        return $this->render('starship/show.html.twig', [
            'ship' => $ship,
        ]);
    }
}
