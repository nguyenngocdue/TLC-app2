<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Constant;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait TraitEntityAdvancedFilter
{
    private function convertDateTime($time)
    {
        return date('Y-m-d', strtotime(str_replace('/', '-', $time)));
    }

    private function advanceFilter($type = null)
    {
        $blackList = ['attachment', 'comment', 'relationship_renderer', 'thumbnail', 'parent_link'];
        $supperProps = SuperProps::getFor($type ?? $this->type);
        $propsFilters = array_filter($supperProps['props'], function ($item) use ($blackList) {
            if ($item['column_type'] === "static_heading") return false;
            if ($item['column_type'] === "static_control") return false;
            if (in_array($item['control'], $blackList)) return false;
            if ($item['hidden_filter'] === 'true') return false;
            return true;
        });
        // dump($propsFilters);
        return $propsFilters;
    }

    private function propsFilterCheckStatusless()
    {
        $propsFilters = $this->advanceFilter();
        if ($this->typeModel) {
            $isStatusless = ($this->typeModel)::isStatusless();
            if ($isStatusless) {
                unset($propsFilters['_status']);
            }
        }
        return $propsFilters;
    }

    private function distributeFilter($advanceFilters, $propsFilters)
    {
        $propsFilters = array_map(fn($item) => $item['control'], $propsFilters);
        if (!empty($advanceFilters)) {
            $advanceFilters = array_filter($advanceFilters, fn($item) => $item !== null);
            $result = [];
            foreach ($advanceFilters as $key => $value) {
                $control = $propsFilters['_' . $key] ?? '';
                switch ($control) {
                    case 'id':
                        $result['id'][$key] = $value;
                        break;
                    case 'doc_id':
                        $result['doc_id'][$key] = $value;
                        break;
                    case 'text':
                    case 'textarea':
                    case 'textarea_diff':
                    case 'textarea_diff_draft':
                    case 'number':
                        $result['text'][$key] = $value;
                        break;
                    case 'status':
                        $result['status'][$key] = $value;
                        break;
                    case 'entity_type':
                        $result['entity_type'][$key] = $value;
                        break;
                    case 'toggle':
                        $result['toggle'][$key] = $value;
                        break;
                    case 'dropdown':
                        $result['dropdown'][$key] = $value;
                        break;
                        // case 'checkbox':
                        //     case 'dropdown_multi':
                        //         $result['dropdown_multi'][$key] = $value;
                        //         break;
                    case 'radio':
                    case 'checkbox_2a':
                    case 'dropdown_multi_2a':
                        $result['dropdown_multi_2a'][$key] = $value;
                        // dump($result['dropdown_multi_2a']);
                        break;
                    case 'picker_time':
                        $result['picker_time'][$key] = $value;
                        break;
                    case 'picker_datetime':
                    case 'picker_date':
                        $result['picker_datetime'][$key] = $value;
                        break;
                    case 'picker_month':
                        $result['picker_month'][$key] = $value;
                        break;
                    case 'picker_week':
                        $result['picker_week'][$key] = $value;
                        break;
                    case 'picker_quarter':
                        $result['picker_quarter'][$key] = $value;
                        break;
                    case 'picker_year':
                        $result['picker_year'][$key] = $value;
                        break;
                    case 'parent_type':
                        $result['parent_type'][$key] = $value;
                        break;
                    case 'parent_id':
                        $result['parent_id'][$key] = $value;
                        break;
                    default:
                        break;
                }
            }
            return $result;
        }
        return false;
    }
    public function queryAdvancedFilter($q, $advanceFilters, $propsFilters)
    {
        if ($advanceFilters) {
            $queryResult = array_filter($advanceFilters, fn($item) => $item);
            // Log::info($queryResult);
            // Log::info($queryResult);
            array_walk($queryResult, function ($value, $key) use ($q, $propsFilters) {
                switch ($key) {
                    case 'id':
                    case 'parent_id':
                    case 'doc_id':
                        array_walk($value, function ($value, $key) use ($q) {
                            $arrayId = explode(',', $value);
                            if (!empty($arrayId)) {
                                $q->whereIn($key, $arrayId);
                            }
                        });
                        break;
                    case 'text':
                        array_walk($value, function ($value, $key) use ($q) {
                            $q->where($key, 'like', '%' . $value . '%');
                        });
                        break;
                    case 'toggle':
                        array_walk($value, function ($value, $key) use ($q) {
                            if ($value !== "null") {
                                $q->where($key, $value);
                            }
                        });
                        break;
                    case 'picker_time':
                        array_walk($value, function ($value, $key) use ($q) {
                            $arrayTime = explode(' - ', $value);
                            $q->whereTime($key, '>=', $arrayTime[0])
                                ->whereTime($key, '<=', $arrayTime[1]);
                        });
                        break;
                    case 'picker_month':
                        array_walk($value, function ($value, $key) use ($q) {
                            $arrayDate = explode(' - ', $value);
                            $fistDayOfMonth = DateTimeConcern::format2($arrayDate[0], Constant::FORMAT_MONTH)->startOfMonth()->toDateString();
                            $endDayOfMonth = DateTimeConcern::format2($arrayDate[1], Constant::FORMAT_MONTH)->endOfMonth()->toDateString();
                            $q->whereDate($key, '>=', $fistDayOfMonth)
                                ->whereDate($key, '<=', $endDayOfMonth);
                        });
                        break;
                    case 'picker_week':
                        array_walk($value, function ($value, $key) use ($q) {
                            $value = str_replace(['W', 'w'], '', $value);
                            $arrayDate = explode(' - ', $value);
                            $fistDayOfWeek = DateTimeConcern::formatWeek2($arrayDate[0])->startOfWeek(Carbon::SUNDAY)->toDateString();
                            $endDayOfWeek = DateTimeConcern::formatWeek2($arrayDate[1])->endOfWeek(Carbon::SATURDAY)->toDateString();
                            $q->whereDate($key, '>=', $fistDayOfWeek)
                                ->whereDate($key, '<=', $endDayOfWeek);
                        });
                        break;
                    case 'picker_quarter':
                        array_walk($value, function ($value, $key) use ($q) {
                            $value = str_replace(['Q', 'q'], '', $value);
                            $arrayDate = explode(' - ', $value);
                            $fistDayOfQuarter = DateTimeConcern::formatQuarter2($arrayDate[0])->startOfQuarter()->toDateString();
                            $endDayOfQuarter = DateTimeConcern::formatQuarter2($arrayDate[1])->endOfQuarter()->toDateString();
                            $q->whereDate($key, '>=', $fistDayOfQuarter)
                                ->whereDate($key, '<=', $endDayOfQuarter);
                        });
                        break;
                    case 'picker_year':
                        array_walk($value, function ($value, $key) use ($q) {
                            $arrayDate = explode(' - ', $value);
                            $fistDayOfYear = DateTimeConcern::format2($arrayDate[0], Constant::FORMAT_YEAR)->startOfYear()->toDateString();
                            $endDayOfYear = DateTimeConcern::format2($arrayDate[1], Constant::FORMAT_YEAR)->endOfYear()->toDateString();
                            $q->whereDate($key, '>=', $fistDayOfYear)
                                ->whereDate($key, '<=', $endDayOfYear);
                        });
                        break;
                    case 'picker_datetime':
                        array_walk($value, function ($value, $key) use ($q) {
                            $arrayDate = explode(' - ', $value);
                            $q->whereDate($key, '>=', $this->convertDateTime($arrayDate[0]))
                                ->whereDate($key, '<=', $this->convertDateTime($arrayDate[1]));
                        });
                        break;
                    case 'entity_type':
                    case 'dropdown':
                    case 'status':
                    case 'parent_type':
                        array_walk($value, function ($value, $key) use ($q) {
                            if (!is_array($value)) $value = [$value];
                            if (!in_array(null, $value)) {
                                $q->whereIn($key, $value);
                            }
                        });
                        break;
                        // case 'checkbox_2a':
                    case 'dropdown_multi_2a':
                        array_walk($value, function ($value, $key) use ($q, $propsFilters) {
                            $columnType = ($propsFilters["_$key"]['column_type']);
                            if ($columnType == 'belongsToMany') {
                                $tableName = ($propsFilters["_$key"]['relationships']['table']);
                                foreach ($value as $id) {
                                    $q->whereHas($key, function ($query) use ($value, $tableName, $id) {
                                        $query->where("$tableName.id", $id);
                                    }, '=', 1);
                                }
                            } else {
                                $q->whereIn($key, $value);
                            }
                        });
                        break;
                        // case 'dropdown_multi':
                        //     array_walk($value, function ($value, $key) use ($q, $propsFilters) {
                        //         $relationship = $propsFilters['_' . $key]['relationships'];
                        //         if (isset($relationship['oracyParams'])) {
                        //             $oracyParams = $relationship['oracyParams'];
                        //             $field = $key;
                        //             $fieldId = DB::table('fields')->where('name', str_replace('()', '', $field))->value('id');
                        //             $collectionFilter = DB::table('many_to_many')->where('field_id', $fieldId)
                        //                 ->where('term_type', $oracyParams[1])
                        //                 ->where('doc_type', $this->typeModel)
                        //                 ->whereIn('term_id', $value)
                        //                 ->get();
                        //             $valueFilter = $collectionFilter->map(function ($item) {
                        //                 return $item->doc_id;
                        //             })->toArray();
                        //             $q->whereIn('id', $valueFilter);
                        //         } else {
                        //             dump("Can not find oracyParams of $key, cancelled the criteria during filter.");
                        //         }
                        //     });
                        //     break;
                    default:
                        break;
                }
            });
        }
    }
}
