<?php

namespace App\Models;

use App\BigThink\HasCachedAvatar;
use App\BigThink\ModelExtended;
use App\Utils\Support\CurrentRoute;
use Laravel\Sanctum\HasApiTokens;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;
use Ndc\SpatieCustom\Traits\HasRoleSets;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends ModelExtended implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    LdapAuthenticatable
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
    use AuthenticatesWithLdap;
    use HasLdapUser;
    use HasRoleSets;
    use HasApiTokens;
    use HasCachedAvatar;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name0", "full_name", "name_suffix", "employeeid", "first_name",
        "last_name", "gender", "address", "phone", "time_keeping_type", "user_type",
        "workplace", "current_workplace", "category", "erp_sub_cat",
        "date_of_birth", "first_date", "last_date", "leave_effective_date",
        "title", "position", "discipline", "department", "show_on_beta",
        "resigned", "viewport_uids", "leaf_uids", 'email_verified_at', "email", "password",
        "settings", "provider", "user_id_passport", "user_pin", "company", "owner_id",
        "is_bod", "org_chart", "standard_signature", "seniority_level",

        "erp_site", "erp_cashflow",
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

    protected $touches = [];
    public static $statusless = true;
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
        "getCurrentWorkplace" => ['belongsTo', Workplace::class, 'current_workplace'],
        "getUserType" => ['belongsTo', User_type::class, 'user_type'],
        "getUserCompany" => ['belongsTo', User_company::class, 'company'],
        "getUserCat" => ['belongsTo', User_category::class, 'category'],
        "getPosition" => ['belongsTo', User_position::class, 'position'],
        "getUserSeniorityLevel" => ['belongsTo', User_seniority_level::class, 'seniority_level'],

        "getUserErpSubCat" => ['belongsTo', User_sub_cat::class, 'erp_sub_cat'],
        "getUserErpSite" => ['belongsTo', Term::class, 'erp_site'],
        "getUserErpCashflow" => ['belongsTo', Term::class, 'erp_cashflow'],

        "getUserDiscipline" => ['belongsTo', User_discipline::class, 'discipline'],
        "getUserOrgChart" => ['belongsTo', User_org_chart::class, 'org_chart'],

        "getUserDepartment" => ['belongsTo', Department::class, 'department'],
        "getTimeKeepType" => ['belongsTo', User_time_keep_type::class, 'time_keeping_type'],

        "getPosts" => ['hasMany', Post::class, 'owner_id'],

        "getRoleSet" => ['morphToMany', Role_set::class, 'model', 'model_has_role_sets'],
        //This line is for ParentType to load,
        //Otherwise in User screen, the thumbnail will lost its value
        "attachment" => ['morphMany', Attachment::class, 'attachments', 'object_type', 'object_id'],
        "featured_image" => ['morphMany', Attachment::class, 'attachments', 'object_type', 'object_id'],
        //Many to many
        "getOtTeams" => ["belongsToMany", User_team_ot::class, "ym2m_user_team_ot_user_ot_member"],
        "getTshtTeams" => ["belongsToMany", User_team_tsht::class, "ym2m_user_team_tsht_user_tsht_member"],
        "getSiteTeams" => ["belongsToMany", User_team_site::class, "ym2m_user_team_site_user_site_member"],

        "getSubProjectsOfCouncilMember" => ['belongsToMany', Sub_project::class, "ym2m_sub_project_user_council_member"],
        "getSubProjectsOfProjectClient" => ['belongsToMany', Sub_project::class, "ym2m_sub_project_user_project_client"],
        "getSubProjectsOfExternalInspector" => ['belongsToMany', Sub_project::class, "ym2m_sub_project_user_ext_insp"],

        "getQaqcInspTmplsOfExternalInspector" => ['belongsToMany', Qaqc_insp_tmpl::class, "ym2m_qaqc_insp_tmpl_user_ext_insp"],
        "getQaqcInspTmplsOfCouncilMember" => ['belongsToMany', Qaqc_insp_tmpl::class, "ym2m_qaqc_insp_tmpl_user_council_member"],

        "getProdRoutingsOfExternalInspector" => ['belongsToMany', Prod_routing::class, "ym2m_prod_routing_user_ext_insp"],
        "getProdRoutingsOfCouncilMember" => ['belongsToMany', Prod_routing::class, "ym2m_prod_routing_user_council_member"],
    ];

    public function getOtTeams()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getTshtTeams()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSiteTeams()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getSubProjectsOfCouncilMember() /////////////
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getSubProjectsOfProjectClient() /////////////
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getSubProjectsOfExternalInspector() ///////
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getQaqcInspTmplsOfExternalInspector()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getQaqcInspTmplsOfCouncilMember()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getProdRoutingsOfExternalInspector()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    function getProdRoutingsOfCouncilMember()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
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
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserSeniorityLevel()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getWorkplace()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrentWorkplace()
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

    public function getUserErpSubCat()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserErpSite()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getUserErpCashflow()
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
    public function getManyIconParams()
    {
        return [
            'id' => 'id',
            'title' => 'name',
            'description' => 'position', //BUG
            'disabled' => 'resigned',
            'groupBy' => 'name',
        ];
    }
    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'id'],
            ['dataIndex' => 'name'],
            ['dataIndex' => 'position'],
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

    // static $singletonDbUserCollection = null;
    // public static function getCollection()
    // {
    //     if (!isset(static::$singletonDbUserCollection)) {
    //         $all = static::all();
    //         foreach ($all as $item) $indexed[$item->id] = $item;
    //         static::$singletonDbUserCollection = collect($indexed);
    //     }
    //     return static::$singletonDbUserCollection;
    // }

    // public static function findFromCache($id)
    // {
    //     return static::getCollection()[$id] ?? null;
    // }

    function isExternalInspector()
    {
        return in_array($this->discipline, [
            138, //138: External Inspector
            180, //180: Shipping Agent
        ]);
    }

    public function isProjectClient()
    {
        return in_array($this->discipline, [128]); //128: Project Client
    }

    public function isApartmentOwner()
    {
        return in_array($this->discipline, []); //: 
    }

    public function isCouncilMember()
    {
        return in_array($this->discipline, [169]); //169: Council Member
    }

    public function isExternal()
    {
        return $this->isExternalInspector() || $this->isProjectClient() || $this->isCouncilMember();
    }

    public function isNewcomer()
    {
        $roleSet = $this->getRoleSet[0]->name;
        return $roleSet == 'newcomer';
    }

    public function isAManager()
    {
        $d = User_discipline::query()->where('def_assignee', $this->id)->get();
        return sizeof($d) > 0;
    }

    public static function getByEmployeeId($employeeId)
    {
        return static::where('employeeid', $employeeId)->first();
    }

    public static function isAllowedDocType()
    {
        $plural = CurrentRoute::getTypePlural();
        // dump($plural);
        $allowed = [
            // 'qaqc_wirs',
            'qaqc_ncrs',
            'qaqc_mirs',
            // 'qaqc_punchlists',
            'qaqc_insp_chklsts',
            'qaqc_insp_chklst_shts',

            // 'dashboards',
        ];
        return (in_array($plural, $allowed));
    }

    public static function showProjectFilterByDocType()
    {
        $plural = CurrentRoute::getTypePlural();
        // dump($plural);
        $allowed = [
            // 'qaqc_wirs',
            'qaqc_ncrs',
            'qaqc_mirs',
            // 'qaqc_punchlists',
            // 'qaqc_insp_chklsts', // this doesn't have Project, only sub project, cant apply
            // 'qaqc_insp_chklst_shts', // this doesn't have Project, only sub project, cant apply

            // 'dashboards',
        ];
        return (in_array($plural, $allowed));
    }

    public function getAllowedSubProjectIds()
    {
        $allowedSubProjectIds = null;
        // dump($this->isProjectClient());
        // dump($this->isAllowedDocType());
        if ($this->isProjectClient() && $this->isAllowedDocType()) {
            $allowedSubProjectIds = $this->getSubProjectsOfProjectClient()
                ->pluck('sub_projects.id')
                ->toArray();
        }
        return $allowedSubProjectIds;
    }
}
