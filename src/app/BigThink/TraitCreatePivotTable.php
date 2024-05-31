<?php

namespace App\BigThink;

use App\BigThink\BlueprintExtended;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait TraitCreatePivotTable
{
    private function getNames()
    {
        $table1Plural = $this->table1Plural;
        $table2Plural = $this->table2Plural;
        $relationshipKey = $this->relationshipKey;

        if ($table1Plural > $table2Plural) {
            $temp = $table1Plural;
            $table1Plural = $table2Plural;
            $table2Plural = $temp;
        }

        $table1Singular = Str::singular($table1Plural);
        $table2Singular = Str::singular($table2Plural);
        $tableName = "ym2m_{$table1Singular}_{$table2Singular}" . ($relationshipKey ? "_$relationshipKey" : '');
        $table1IdColumn = $table1Singular . '_id';
        $table2IdColumn = $table2Singular . '_id';

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
        $schema->blueprintResolver(fn ($table, $callback) => new BlueprintExtended($table, $callback));

        $schema->create($tableName, function (BlueprintExtended $table)
        use ($table1Plural, $table2Plural, $table1IdColumn, $table2IdColumn) {
            $table1 = $table1Plural; //"workplaces";
            $key1 = $table1IdColumn; //"workplace_id";
            $table2 = $table2Plural; //"zunit_test_01s";
            $key2 = $table2IdColumn; // "zunit_test_01_id";
            $key3 = $this->relationshipKey; //"checkbox";

            $table->id();
            $table->unsignedBigInteger($key1);
            $table->foreign($key1, "$key1+$key2+$key3")->references('id')->on($table1)->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger($key2);
            $table->foreign($key2, "$key2+$key1+$key3")->references('id')->on($table2)->onDelete('cascade')->onUpdate('cascade');

            $table->unique([$key1, $key2], md5($key1 . $key2 . $key3));

            $table->unsignedBigInteger('owner_id');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }
}
