<?php

namespace App\Services;

use HM\Core\KC\Application;
use PDO;
use stdClass;

class CategoryService
{
    public function getAllCategories(): array
    {
        $result = Application::$app
            ->database
            ->connection
            ->query('select categories.id, categories.name,categories.name as description, categories.parent as parent_id, count(courses.id) as count_of_courses, categories.created_at,categories.updated_at from categories left join courses on categories.id = courses.category_id group by categories.id');

        return $result->fetchAll(PDO::FETCH_OBJ);
    }
    public function getCategoryById($id): bool|StdClass
    {
        $result = Application::$app
            ->database
            ->connection
            ->prepare('select categories.id, categories.name,categories.name as description, categories.parent as parent_id, count(courses.id) as count_of_courses, categories.created_at,categories.updated_at from categories left join courses on categories.id = courses.category_id group by categories.id having categories.id = :id');

        $result->bindParam(':id', $id, PDO::PARAM_STR);
        $result->execute();

        return $result->fetch(PDO::FETCH_OBJ);
    }
    public function getTree($isHtml = true): string|array
    {
        $categories = $this->getAllCategories();
        return $isHtml
            ? $this->asHtml($categories, null, 4)
            : $this->asTree($categories, null, 4);
    }
    public function asTree($categories, $start, $level): array
    {
        $parents = [];
        if ($level > 0) {
            foreach ($categories as $index => $category) {
                if ($category->parent_id == $start) {

                    unset($categories[$index]);

                    $children = $this->asTree($categories, $category->id, $level - 1);

                    $subTotal = 0;
                    foreach ($children as $child) {
                        $subTotal += $child['total'];
                    }

                    $parents[] = [
                        'id'       => $category->id,
                        'name'     => $category->name,
                        'parent'   => $category->parent_id,
                        'total'    => $category->count_of_courses + $subTotal,
                        'children' => $children
                    ];
                }
            }
        }
        return $parents;
    }
    public function generateListItems(array $categories, $start): string
    {
        $html = is_null($start) ? '<ul class="nested-list">' : '<ul>';

        foreach ($categories as $category) {
            $html .= sprintf('<li data-name="%s" id="%s" class="category clickable"> %s %s', $category['name'], $category['id'], htmlspecialchars($category['name']), $category['total'] > 0 ? '<span class="total_courses"> (' . $category['total'] . ')</span>' : '');

            if (!empty($category['children'])) {
                $html .= $this->generateListItems($category['children'], 1);
            }

            $html .= '</li>';
        }

        return  $html . '</ul>';
    }
    public function asHtml($categories, $start, $level): string
    {
        $tree = $this->asTree($categories, $start, $level);

        return $this->generateListItems($tree, $start);
    }
}
