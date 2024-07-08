<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 8:36 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use App\MaterialCode;
use iLaravel\Core\Vendor\iRole\iRole;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isFalse;

trait RequestFilter
{
    public function filters($request, $model, $parent = null, $operators = [])
    {
        $filters = [
            [
                'name' => 'all',
                'title' => _t('all'),
                'type' => 'text',
            ],
        ];
        foreach ($this->model::getTableColumns() as $column) {
            $filters[] = [
                'name' => $column,
                'title' => _t(str_replace('_id', $column)),
                'type' => 'text',
            ];
        }
        return [$filters, [], $operators];
    }
    public function requestFilter(Request $request, $model, $parent, $current, $filters, $operators)
    {
        $tableNameDot = $this->model::getTableNameDot();
        $req_filters = $request->filters && is_array($request->filters) && count($request->filters) ? $request->filters : ($request->filter ? [$request->filter] : []);
        if (count($req_filters))
            foreach ($req_filters as $index => $filter) {
                $filter = is_array($filter) ? (object)$filter : (object)json_decode($filter);
                $ftype = isset($filter->type) && in_array($filter->type, array_column($filters, 'name')) ? $filter->type : 'all';
                $filterOPT = array_values(array_filter($filters, function ($value) use ($ftype) {
                    return $value['name'] == $ftype;
                }));
                if (isset($filter->operator) && $filter->operator) {
                    $fsymbol = array_filter($operators, function ($value) use ($filter) {
                        return $filter->operator == $value['value'];
                    });
                    $fsymbol = count($fsymbol) > 0 ? array_shift($fsymbol)['symbol'] : $operators[0]['symbol'];
                } else
                    $fsymbol = '=';
                $rules = method_exists($this, 'rules') ? $this->rules($request, 'store') : $this->model::getRules($request, 'store');
                //$rule = str_replace(['required', 'nullable'], 'sometimes', _get_value($rules, $ftype) ? (_get_value($rules, $ftype) . '|string|integer'): 'nullable|string|integer');
                $rule = _get_value($rules, $ftype);
                if (isset($filterOPT[0]['rule']) && is_callable($filterOPT[0]['rule']))
                    $filter = (object)$filterOPT[0]['rule']($filter);
                elseif ($rule) {
                    if (isset($filter->cvalue))
                        (new Request(['filters' => (array)$req_filters]))->validate([
                            "filters.$index." . (@$filter->cvalue ? 'cvalue' : 'value') => explode('|', (isset($filterOPT[0]['rule']) ? $filterOPT[0]['rule'] : $rule)),
                        ]);
                }
                if (isset($filterOPT[0]) && !isset($filterOPT[0]['handel']) && isset($filter->value)) {
                    $fvalue = @$filter->cvalue ?? @$filter->value;
                    switch ($ftype) {
                        case 'all':
                            $this->searchQ(new Request(['q' => $fvalue]), $model, $parent);
                            $current['q'] = $filter->value;
                            break;
                        default:
                            if (method_exists($this, 'query_filter_type'))
                                $current = $this->query_filter_type($model, $filter, (object)['value' => @$filter->value, 'cvalue' => @$filter->cvalue, 'type' => $ftype, 'symbol' => $fsymbol], $current, $filters);
                            if (!isset($current[$ftype]) && $fvalue) {
                                switch ($fsymbol) {
                                    case 'like_any':
                                        $model->where($this->model::getTableNameDot() . $ftype, "like", "'%{$fvalue}%'");
                                        break;
                                    default:
                                        if (!@$filter->cvalue && (substr($ftype, -3, 3) === '_id' || isset($filterOPT[0]['with']) || isset($filterOPT[0]['pivot'])) && isset($filter->model) && $filter->model) {
                                            $model->whereHas(str_replace('_id', '', $ftype), function ($q) use($filter, $fvalue) {
                                                $tableNameDot = isset($filterOPT[0]['pivot']) ? 'pivot.': $filter->model::getTableNameDot();
                                                foreach ($filter->model::getTableColumns() as $column) {
                                                    if (in_array($column, ['id', 'parent_id']))
                                                        $q->whereIn($tableNameDot . $column, array_merge([@$filter->value], @$filter->kids ? $filter->kids->pluck('id')->toArray() : []));
                                                    else
                                                        $q->orWhere($tableNameDot . $column, 'LIKE', "%$filter->value%");
                                                }
                                            });
                                        } elseif (is_array($fvalue))
                                            $model->whereIn($tableNameDot . $ftype, $fvalue);
                                        else {
                                            $model->where($tableNameDot . $ftype, $fsymbol, $fvalue);
                                        }
                                        break;
                                }
                            }
                            break;
                    }
                }
                $current[_get_value((array)$filter, 'type')] = _get_value((array)$filter, 'value');
            }

        if ($request->excepts && is_array($request->excepts) && count($request->excepts)) {
            $excepts = array_unique(array_filter(array_map(function ($item) { return $this->model::id($item)?:$item; },$request->excepts), 'is_numeric'));
            if ($excepts) $model->whereNotIn('id', $excepts);
        }
        return [$filters, $current];
    }
}
