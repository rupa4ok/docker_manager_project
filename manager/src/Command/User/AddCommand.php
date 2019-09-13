<?php

declare(strict_types=1);

namespace App\Command\User;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\User;
use App\Model\User\UseCase\SignUp\Request;
use App\ReadModel\User\UserFetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AddCommand extends Command
{
    private $users;

    public function __construct(UserFetcher $users)
    {
        $this->users = $users;
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

        $hash = $helper->ask($input, $output, new Question('Password: '));

        $user = User::signUpByEmail(
            Id::next(),
            new \DateTimeImmutable,
            new Email($email),
            $hash,
            'token'
        );
        $user->confirmSignUp();
        $output->writeln('<info>Done!</info>');
    }
}
