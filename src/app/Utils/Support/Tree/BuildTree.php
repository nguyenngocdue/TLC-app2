<?php

namespace App\Utils\Support\Tree;

use App\Utils\CacheToRamForThisSection;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Timer;
use Illuminate\Support\Facades\DB;


class BuildTree
{
    protected static $key = 'my_company';
    protected static $fillableUser = ['id', 'name0', 'discipline', 'viewport_uids', 'leaf_uids', 'resigned', 'show_on_beta', 'time_keeping_type', 'department', 'workplace','is_bod'];
    protected static $fillableUserDiscipline = ['id', 'name', 'def_assignee'];
    private static function buildTree(array &$elements, $parentId = 0)
    {
        $branch = [];
        if (sizeof($elements) > 0) {
            foreach ($elements as $key => $element) {
                if ($element->parent_id == $parentId) {
                    $children = static::buildTree($elements, $element->id);
                    if ($children) {
                        $element->children = $children;
                    }
                    $branch[$key] = $element;
                    unset($element);
                }
            }
        }
        return $branch;
    }
    private static function arrayGenKey(array $object)
    {
        $result = [];
        foreach ($object as $value) {
            $result[$value->id] = $value;
        }
        return $result;
    }
    private static function getDataUsers()
    {
        $queryUsers =  DB::table('users')->select(static::$fillableUser)->get()->toArray();

        return static::arrayGenKey($queryUsers);
    }
    private static function getLeafs($ids)
    {
        $queryUsers =  DB::table('users')->whereIn('id', $ids)->select(static::$fillableUser)->get()->toArray();

        return static::arrayGenKey($queryUsers);
    }
    private static function getDataDisciplines()
    {
        $queryUserDisciplines = DB::table('user_disciplines')->select(static::$fillableUserDiscipline)->get()->toArray();

        return static::arrayGenKey($queryUserDisciplines);
    }
    private static function arrangeDataNow()
    {
        $disciplines = static::getDataDisciplines();
        $users = static::getDataUsers();
        foreach ($users as &$value) {
            $discipline = $value->discipline;
            if ($discipline) {
                $assignee = $disciplines[$discipline]->def_assignee;
                $value->parent_id = is_numeric($assignee) ? $assignee : 0;
            } else {
                $value->parent_id = 0;
            }
        }
        return $users;
    }
    private static function getTreeFollowedCurrentIdAndViewPort($tree, $currentId, $arrUIdViewPort, $onlyChildren)
    {
        $arrId = [];
        if ($arrUIdViewPort) {
            $arrId = explode(',', $arrUIdViewPort);
        }
        if (!$arrUIdViewPort) {
            $arrId[] = $currentId;
        }
        $result = [];

        foreach ($arrId as $value) {
            $result[] = static::findNodeValue($tree, $value, $onlyChildren);
        }
        return $result;
    }
    private static function getTreeFollowedLeaf($tree, $arrUIdLeaf)
    {
        $arrIdLeaf = [];
        if ($arrUIdLeaf) {
            $arrIdLeaf[] = explode(',', $arrUIdLeaf);
            $arrIdLeaf = array_unique(...$arrIdLeaf);
        }
        // $result = [];
        // foreach ($arrIdLeaf as $value) {

        //     $result[] = static::findNodeValueByUIDLeaf($tree, $value);
        // }
        return [static::getLeafs($arrIdLeaf)];
    }
    private static function flatten($tree)
    {
        $flatArray = [];
        foreach ($tree as $node) {
            if (isset($node->children)) {
                $value = static::flatten($node->children);
                $flatArray = array_merge($flatArray, $value);
                //<< cause unexpected behavior when get flattened and un-flattened in a same scope of function
                // unset($node->children);
            }
            $flatArray[] = $node;
        }
        return $flatArray;
    }
    private static function createTreeExpensive()
    {
        $users = static::arrangeDataNow();
        return static::buildTree($users);
    }
    public static function getAll()
    {
        $key = static::$key . '_of_the_app';
        return CacheToRamForThisSection::get($key, fn () => static::createTreeExpensive());
    }
    public static function getTree($flatten = false)
    {
        $result = static::getAll();
        if ($flatten) {
            return static::flatten($result);
        }
        return $result;
    }

    public static function getTreeById($id, $flatten = false)
    {
        $tree = static::getAll();
        $tree = static::findNodeValueById($tree, $id);
        if ($flatten) {
            return static::flatten($tree);
        }
        return $tree;
    }

    public static function getTreeByOptions($currentId = null, $arrUIdViewPort = null, $arrUIdLeaf = null, $onlyDirectChildren = false, $flatten = false)
    {
        $tree = static::getAll();
        $valueTreeCurrentIdAndViewPort = static::getTreeFollowedCurrentIdAndViewPort($tree, $currentId, $arrUIdViewPort, $onlyDirectChildren);
        $valueTreeLeaf = static::getTreeFollowedLeaf($tree, $arrUIdLeaf, $onlyDirectChildren);
        $result = array_merge(...$valueTreeCurrentIdAndViewPort, ...$valueTreeLeaf);
        if ($flatten) {
            return static::flatten($result);
        }
        return $result;
    }
    private static function findNodeValue($tree, $nodeValue, $onlyChildren = false)
    {
        if (!$nodeValue) {
            return $tree;
        }
        foreach ($tree as $key => $node) {
            if ($key == $nodeValue) {
                return $onlyChildren ? [$node] : ($node->children ?? []);
            } else {
                $isChildren = isset($node->children);
                if ($isChildren) {
                    $result = static::findNodeValue($node->children, $nodeValue);
                    if ($result) {
                        if (!$onlyChildren) {
                            return $result;
                        } else {
                            foreach ($result as $key => $value) {
                                unset($value->children);
                            }
                            return $result;
                        }
                    }
                }
            }
        }
        return [];
    }
    private static function findNodeValueById($tree, $nodeValue)
    {
        if (!$nodeValue) {
            return [];
        }
        foreach ($tree as $key => $node) {
            if ($key == $nodeValue) {
                return  [$node] ?? [];
            } else {
                $isChildren = isset($node->children);
                if ($isChildren) {
                    $result = static::findNodeValueById($node->children, $nodeValue);
                    if ($result) {
                        unset($result->children);
                        return $result;
                    }
                }
            }
        }
        return [];
    }

    public static function isApprovable($managerId, $creatorId)
    {
        if (CurrentUser::isAdmin()) return true;
        $tree = BuildTree::getTreeById($managerId, true);
        $staffIds = array_map(fn ($user) => $user->id, $tree);
        return in_array($creatorId, $staffIds);
    }
}
