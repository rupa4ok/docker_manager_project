<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\NewEmail\Request;

use App\Model\Flusher;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\Id;
use App\Model\User\Entity\User;
use App\Model\User\Entity\UserRepository;
use App\Model\User\Service\NewEmailConfirmTokenizer;
use App\Model\User\Service\NewEmailConfirmTokenSender;

class Handler
{
    private $users;
    private $tokenizer;
    private $sender;
    private $flusher;
    public function __construct(
        UserRepository $users,
        NewEmailConfirmTokenizer $tokenizer,
        NewEmailConfirmTokenSender $sender,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
    	$user = $this->users->get(new Id($command->id));
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new \DomainException('Такой email уже используется.');
        }

        $user->requestEmailChanging(
        	$email,
	        $token = $this->tokenizer->generate()
        );
        
        $this->flusher->flush();
	    $this->sender->send($email, $token);
    }
}
