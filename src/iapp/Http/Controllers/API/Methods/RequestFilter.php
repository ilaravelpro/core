<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 8:36 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\Core\iApp\Http\Controllers\API\Methods;

use iLaravel\Core\Vendor\iRole\iRole;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isFalse;

trait RequestFilter
{
    public function requestFilter(Request $request, $model, $parent, $current, $filters, $operators){
        $req_filters = $request->filters && is_array($request->filters) && count($request->filters) ? $request->filters : ($request->filter ? [$request->filter] : []);
        if (count($req_filters))
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
                $rules = method_exists($this, 'rules') ? $this->rules($request, 'store') : $this->model::getRules($request, 'store');
                $rule = str_replace(['required'], ['nullable'], _get_value($rules, $ftype) ? (_get_value($rules, $ftype)): 'nullable|string');
                if (isset($filterOPT[0]['rule']) && is_callable($filterOPT[0]['rule']))
                    $filter = (object) $filterOPT[0]['rule']($filter);
                else{
                    if(isset($filter->cvalue))
                        (new Request(['filters' => (array) $req_filters]))->validate([
                            "filters.$index." . (@$filter->cvalue? 'cvalue' : 'value') => explode('|', (isset($filterOPT[0]['rule']) ? $filterOPT[0]['rule'] : $rule)),
                        ]);
                }
                if (isset($filterOPT[0]) && !isset($filterOPT[0]['handel']) && isset($filter->value)) {
                    $fvalue = @$filter->cvalue??@$filter->value;
                    switch ($ftype) {
                        case 'all':
                            $this->searchQ(new Request(['q' => $fvalue]), $model, $parent);
                            $current['q'] = $filter->value;
                            break;
                        default:
                            if (method_exists($this, 'query_filter_type'))
                                $current = $this->query_filter_type($model, $filter, (object)['value' => @$filter->value, 'cvalue' =>  @$filter->cvalue, 'type' => $ftype, 'symbol' => $fsymbol], $current, $filters);
                            if (!isset($current[$ftype]) && $fvalue) {
                                switch ($fsymbol) {
                                    case 'like_any':
                                        $model->whereRaw("$ftype like '%{$fvalue}%'");
                                        break;
                                    default:
                                        $model->whereRaw("$ftype $fsymbol " . (is_integer($fvalue) ?  $fvalue: "'{$fvalue}'"));
                                        break;
                                }
                            }
                            break;
                    }
                }
                $current[_get_value((array)$filter, 'type')] = _get_value((array)$filter, 'value');
            }
        if ($this->model::hasTableColumn('parent_id') && $request->no_check_parent != 1) {
            $parentSet = $request->has('parent') ? (boolean) $request->parent : true;
            if ((!isset($current['parent_id']) || !$current['parent_id']) && $parentSet){
                if (auth()->user()->banks->count() && !iRole::has($request->action . '.any'))
                    $model->whereIn('parent_id', auth()->user()->banks->pluck('id')->toArray());
                else
                    $model->where('parent_id', null)->orWhere('parent_id','<=', 0);
            }
        }
        return [$filters, $current];
    }
}
