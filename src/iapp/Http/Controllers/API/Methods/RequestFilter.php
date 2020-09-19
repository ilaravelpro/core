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
    public function requestFilter(Request $request, $model, $parent, $filters, $operators){
        if ($request->has('filter')){
            $filter = is_array($request->filter) ? (object)$request->filter : (object) json_decode($request->filter);
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
            if (method_exists($this, 'query_filter_type'))
                $this->query_filter_type($model, $filter, (object)['value' =>  $filter->value, 'type' => $ftype, 'symbol' => $fsymbol]);
            if (isset($filterOPT[0]) && !isset($filterOPT[0]['handel']))
                switch ($ftype) {
                    case 'all':
                        $request->validate(['filter.value' => ['string']]);
                        $this->searchQ(new Request(['q' => $filter->value]), $model, $parent);
                        $current['q'] = $request->q;
                        break;
                    default:
                        $request->validate([
                            'filter.value' => explode('|', $this->rules($request, 'store', null, $filter->type)),
                        ]);
                        $model->where($ftype, $fsymbol , $filter->value);
                        break;
                }
        }
    }
}
