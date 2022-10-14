<?php

namespace App\Models;

use App\Utils\PermissionTraits\CheckPermissionEntities;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\HasLdapUser;
use Laravel\Scout\Searchable;
use Ndc\Spatiecustom\Traits\HasRoleSets;

class User extends Authenticatable implements LdapAuthenticatable
{
    use Notifiable, AuthenticatesWithLdap, HasLdapUser, HasFactory, HasRoleSets, Searchable, CheckPermissionEntities, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name_rendered", "full_name", "name_suffix", "employeeid", "first_name",
        "last_name", "address", "phone", "featured_image", "time_keeping_type", "user_type", "workplace",
        "category", "date_of_birth", "first_date", "last_date", "title", "position_prefix", "position_1",
        "position_2", "position_3", "position_rendered", "role", "discipline", "department", "show_on_beta",
        "resigned", "viewport_uids", "leaf_uids", "email", "password", "settings", 'avatar',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ["password", "remember_token"];

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
        // "files" => ['hasMany' , Media::class],
        // "medias" => ['morphMany',Media::class, 'owner_id','id'],
        "media" => ['hasMany', Media::class, 'owner_id', 'id'],
        "posts" => ['hasMany', Post::class, 'owner_id', 'id'],
        // "userTypes" => ['hasMany', UserType::class, 'user_type'],
        "getWorkplace" => ['belongsTo', Workplace::class, 'workplace'],
        "userTypes" => ['hasMany', User_type::class, 'user_type'],
        "categories" => ['hasMany', User_category::class, 'category'],
        "positionPres" => ['hasMany', User_position_pre::class, 'position_prefix'],
        "position1" => ['hasMany', User_position1::class, 'position_1'],
        "position2" => ['hasMany', User_position2::class, 'position_2'],
        "position3" => ['hasMany', User_position3::class, 'position_3'],
        "disciplines" => ['hasMany', User_discipline::class, 'discipline'],
        "departments" => ['hasMany', Department::class, 'department'],
    ];
    protected $guard_name = 'web';
    // public function files()
    // {
    //     $p = $this->eloquentParams[__FUNCTION__];
    //     return $this->{$p[0]}($p[1]);
    // }
    public function media()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function posts()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }
    public function getWorkplace()
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
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'name_rendered' => $this->name_rendered,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ];
    }
}
