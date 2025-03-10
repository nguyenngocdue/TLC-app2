<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_06 extends ModelExtended
{
    protected $fillable = ['id', 'name', 'parent_id', 'order_no', 'owner_id'];
    public static $statusless = true;

    public static $eloquentParams = [
        "attachment_1" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "comment_1" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_2" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_3" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        "comment_4" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],
        // "comment_5" => ['morphMany', Comment::class, 'commentable', 'commentable_type', 'commentable_id'],

        "signature_eco_peers" => ['morphMany', Signature::class, 'signable', 'signable_type', 'signable_id'],
        "signature_eco_managers" => ['morphMany', Signature::class, 'signable', 'signable_type', 'signable_id'],

        "signature_eco_peers_list" => ['belongsToMany', User::class, 'ym2m_user_zunit_test_06_peer_list',],
        "signature_eco_managers_list" => ['belongsToMany', User::class, 'ym2m_user_zunit_test_06_manager_list',],

        "getMonitors1" => ["belongsToMany", User::class, 'ym2m_user_zunit_test_06_monitor_1',],
    ];

    public function attachment_1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function signature_eco_peers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function signature_eco_managers()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function signature_1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function signature_2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function comment_1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_3()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function comment_4()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    // public function comment_5()
    // {
    //     $p = static::$eloquentParams[__FUNCTION__];
    //     $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
    //     return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    // }
    public function signature_eco_peers_list()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function signature_eco_managers_list()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
