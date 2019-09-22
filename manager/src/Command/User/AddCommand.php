<?php

declare(strict_types=1);

namespace App\Command\User;

use App\Model\User\Entity\UserRepository;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\UseCase\SignUp\Confirm;
use App\Model\User\UseCase\SignUp;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AddCommand extends Command
{
    private $users;
    private $handler;
    private $hasher;
    private $repo;
    private $signup;

    public function __construct(
        UserFetcher $users,
        SignUp\Request\Handler $handler,
        PasswordHasher $hasher,
        UserRepository $repo,
        Confirm\Manual\Handler $signup
    )
    {
        $this->handler = $handler;
        $this->users = $users;
        $this->hasher = $hasher;
        $this->repo = $repo;
        $this->signup = $signup;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('user:add')
            ->setDescription('Add new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $output->writeln('<info>Регистрация нового пользователя</info>');

        $email = $helper->ask($input, $output, new Question('Email: '));
        if ($user = $this->users->findByEmail($email)) {
            throw new LogicException('Пользователь с таким email уже существует.');
        }

        $password = $helper->ask($input, $output, new Question('Password: '));
        $this->create($email, $password);
        $output->writeln('<info>Done!</info>');
    }

    private function create($email, $password)
    {
        $command = new SignUp\Request\Command();
        $command->email = $email;
        $command->password = $this->hasher->hash($password);
        $this->handler->handle($command);

        $user = $this->users->findByEmail($email);
        $command = new Confirm\Manual\Command($user->id);
        $this->signup->handle($command);
    }
}
