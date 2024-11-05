<?php

namespace App\Repositories;

use App\Models\Tag;
use Core\Repository\AbstractRepository;
/**
 * @template-extends AbstractRepository<Tag>
 */
final class TagRepository extends AbstractRepository{
    public function __construct() {
        parent::__construct(Tag::class);
    }
}