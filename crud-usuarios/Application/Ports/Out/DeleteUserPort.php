<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../Domain/ValueObjects/UserId.php';
interface DeleteUserPort
{
 public function delete(UserId $Id): void;
}
