<?php

namespace App\Command;

use App\Entity\StarshipStatusEnum;
use App\Repository\StarshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:ship:check-in',
    description: 'Check-in ship',
)]
class ShipCheckInCommand extends Command
{
    public function __construct(
        private StarshipRepository $shipRepo,
        private EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('slug', InputArgument::REQUIRED, 'The slug of the starship.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $slug = $input->getArgument('slug');
        $ship = $this->shipRepo->findOneBy([
            'slug' => $slug, // Busca a nave pelo slug
        ]);

        if (!$ship) {
            $io->error('Starship not found.');

            return Command::FAILURE;
        }

        // Exibe uma mensagem de confirmação da exclusão
        $io->comment(sprintf('Checking in starship %s', $ship->getName()));

        // Atualiza o status da nave para "WAITING" e define a data de chegada
        $ship->setArrivedAt(new \DateTimeImmutable('now'));
        $ship->setStatus(StarshipStatusEnum::WAITING);

        // Não precisa adicionar o objeto para ser removido na fila(queue) de remoção, o Symfony já sabe que o objeto foi alterado
        $this->em->flush();

        // Exibe uma mensagem de sucesso
        $io->success('Starship checked-in.');

        return Command::SUCCESS;
    }
}
