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
use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;
use Laravel\Scout\Searchable;
use Ndc\SpatieCustom\Traits\HasRoleSets;

class User extends Authenticatable implements LdapAuthenticatable
{
    use Searchable;
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
        "name0", "full_name", "name_suffix", "employeeid", "first_name",
        "last_name", "gender", "address", "phone", "time_keeping_type", "user_type", "workplace",
        "category", "date_of_birth", "first_date", "last_date", "title",
        "position",
        "discipline", "department", "show_on_beta",
        "resigned", "viewport_uids", "leaf_uids", 'email_verified_at', "email", "password",
        "settings", "provider", "user_id_passport", "user_pin", "company", "owner_id",
        "is_bod", "org_chart", "standard_signature",
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    protected $touches = [];
    protected static $statusless = true;
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $name = $this->name0 . ($this->resigned ? " (RESIGNED)" : "") . ($this->show_on_beta ? " (BETA)" : "");
        //This will stop the queue from executing
        // if (CurrentUser::isLoggedIn() && CurrentUser::isAdmin()) $name .= " (#" . $this->id . ")";
        return $name;
    }

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

    public static $eloquentParams = [
        "getAvatar" => ['morphOne', Attachment::class, 'attachable', 'object_type', 'object_id'],
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace'],
        "getUserType" => ['belongsTo', User_type::class, 'user_type'],
        "getUserCompany" => ['belongsTo', User_company::class, 'company'],
        "getUserCat" => ['belongsTo', User_category::class, 'category'],
        "getPosition" => ['belongsTo', User_position::class, 'position'],

        "getUserDiscipline" => ['belongsTo', User_discipline::class, 'discipline'],
        "getUserOrgChart" => ['belongsTo', User_org_chart::class, 'org_chart'],

        "getUserDepartment" => ['belongsTo', Department::class, 'department'],
        "getTimeKeepType" => ['belongsTo', User_time_keep_type::class, 'time_keeping_type'],
        "getOwner" =>  ["belongsTo", User::class, "owner_id"],
        "getDeletedBy" =>  ["belongsTo", User::class, "deleted_by"],

        "getPosts" => ['hasMany', Post::class, 'owner_id', 'id'],

        "getRoleSet" => ['morphToMany', Role_set::class, 'model', 'model_has_role_sets'],
        //This line is for ParentType to load,
        //Otherwise in User screen, the thumbnail will lost its value
        "attachment" => ['morphMany', Attachment::class, 'attachments', 'object_type', 'object_id'],
        "featured_image" => ['morphMany', Attachment::class, 'attachments', 'object_type', 'object_id'],
    ];

    public static $oracyParams = [
        "getOtTeams()" => ["getCheckedByField", User_team_ot::class],
        "getTshtTeams()" => ["getCheckedByField", User_team_tsht::class],
        "getSiteTeams()" => ["getCheckedByField", User_team_site::class],

        "getSubProjectsOfExternalInspector()" => ['getCheckedByField', Sub_project::class],
        "getQaqcInspTmplsOfExternalInspector()" => ['getCheckedByField', Qaqc_insp_tmpl::class],
    ];

    public function getOtTeams()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getTshtTeams()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    public function getSiteTeams()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    function getSubProjectsOfExternalInspector()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    function getQaqcInspTmplsOfExternalInspector()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }

    protected $guard_name = 'web';

    public function getRoleSet()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getAvatar()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3], $p[4])->latestOfMany();
    }
    public function getPosts()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserCat()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserCompany()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPosition()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserDiscipline()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserOrgChart()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserDepartment()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTimeKeepType()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function featured_image()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }

    public function getDepartment()
    {
        return Department::findFromCache($this->department)->name ?? '';
    }
    public static function isStatusless()
    {
        return static::$statusless;
    }
    function getOwner()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getDeletedBy()
    {
        $p = static::$eloquentParams[__FUNCTION__];
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
            'name0' => $this->name0,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];
    }

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
        // if(!isset(static::getCollection()[$id]))
        return static::getCollection()[$id] ?? null;
    }

    public static function getTotalWorkingHoursOfYear($uids, $year)
    {
        // dump(join(",", $uids->toArray()));
        $uidsArray = join(",", $uids->toArray());
        $sql = "SELECT line.user_id, left(tsw.ts_date,7) month0, sum(line.duration_in_min)/60 working_hours
        FROM
            `hr_timesheet_lines` line,
            `hr_timesheet_workers` tsw
        WHERE 1=1
            AND tsw.id = line.timesheetable_id
            AND timesheetable_type='App\\\\Models\\\\Hr_timesheet_worker'
            AND tsw.deleted_at IS NULL
            AND line.deleted_at IS NULL
            AND LEFT(tsw.ts_date,4) = '$year'
            AND user_id IN ($uidsArray)
        GROUP BY month0, line.user_id
        ORDER BY working_hours DESC
        ";
        $rows = DB::select($sql);
        // Log::info($sql);
        $result = [];
        foreach ($rows as $row) {
            $result[$row->user_id . "_" . $row->month0] = $row;
        }
        return $result;
    }

    public static function getTotalOvertimeHoursOfYear($uids, $year)
    {
        // dump(join(",", $uids->toArray()));
        $uidsArray = join(",", $uids->toArray());
        $sql = "SELECT line.user_id, left(line.ot_date,7) month0, sum(line.total_time) ot_hours
        FROM `hr_overtime_request_lines` line
        WHERE 1=1
            AND line.deleted_at IS NULL
            AND line.user_id IN ($uidsArray)
            AND LEFT(line.ot_date, 4) = '$year'
        GROUP BY month0, line.user_id
        ORDER BY ot_hours DESC
        ";
        $rows = DB::select($sql);
        // Log::info($sql);
        $result = [];
        foreach ($rows as $row) {
            $result[$row->user_id . "_" . $row->month0] = $row;
        }
        return $result;
    }
}
