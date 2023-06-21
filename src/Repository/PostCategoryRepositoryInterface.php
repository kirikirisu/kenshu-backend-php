<?php
namespace App\Repository;

interface PostCategoryRepositoryInterface {
    public function insertMultiCategory(int $post_id, array $category_list);
}
