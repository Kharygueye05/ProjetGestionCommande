<?php

namespace App\Command;

use App\Entity\Gestionnaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'test:password',
    description: 'Teste si le mot de passe du gestionnaire est correct',
)]
class TestPasswordCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $email = 'admin@brasilburger.com';
        $password = 'azerty123@@@';
        
        $gestionnaire = $this->entityManager->getRepository(Gestionnaire::class)
            ->findOneBy(['email' => $email]);
        
        if (!$gestionnaire) {
            $io->error("Gestionnaire non trouvé avec l'email: $email");
            return Command::FAILURE;
        }
        
        $io->info("Gestionnaire trouvé:");
        $io->text("Email: " . $gestionnaire->getEmail());
        $io->text("ID: " . $gestionnaire->getId());
        
        $isValid = $this->passwordHasher->isPasswordValid($gestionnaire, $password);
        
        if ($isValid) {
            $io->success("✅ Le mot de passe est VALIDE!");
        } else {
            $io->error("❌ Le mot de passe est INVALIDE!");
            
            $io->note("Vous pouvez recréer le hash avec:");
            $io->text("php bin/console security:hash-password");
            $io->text("Puis mettre à jour la base de données avec le nouveau hash.");
        }
        
        return Command::SUCCESS;
    }
}