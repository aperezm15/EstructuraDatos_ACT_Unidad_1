<?php

require_once __DIR__ ."/EventDomain.php";
require_once __DIR__ ."/../Models/UserModel.php";

class UserCreatedDomainEvent extends DomainEvent {

    private $user;

    public function __construct(UserModel $user) {
        parent::__construct('user.created');
        $this->user = $user;
    }

    public function user(){
        return $this->user;
    }

    public function payload()
    {
        return array(
            'id'=> $this->user->id()->value(),
            'name' => $this->user->name()->value(),
            'email'=> $this->user->email()->value(),
            'role' => $this->user->role(),
            'status' => $this->user->status()
        );
    }
}