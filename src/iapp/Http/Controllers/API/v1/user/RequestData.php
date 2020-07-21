<?php


namespace iLaravel\Core\IApp\Http\Controllers\API\v1\User;

use iLaravel\Core\IApp\Http\Requests\iLaravel as Request;

trait RequestData
{
    public function requestData(Request $request, $action, &$data)
    {
        if (in_array($action, ['store', 'update']) && isset($data['mobile'])) {
            $request->merge([
                'mobile' => $data['mobile']['country'] . '-'. $data['mobile']['number'],
            ]);
            $data['mobile'] = $request->mobile;
        }
        if (in_array($action, ['store', 'update']) && isset($data['groups'])) {
            if (is_string($data['groups']))
                $data['groups'] = explode(',', $data['groups']);
            $groups = iRole::allGroups();
            $parse = [];
            foreach ($data['groups'] as $key => $value) {
                if (in_array($value, $groups)) {
                    $parse[] = $value;
                }
            }
            $data['groups'] = join('|', $parse);
            $request->merge([
                'groups' => $data['groups'],
            ]);
        } elseif (in_array($action, ['store', 'update']) && !isset($data['groups'])) {
            $data['groups'] = null;
        }
        if (in_array($action, ['store']))
            $data['creator_id'] = auth()->id();
    }
}
