<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Erp_item extends Model
{
    // Specify the connection name
    protected $connection = 'sqlsrv';

    // Specify the table name with special characters
    // protected $table = '[TLC_PROD].[dbo].[TLC_LLC$Item$437dbf0e-84ff-417a-965d-ed2bb9650972]';
    protected $externalTable = 'TLC_PROD.dbo.TLC_LLC$Item$437dbf0e-84ff-417a-965d-ed2bb9650972';

    // Disable the default timestamps (created_at, updated_at)
    public $timestamps = false;

    // (Optional) Set the primary key if it's different from 'id'
    protected $primaryKey = 'your_primary_key';

    // (Optional) If your primary key is not an auto-incrementing integer
    public $incrementing = false;
    protected $keyType = 'string';
}
