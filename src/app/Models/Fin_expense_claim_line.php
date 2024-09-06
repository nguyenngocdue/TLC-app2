<?php

namespace App\Models;

use App\BigThink\ModelExtended;

class Fin_expense_claim_line extends ModelExtended
{
    protected $fillable = [
        "id",
        "name",
        "description",
        "owner_id",
        "order_no",

        "expense_location_id",
        "expense_item_id",
        "gl_account_id",
        "invoice_date",
        "invoice_no",
        "quantity",
        "unit_price",
        "document_currency_id",
        "total_amount_0",
        "vat_product_posting_group_id",
        "vat_product_posting_group_value",
        "total_amount_1",

        "currency_pair_id",
        "rate_exchange_month_id",
        "counter_currency_id",
        "rate_exchange",

        "total_amount_lcy",
        "vendor_id",
        "vendor_name",
        "vendor_address",

        "claimable_type",
        "claimable_id",
    ];

    public static $nameless = true;

    public function getNameAttribute($value)
    {
        $location = "";
        if ($this->expense_location_id) {
            $location = "(" . strtoupper(substr($this->getExpenseLocation->name, 0, 2)) . ")";
        }
        if ($this->expense_item_id) {

            $item = $this->getExpenseItem;
            return  $item->name . " | " . $this->description . "|" . $location;
        }
        return "";
    }

    public static $eloquentParams = [
        "claimable" => ['morphTo', Fin_expense_claim_line::class, 'claimable_type', 'claimable_id'],
        "getExpenseLocation" => ['belongsTo', Term::class, 'expense_location_id'],
        "getExpenseItem" => ['belongsTo', Fin_expense_item::class, 'expense_item_id'],
        "getGlAccount" => ['belongsTo', Fin_gl_account::class, 'gl_account_id'],
        "getDocumentCurrency" => ['belongsTo', Act_currency::class, 'document_currency_id'],
        "getVatProductPostingGroup" => ['belongsTo', Fin_vat_product_posting_group::class, 'vat_product_posting_group_id'],

        'getCurrencyPair' => ['belongsTo', Act_currency_pair::class, 'currency_pair_id'],
        'getRateExchangeMonth' => ['belongsTo', Act_currency_xr::class, 'rate_exchange_month_id'],
        'getCounterCurrency' => ['belongsTo', Act_currency::class, 'counter_currency_id'],

        "getVendor" => ['belongsTo', Erp_vendor::class, 'vendor_id'],

    ];

    public function claimable()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2], $p[3]);
    }

    public function getExpenseLocation()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getExpenseItem()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getGlAccount()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getDocumentCurrency()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getVatProductPostingGroup()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getRateExchangeMonth()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCounterCurrency()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getCurrencyPair()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }


    public function getVendor()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getManyLineParams()
    {
        return [
            ['dataIndex' => 'order_no', 'invisible' => true],
            ['dataIndex' => 'id', 'invisible' => true],
            ['dataIndex' => 'claimable_type', 'title' => 'Parent Type', 'invisible' => true, 'value_as_parent_type' => true],
            ['dataIndex' => 'claimable_id', 'title' => 'Parent ID', 'invisible' => true, 'value_as_parent_id' => true],
            ['dataIndex' => 'name', 'invisible' => true],
            ['dataIndex' => 'expense_location_id'],
            ['dataIndex' => 'expense_item_id'],
            ['dataIndex' => 'gl_account_id', 'invisible' => !true,],
            ['dataIndex' => 'invoice_date', 'cloneable' => true,],
            ['dataIndex' => 'invoice_no', 'cloneable' => true,],
            ['dataIndex' => 'quantity', 'footer' => 'agg_sum',],
            ['dataIndex' => 'unit_price',],
            ['dataIndex' => 'document_currency_id', 'cloneable' => true,],
            ['dataIndex' => 'total_amount_0', 'footer' => 'agg_sum',],
            ['dataIndex' => 'vat_product_posting_group_id', 'cloneable' => true,],
            ['dataIndex' => 'vat_product_posting_group_value',],
            ['dataIndex' => 'total_amount_1', 'footer' => 'agg_sum',],


            ['dataIndex' => 'counter_currency_id', 'invisible' => true, 'read_only_rr2' => true],
            ['dataIndex' => 'currency_pair_id', 'invisible' => true,],
            ['dataIndex' => 'rate_exchange_month_id', 'invisible' => true, 'read_only_rr2' => true],

            ['dataIndex' => 'rate_exchange',],
            ['dataIndex' => 'total_amount_lcy', 'footer' => 'agg_sum',],
            // ['dataIndex' => 'vendor_id',],
            ['dataIndex' => 'vendor_name',],
            ['dataIndex' => 'vendor_address',],
        ];
    }
}
