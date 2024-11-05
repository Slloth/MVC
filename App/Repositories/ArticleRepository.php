<?php

namespace App\Repositories;

use App\Models\Article;
use Core\Repository\AbstractRepository;
/**
 * @template-extends AbstractRepository<Article>
 */
final class ArticleRepository extends AbstractRepository{
    public function __construct() {
        parent::__construct(Article::class);
    }
}