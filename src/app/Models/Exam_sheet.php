<?php

namespace App\Models;

use App\BigThink\ModelExtended;
use App\Utils\Support\CurrentUser;

class Exam_sheet extends ModelExtended
{
    public $fillable = [
        "id", "name", "description", "owner_id", "status",
        "employee_name",
        "employee_id",
        "employee_position",
        "employee_department",
        "employee_manager",
        "exam_tmpl_id", "comment",
    ];

    // public static $statusless = true;
    public static $nameless = true;
    public function getNameAttribute($value)
    {
        $exam = $this->getExamTmpl;
        return ($exam ? $exam->name : "") . " - " . $this->getOwner->name;
    }

    public static $eloquentParams = [
        "getExamTmpl" => ["belongsTo", Exam_tmpl::class, "exam_tmpl_id"],
        "getSheetLines" => ["hasMany", Exam_sheet_line::class, "exam_sheet_id"],
    ];

    public static $oracyParams = [];

    public function getExamTmpl()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getSheetLines()
    {
        $p = static::$eloquentParams[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public static function groupByQuestionId($lines)
    {
        $grouped = [];
        foreach ($lines as $line) {
            $grouped[$line->exam_question_id][$line->sub_question_1_id ?? 0][$line->sub_question_2_id ?? 0] = $line;
        }
        // dd($grouped);
        return $grouped;
    }

    public static function getQuestionsOfSheet($sheet)
    {
        $exam_tmpl_id = $sheet->exam_tmpl_id;
        $dataSource = Exam_tmpl_question::query()
            ->where("exam_tmpl_id", $exam_tmpl_id)
            ->with('getExamTmplGroup')
            ->orderBy('order_no')
            ->get();
        // dump($dataSource);

        $isAManager =  CurrentUser::get()->isAManager();
        // $isAManager = true;
        $HIDE_WHEN_I_AM_NOT_A_MANAGER = 400;

        foreach ($dataSource as $line) {
            if (!$line->exam_tmpl_group_id) {
                dd("What is the group of question [$line->id] ?");
            }
        }

        $dataSource = $dataSource->filter(fn ($i) => (($i->getExamTmplGroup->hide_when) != $HIDE_WHEN_I_AM_NOT_A_MANAGER || $isAManager));
        return $dataSource;
    }
}
