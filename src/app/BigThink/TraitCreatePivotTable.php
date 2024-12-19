<?php

namespace App\BigThink;

use App\BigThink\BlueprintExtended;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait TraitCreatePivotTable
{
    private function getNames()
    {
        $table1Plural = $this->table1Plural;
        $table2Plural = $this->table2Plural;
        $relationshipKey = $this->relationshipKey;

        if ($table1Plural > $table2Plural) {
            // Log::info("$table1Plural > $table2Plural");
            $temp = $table1Plural;
            $table1Plural = $table2Plural;
            $table2Plural = $temp;
        }

        $table1Singular = Str::singular($table1Plural);
        $table2Singular = Str::singular($table2Plural);
        if (isset($this->tableName)) {
            $tableName = $this->tableName;
        } else {
            $tableName = "ym2m_{$table1Singular}_{$table2Singular}" . ($relationshipKey ? "_$relationshipKey" : '');
        }
        $table1PostFix = isset($this->table1IdColumn) ? '_' . $this->table1IdColumn : '_id';
        $table2PostFix = isset($this->table2IdColumn) ? '_' . $this->table2IdColumn : '_id';

        $table1IdColumn = $table1Singular . $table1PostFix;
        $table2IdColumn = $table2Singular . $table2PostFix;

        return [$tableName, $table1Plural, $table2Plural, $table1IdColumn, $table2IdColumn];
    }

    public function schemaDown()
    {
        [$tableName] = $this->getNames();
        Schema::dropIfExists($tableName);
    }

    public function schemaUp()
    {
        [$tableName, $table1Plural, $table2Plural, $table1IdColumn, $table2IdColumn] = $this->getNames();

        $schema = DB::connection()->getSchemaBuilder();
        $schema->blueprintResolver(fn($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create($tableName, function (BlueprintExtended $table)
        use ($table1Plural, $table2Plural, $table1IdColumn, $table2IdColumn) {
            $table1 = $table1Plural; //"workplaces";
            $key1 = $table1IdColumn; //"workplace_id";
            $table2 = $table2Plural; //"zunit_test_01s";
            $key2 = $table2IdColumn; // "zunit_test_01_id";
            $key3 = $this->relationshipKey; //"checkbox";

            $table->id();
            $foreignColumn1 = isset($this->table1IdColumn) ? $this->table1IdColumn : 'id';
            $table->unsignedBigInteger($key1)->nullable();
            $table->foreign($key1, "$key1|$key2|$key3")->references($foreignColumn1)->on($table1)->onDelete('cascade')->onUpdate('cascade');

            $foreignColumn2 = isset($this->table2IdColumn) ? $this->table2IdColumn : 'id';
            $table->unsignedBigInteger($key2)->nullable();
            $table->foreign($key2, "$key2|$key1|$key3")->references($foreignColumn2)->on($table2)->onDelete('cascade')->onUpdate('cascade');

            $table->unique([$key1, $key2], md5($key1 . $key2 . $key3));

            $this->schemaPivot($table);

            $table->unsignedBigInteger('owner_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function schemaPivot(BlueprintExtended $table)
    {
        //This function is for child migration to override
    }
}
