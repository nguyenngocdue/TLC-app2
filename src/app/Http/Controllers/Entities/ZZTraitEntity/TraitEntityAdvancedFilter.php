<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Helpers\Helper;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\DB;

trait TraitEntityAdvancedFilter
{
    private function advanceFilter($type = null)
    {
        $blackList = ['attachment', 'comment', 'relationship_renderer', 'thumbnail', 'parent_link'];
        $supperProps = SuperProps::getFor($type ?? $this->type);
        $propsFilters = array_filter($supperProps['props'], function ($item) use ($blackList) {
            if ($item['column_type'] === "static") {
                return false;
            } else {
                if (in_array($item['control'], $blackList)) return false;
                else {
                    if ($item['hidden_filter'] === 'true') return false;
                }
            }
            return true;
        });
        return $propsFilters;
    }
    private function distributeFilter($advanceFilters, $propsFilters)
    {
        $propsFilters = array_map(fn ($item) => $item['control'], $propsFilters);
        if (!empty($advanceFilters)) {
            $advanceFilters = array_filter($advanceFilters, fn ($item) => $item);
            $result = [];
            foreach ($advanceFilters as $key => $value) {
                switch ($propsFilters['_' . $key]) {
                    case 'id':
                        $result['id'][$key] = $value;
                        break;
                    case 'doc_id':
                        $result['doc_id'][$key] = $value;
                        break;
                    case 'text':
                    case 'textarea':
                    case 'number':
                        $result['text'][$key] = $value;
                        break;
                    case 'status':
                        $result['status'][$key] = $value;
                        break;
                    case 'toggle':
                        $result['toggle'][$key] = $value;
                        break;
                    case 'dropdown':
                        $result['dropdown'][$key] = $value;
                        break;
                    case 'radio':
                    case 'checkbox':
                    case 'dropdown_multi':
                        $result['dropdown_multi'][$key] = $value;
                        break;
                    case 'picker_time':
                        $result['picker_time'][$key] = $value;
                        break;
                    case 'picker_datetime':
                    case 'picker_date':
                    case 'picker_month':
                    case 'picker_week':
                    case 'picker_quarter':
                    case 'picker_year':
                        $result['picker_datetime'][$key] = $value;
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
            $queryResult = array_filter($advanceFilters, fn ($item) => $item);
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
                            $q->where($key, $value);
                        });
                        break;
                    case 'picker_time':
                        array_walk($value, function ($value, $key) use ($q) {
                            $arrayTime = explode(' - ', $value);
                            $q->whereTime($key, '>=', $arrayTime[0])
                                ->whereTime($key, '<=', $arrayTime[1]);
                        });
                        break;
                    case 'picker_datetime':
                        array_walk($value, function ($value, $key) use ($q) {
                            $arrayDate = explode(' - ', $value);
                            $q->whereDate($key, '>=', $this->convertDateTime($arrayDate[0]))
                                ->whereDate($key, '<=', $this->convertDateTime($arrayDate[1]));
                        });
                        break;
                    case 'dropdown':
                    case 'status':
                    case 'parent_type':
                        array_walk($value, function ($value, $key) use ($q) {
                            $q->whereIn($key, $value);
                        });
                        break;
                    case 'dropdown_multi':
                        array_walk($value, function ($value, $key) use ($q, $propsFilters) {
                            $relationship = $propsFilters['_' . $key]['relationships'];
                            $oracyParams = $relationship['oracyParams'];
                            $field = $key;
                            $fieldId = DB::table('fields')->where('name', str_replace('()', '', $field))->value('id');
                            $collectionFilter = DB::table('many_to_many')->where('field_id', $fieldId)
                                ->where('term_type', $oracyParams[1])
                                ->where('doc_type', $this->typeModel)
                                ->whereIn('term_id', $value)
                                ->get();
                            $valueFilter = $collectionFilter->map(function ($item) {
                                return $item->doc_id;
                            })->toArray();
                            $q->whereIn('id', $valueFilter);
                        });
                        break;
                    default:
                        break;
                }
            });
        }
    }
}
