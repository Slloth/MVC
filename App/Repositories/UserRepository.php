<?php

namespace App\Repositories;

use App\Models\User;
use Core\Repository\AbstractRepository;
/**
 * @template-extends AbstractRepository<User>
 */
final class UserRepository extends AbstractRepository{
    public function __construct() {
        parent::__construct(User::class);
    }
}