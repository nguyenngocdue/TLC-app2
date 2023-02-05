<?php

namespace App\Models;

use App\BigThink\HasAttachments;
use App\BigThink\HasStatus;
use App\BigThink\TraitMenuTitle;
use App\BigThink\TraitMetaForChart;
use App\BigThink\TraitMorphManyByFieldName;
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
    use Searchable;
    use CheckPermissionEntities;
    use HasApiTokens;
    use TraitMetaForChart;
    use TraitMenuTitle;
    use TraitMorphManyByFieldName;
    use HasAttachments;
    use HasStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name", "full_name", "name_suffix", "employeeid", "first_name",
        "last_name", "address", "phone", "featured_image", "time_keeping_type", "user_type", "workplace",
        "category", "date_of_birth", "first_date", "last_date", "title", "position_prefix", "position_1",
        "position_2", "position_3", "position_rendered", "discipline", "department", "show_on_beta",
        "resigned", "viewport_uids", "leaf_uids", 'email_verified_at', "email", "password", "settings", "provider", "user_id_passport", "user_pin",
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    protected $touches = [];

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
        "avatar" => ['morphOne', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "posts" => ['hasMany', Post::class, 'owner_id', 'id'],
        "getWorkplaces" => ['belongsTo', Workplace::class, 'workplace'],
        "userTypes" => ['belongsTo', User_type::class, 'user_type'],
        "categories" => ['belongsTo', User_category::class, 'category'],
        "positionPres" => ['belongsTo', User_position_pre::class, 'position_prefix'],
        "position1" => ['belongsTo', User_position1::class, 'position_1'],
        "position2" => ['belongsTo', User_position2::class, 'position_2'],
        "position3" => ['belongsTo', User_position3::class, 'position_3'],
        "disciplines" => ['belongsTo', User_discipline::class, 'discipline'],
        "departments" => ['belongsTo', Department::class, 'department'],
        "time_keep_types" => ['belongsTo', User_time_keep_type::class, 'time_keeping_type'],
        "productionRuns" => ['belongsToMany', Prod_run::class, 'prod_user_runs', 'user_id', 'prod_run_id'],
        "qaqcInspChklsts" => ['belongsTo', Qaqc_insp_chklst::class, 'owner_id'],
        "getRoleSet" => ['morphToMany', RoleSet::class, 'model', 'model_has_role_sets'],
    ];
    public $oracyParams = [];
    protected $guard_name = 'web';

    public function getRoleSet()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function avatar()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->latestOfMany();
    }
    public function posts()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
    public function getWorkplaces()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function userTypes()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function categories()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function positionPres()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function position1()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function position2()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function position3()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function disciplines()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function departments()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function time_keep_types()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function productionRuns()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->withPivot('user_id');
    }

    public function qaqcInspChklsts()
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
            ['dataIndex' => 'resigned', 'renderer' => 'toggle', "align" => "center"],
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

    public static function getTableName()
    {
        return (new static())->getTable();
    }
}
