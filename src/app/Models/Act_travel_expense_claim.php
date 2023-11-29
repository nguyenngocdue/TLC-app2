<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Act_travel_expense_claim extends ModelExtended
{
    protected $fillable = [
        "id", "name", "description", "status", "user_id",
        "advance_req_id", "advance_amount", "currency1_id", "currency_pair1_id",
        "rate_exchange_advance", "total_advance_amount", "travel_req_id", "travel_amount",
        "currency2_id", "currency_pair2_id", "rate_exchange_travel", "total_travel_amount", "rate_exchange_month_id",
        "counter_currency_id", "total_amount_ee", "total_amount_re", "remark", "assignee_1", "owner_id",
        "reimbursement_in_words"
    ];

    public static $eloquentParams = [
        'getUser' => ['belongsTo', User::class, 'user_id'],
        'getAdvanceTravel' => ['belongsTo', Act_advance_req::class, 'advance_req_id'],
        'getTravelReq' => ['belongsTo', Act_travel_req::class, 'travel_req_id'],

        'getCurrency1' => ['belongsTo', Act_currency::class, 'currency1_id'],
        'getCurrencyPair1' => ['belongsTo', Act_currency_pair::class, 'currency_pair1_id'],
        'getCurrency2' => ['belongsTo', Act_currency::class, 'currency2_id'],
        'getCurrencyPair2' => ['belongsTo', Act_currency_pair::class, 'currency_pair2_id'],
        'getCounterCurrency' => ['belongsTo', Act_currency::class, 'counter_currency_id'],

        'getTravelExpenseClaimLines' => ['hasMany', Act_travel_expense_claim_line::class, 'travel_expense_claim_id'],
        'getRateExchangeMonth' => ['belongsTo', Act_currency_xr::class, 'rate_exchange_month_id'],
        "attachment_doc_travel_expense_claim" => ['morphMany', Attachment::class, 'attachable', 'object_type', 'object_id'],
        'getAssignee1' => ['belongsTo', User::class, 'assignee_1'],
    ];

    public static $oracyParams = [
        "getMonitors1()" => ["getCheckedByField", User::class],
        'getSubProjectsOfExpenseClaim()' => ['getCheckedByField', Sub_project::class,],
    ];

    public function getSubProjectsOfExpenseClaim()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
    public function getUser()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTravelExpenseClaimLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getRateExchangeMonth()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function attachment_doc_travel_expense_claim()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        $relation = $this->{$p[0]}($p[1], $p[2], $p[3], $p[4]);
        return $this->morphManyByFieldName($relation, __FUNCTION__, 'category');
    }
    public function getAdvanceTravel()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getTravelReq()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrency1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrencyPair1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrency2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCurrencyPair2()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getCounterCurrency()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getAssignee1()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
    public function getMonitors1()
    {
        $p = static::$oracyParams[__FUNCTION__ . '()'];
        return $this->{$p[0]}(__FUNCTION__, $p[1]);
    }
}
