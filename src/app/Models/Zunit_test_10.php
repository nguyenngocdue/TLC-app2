<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Zunit_test_10 extends ModelExtended
{
    protected $fillable = ['id'];
    protected $primaryKey = 'id';
    protected $table = 'zunit_test_10s';

    public $eloquentParams = [
        'getCorrectiveActions' => ['hasMany', Hse_corrective_action::class, 'hse_incident_report_id'],
    ];

    public function getCorrectiveActions()
    {
        $p = $this->eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
