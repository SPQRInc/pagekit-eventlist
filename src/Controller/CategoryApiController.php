<?php

namespace Spqr\Eventlist\Controller;

use Pagekit\Application as App;
use Spqr\Eventlist\Model\Category;

/**
 * @Access("eventlist: manage categories")
 * @Route("category", name="category")
 */
class CategoryApiController
{
    /**
     * @param array $filter
     * @param int   $page
     * @param int   $limit
     * @Route("/", methods="GET")
     * @Request({"filter": "array", "page":"int", "limit":"int"})
     *
     * @return mixed
     */
    public function indexAction($filter = [], $page = 0, $limit = 0)
    {
        $query  = Category::query();
        $filter = array_merge(array_fill_keys([
            'status',
            'search',
            'limit',
            'order',
        ], ''), $filter);
        extract($filter, EXTR_SKIP);
        if (is_numeric($status)) {
            $query->where(['status' => (int)$status]);
        }
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere([
                    'title LIKE :search',
                ], ['search' => "%{$search}%"]);
            });
        }
        if (preg_match('/^(title)\s(asc|desc)$/i', $order, $match)) {
            $order = $match;
        } else {
            $order = [1 => 'title', 2 => 'asc'];
        }
        $default    = App::module('spqr/eventlist')->config('items_per_page');
        $limit      = min(max(0, $limit), $default) ? : $default;
        $count      = $query->count();
        $pages      = ceil($count / $limit);
        $page       = max(0, min($pages - 1, $page));
        $categories = array_values($query->offset($page * $limit)->limit($limit)
            ->orderBy($order[1], $order[2])->get());
        
        return compact('categories', 'pages', 'count');
    }
    
    /**
     * @Route("/{id}", methods="GET", requirements={"id"="\d+"})
     * @param $id
     *
     * @return static
     */
    public function getAction($id)
    {
        if (!$category = Category::where(compact('id'))->first()) {
            App::abort(404, 'Category not found.');
        }
        
        return $category;
    }
    
    /**
     * @Route(methods="POST")
     * @Request({"ids": "int[]"}, csrf=true)
     * @param array $ids
     *
     * @return array
     */
    public function copyAction($ids = [])
    {
        foreach ($ids as $id) {
            if ($category = Category::find((int)$id)) {
                if (!App::user()->hasAccess('eventlist: manage categories')) {
                    continue;
                }
                $category         = clone $category;
                $category->id     = null;
                $category->status = $category::STATUS_UNPUBLISHED;
                $category->title  = $category->title.' - '.__('Copy');
                $category->save();
            }
        }
        
        return ['message' => 'success'];
    }
    
    /**
     * @Route("/bulk", methods="POST")
     * @Request({"categories": "array"}, csrf=true)
     * @param array $categories
     *
     * @return array
     */
    public function bulkSaveAction($categories = [])
    {
        foreach ($categories as $data) {
            $this->saveAction($data, isset($data['id']) ? $data['id'] : 0);
        }
        
        return ['message' => 'success'];
    }
    
    /**
     * @Route("/", methods="POST")
     * @Route("/{id}", methods="POST", requirements={"id"="\d+"})
     * @Request({"category": "array", "id": "int"}, csrf=true)
     */
    public function saveAction($data, $id = 0)
    {
        if (!$id || !$category = Category::find($id)) {
            if ($id) {
                App::abort(404, __('Category not found.'));
            }
            $category = Category::create();
        }
        if (!$data['slug'] = App::filter($data['slug'] ? : $data['title'],
            'slugify')
        ) {
            App::abort(400, __('Invalid slug.'));
        }
        
        $category->save($data);
        
        return ['message' => 'success', 'category' => $category];
    }
    
    /**
     * @Route("/bulk", methods="DELETE")
     * @Request({"ids": "array"}, csrf=true)
     * @param array $ids
     *
     * @return array
     */
    public function bulkDeleteAction($ids = [])
    {
        foreach (array_filter($ids) as $id) {
            $this->deleteAction($id);
        }
        
        return ['message' => 'success'];
    }
    
    /**
     * @Route("/{id}", methods="DELETE", requirements={"id"="\d+"})
     * @Request({"id": "int"}, csrf=true)
     * @param $id
     *
     * @return array
     */
    public function deleteAction($id)
    {
        if ($category = Category::find($id)) {
            if (!App::user()->hasAccess('eventlist: manage categories')) {
                App::abort(400, __('Access denied.'));
            }
            $category->delete();
        }
        
        return ['message' => 'success'];
    }
    
}