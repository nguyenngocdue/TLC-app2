<?php

namespace App\Models;

use App\BigThink\HasAttachments;
use App\BigThink\HasCachedAvatar;
use App\BigThink\HasCheckbox;
use App\BigThink\HasStatus;
use App\BigThink\SoftDeletesWithDeletedBy;
use App\BigThink\TraitMenuTitle;
use App\BigThink\TraitMetaForChart;
use App\BigThink\TraitModelExtended;
use App\BigThink\TraitMorphManyByFieldName;
use App\Utils\OptimisticLocking\TraitOptimisticLocking;
use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;
use Laravel\Scout\Searchable;
use Ndc\SpatieCustom\Traits\HasRoleSets;

class User extends Authenticatable implements LdapAuthenticatable
{
    use Notifiable;
    use AuthenticatesWithLdap;

    use HasLdapUser;
    use HasFactory;
    use HasRoleSets;
    use HasApiTokens;

    use Searchable;
    use CheckPermissionEntities;
    use TraitMetaForChart;
    use TraitMenuTitle;
    use TraitMorphManyByFieldName;
    use TraitOptimisticLocking;
    use TraitModelExtended;
    use SoftDeletesWithDeletedBy;

    use HasAttachments;
    use HasCachedAvatar;
    use HasStatus;
    use HasCheckbox;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name", "full_name", "name_suffix", "employeeid", "first_name",
        "last_name", "address", "phone", "time_keeping_type", "user_type", "workplace",
        "category", "date_of_birth", "first_date", "last_date", "title", "position_prefix", "position_1",
        "position_2", "position_3", "position_rendered", "discipline", "department", "show_on_beta",
        "resigned", "viewport_uids", "leaf_uids", 'email_verified_at', "email", "password",
        "settings", "provider", "user_id_passport", "user_pin", "company", "owner_id",
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    protected $touches = [];
    protected static $statusless = true;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "email_verified_at" => "datetime",
        "settings" => "array"
    ];

    // public function getLdapDomainColumn()
    // {
    //     return 'my_domain_column';
    // }

    // public function getLdapGuidColumn()
    // {
    //     return 'my_guid_column';
    // }

    public $eloquentParams = [
        "getAvatar" => ['morphOne', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace'],
        "getUserType" => ['belongsTo', User_type::class, 'user_type'],
        "getUserCompany" => ['belongsTo', User_company::class, 'company'],
        "getUserCat" => ['belongsTo', User_category::class, 'category'],
        "getPositionPrefix" => ['belongsTo', User_position_pre::class, 'position_prefix'],
        "getPosition1" => ['belongsTo', User_position1::class, 'position_1'],
        "getPosition2" => ['belongsTo', User_position2::class, 'position_2'],
        "getPosition3" => ['belongsTo', User_position3::class, 'position_3'],
        "getUserDiscipline" => ['belongsTo', User_discipline::class, 'discipline'],
        "getUserDepartment" => ['belongsTo', Department::class, 'department'],
        "getTimeKeepType" => ['belongsTo', User_time_keep_type::class, 'time_keeping_type'],
        "getOwner" =>  ["belongsTo", User::class, "owner_id"],
        "getDeletedBy" =>  ["belongsTo", User::class, "deleted_by"],

        "getPosts" => ['hasMany', Post::class, 'owner_id', 'id'],

        "getRoleSet" => ['morphToMany', Role_set::class, 'model', 'model_has_role_sets'],
        // "productionRuns" => ['belongsToMany', Prod_run::class, 'prod_user_runs', 'user_id', 'prod_run_id'],
        // "getQaqcInspChklsts" => ['belongsTo', Qaqc_insp_chklst::class, 'owner_id'],
        //This line is for ParentType to load,
        //Otherwise in User screen, the thumbnail will lost its value
        "attachment" => ['morphMany', Attachment::class, 'attachments', 'object_type', 'object_id'],
        "featured_image" => ['morphMany', Attachment::class, 'attachments', 'object_type', 'object_id'],
    ];

    public $oracyParams = [
        "getOtTeams()" => ["getCheckedByField", User_team_ot::class],
    ];

    public function getOtTeams()
    {
        $p = $this->oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    protected $guard_name = 'web';

    public function getRoleSet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getAvatar()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->latestOfMany();
    }
    public function getPosts()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
    public function getWorkplace()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserCat()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserCompany()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPositionPrefix()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPosition1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPosition2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getPosition3()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserDiscipline()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserDepartment()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTimeKeepType()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function featured_image()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    // public function productionRuns()
    // {
    //     $p = $this->eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('user_id');
    // }

    // public function getQaqcInspChklsts()
    // {
    //     $p = $this->eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1], $p[2]);
    // }
    // public static function getAllInspector()
    // {
    //     $disciplineInspector = 23;
    //     return self::where('discipline', $disciplineInspector)->where('resigned', 0)->get();
    // }
    public static function isStatusless()
    {
        return static::$statusless;
    }
    function getOwner()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getDeletedBy()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getManyIconParams()
    {
        return [
            'id' => 'id',
            'title' => 'name',
            'description' => 'position_rendered',
            'disabled' => 'resigned',
            'groupBy' => 'name',
        ];
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'position_rendered'],
            ['dataIndex' => 'resigned'],
        ];
    }
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];
    }

    // Override Model find

    // static $userDbSingleton = [];
    // public static function findFromCache($id)
    // {
    //     // return parent::find($id);
    //     if (!isset(static::$userDbSingleton[$id])) {
    //         static::$userDbSingleton[$id] = static::find($id);
    //     }
    //     return static::$userDbSingleton[$id];
    // }

    static $singletonDbUserCollection = null;
    public static function getCollection()
    {
        if (!isset(static::$singletonDbUserCollection)) {
            $all = static::all();
            foreach ($all as $item) $indexed[$item->id] = $item;
            static::$singletonDbUserCollection = collect($indexed);
        }
        return static::$singletonDbUserCollection;
    }

    public static function findFromCache($id)
    {
        return static::getCollection()[$id];
    }
}
