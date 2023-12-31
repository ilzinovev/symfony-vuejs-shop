<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class AddUserCommand extends Command
{
    protected static $defaultName = 'app:add-user';
    protected static $defaultDescription = 'Create user';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        string $name = null,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        UserRepository $userRepository
    ) {
        parent::__construct();
        $this->entityManager  = $entityManager;
        $this->encoder        = $encoder;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('email', 'em', InputArgument::REQUIRED, 'Email')
            ->addOption('password', 'p', InputArgument::REQUIRED, 'Password')
            ->addOption('isAdmin', '', InputArgument::OPTIONAL, 'set if admin', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io        = new SymfonyStyle($input, $output);
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user');


        $email    = $input->getOption('email');
        $password = $input->getOption('password');
        $isAdmin  = $input->getOption('isAdmin');


        $io->title('Add User Command Wizard');
        $io->text(['Please enter the following information']);

        if (!$email) {
            $email = $io->ask('Email please');
        }
        if (!$password) {
            $password = $io->askHidden('Password please');
        }

        if (!$isAdmin) {
            $question = new Question('is admin? (1 or 0)', 0);
            $isAdmin  = $io->askQuestion($question);
        }
        $isAdmin = boolval($isAdmin);

        try {
            $user = $this->createUser($email, $password, $isAdmin);
        } catch (RuntimeException  $exception) {
            $io->comment($exception->getMessage());
            return Command::FAILURE;
        }


        $successMessage = sprintf(
            '%s was successfully added: %s',
            $isAdmin ? 'Administrator user' : 'Default user',
            $email
        );
        $io->success($successMessage);

        $event            = $stopwatch->stop('add-user');
        $stopwatchMessage = sprintf(
            'New user id: %s / Elapsed time: %.2f ms / Consumed memory: %.2f MB',
            $user->getId(),
            $event->getDuration(),
            $event->getMemory() / 1000 / 1000
        );
        $io->comment($stopwatchMessage);

        return Command::SUCCESS;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $isAdmin
     * @return User
     */
    private function createUser(string $email, string $password, bool $isAdmin): User
    {
        $existingUser = $this->userRepository->findOneBy(['email' => $email]);
        if ($existingUser) {
            throw new RuntimeException('user already exist');
        }
        $user = new User();
        $user->setEmail($email);

        $user->setRoles([$isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER']);
        $encodedPassword = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encodedPassword);
        $user->setIsVerified(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}
