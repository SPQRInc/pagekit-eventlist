<?php

namespace Spqr\Eventlist\Controller;

use Pagekit\Application as App;
use Spqr\Eventlist\Model\Category;


/**
 * @Access(admin=true)
 * @return string
 */
class CategoryController
{
    /**
     * @Access("eventlist: manage categories")
     * @Request({"filter": "array", "page":"int"})
     * @param null $filter
     * @param int  $page
     *
     * @return array
     */
    public function categoryAction($filter = null, $page = 0)
    {
        return [
            '$view' => [
                'title' => 'Categories',
                'name'  => 'spqr/eventlist:views/admin/category-index.php',
            ],
            '$data' => [
                'statuses' => Category::getStatuses(),
                'config'   => [
                    'filter' => (object)$filter,
                    'page'   => $page,
                ],
            ],
        ];
    }
    
    /**
     * @Route("/category/edit", name="category/edit")
     * @Access("eventlist: manage categories")
     * @Request({"id": "int"})
     * @param int $id
     *
     * @return array
     */
    public function editAction($id = 0)
    {
        try {
            $module = App::module('spqr/eventlist');
            
            if (!$category = Category::where(compact('id'))->first()) {
                if ($id) {
                    App::abort(404, __('Invalid category id.'));
                }
                $category = Category::create([
                    'status' => Category::STATUS_DRAFT,
                ]);
            }
            
            return [
                '$view' => [
                    'title' => $id ? __('Edit Category') : __('Add Category'),
                    'name'  => 'spqr/eventlist:views/admin/category-edit.php',
                ],
                '$data' => [
                    'category' => $category,
                    'statuses' => Category::getStatuses(),
                ],
            ];
        } catch (\Exception $e) {
            App::message()->error($e->getMessage());
            
            return App::redirect('@eventlist/category');
        }
    }
}