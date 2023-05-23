<?php

namespace Code23\MarketplaceLaravelSDK\Traits;

trait HandlesCategories
{
    protected function filterCategoriesById($id, $categories)
    {
        foreach ($categories as $category) {
            if ($category['id'] == $id) {
                return $category;
            }
            if (isset($category['active_children'])) {
                $result = $this->filterCategoriesById($id, $category['active_children']);
                if ($result !== null) {
                    return $result;
                }
            }
        }
        return null;
    }
}
