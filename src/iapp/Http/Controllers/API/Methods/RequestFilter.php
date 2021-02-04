<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/6/20, 12:03 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use Illuminate\Http\Request;

trait RequestFilter
{
    public function requestFilter(Request $request, $model, $parent, $current, $filters, $operators){
        $req_filters = $request->filters && is_array($request->filters) && count($request->filters) ? $request->filters : ($request->filter ? [$request->filter] : []);
        foreach ($req_filters as $index => $filter) {
            $filter = is_array($filter) ? (object)$filter : (object)json_decode($filter);
            $ftype = isset($filter->type) && in_array($filter->type, array_column($filters, 'name')) ? $filter->type : 'all';
            $filterOPT = array_values(array_filter($filters, function ($value) use ($ftype) {
                return $value['name'] == $ftype;
            }));
            if (isset($filter->operator) && $filter->operator){
                $fsymbol = array_filter($operators, function ($value) use ($filter) {
                    return $filter->operator == $value['value'];
                });
                $fsymbol = count($fsymbol) > 0 ? array_shift($fsymbol)['symbol'] : $operators[0]['symbol'];
            }
            else
                $fsymbol = '=';
            $rules = method_exists($this, 'rules') ? $this->rules($request, 'store', $model, $parent) : $this->model::getRules($request, 'store', $model, $parent);
            $rule = str_replace(['required'], ['nullable'], _get_value($rules, $ftype, 'nullable|string'));
            $request->validate([
                'filter.value' => explode('|', (isset($filterOPT[0]['rule']) ? $filterOPT[0]['rule'] : $rule)),
            ]);
            if (isset($filterOPT[0]) && !isset($filterOPT[0]['handel']))
                switch ($ftype) {
                    case 'all':
                        $request->validate(['filter.value' => ['string']]);
                        $this->searchQ(new Request(['q' => $filter->value]), $model, $parent);
                        $current['q'] = $request->q;
                        break;
                    default:
                        if (method_exists($this, 'query_filter_type'))
                            $current = $this->query_filter_type($model, $filter, (object)['value' =>  $filter->value, 'type' => $ftype, 'symbol' => $fsymbol], $current);
                        if (!isset($current[$ftype]))
                            $model->where($ftype, $fsymbol , $filter->value);
                        break;
                }
            $current[_get_value((array)$filter, 'type')] = _get_value((array)$filter, 'value');
        }
        return [$filters, $current];
    }
}
