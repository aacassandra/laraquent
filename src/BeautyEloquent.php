<?php

namespace Laraquent;

use Laraquent\BeautyEloquentTools;

class BeautyEloquent
{
    /** $data = [
     *     ['name', 'create new'],
     *     ['email', 'asdad123@2asd.com'],
     *     ['phone', 12313],
     *     ['role', 'create new'],
     *     ['password', 'asdasdad']
     * ];
     * $user = $this->API->Create('user', $data);
     */
    public function Create($table, $data = [])
    {
        $table = 'App\\' . ucfirst($table);
        $request = new $table;
        foreach ($data as $key => $value) {
            $columnName = $value[0];
            $request->$columnName = $value[1];
        }
        $request->save();
        return BeautyEloquentTools::arr2Json([
            'status' => true
        ]);
    }

    /**
     *  $users = $this->API->Read('user', [
     *              'where' => [
     *                  ['role','=','admin'],
     *                  [...] //and more
     *              ],
     *              'orWhere' => [
     *                  ['role','=','admin'],
     *                  [...] //and more
     *              ],
     *              'whereJsonContains' => [
     *                  ['slug->en', 'article-part-2'],
     *                  [...] //and more
     *              ],
     *              'whereRaw' => [
     *                  ['price > IF(state = "TX", ?, 100)', [200]],
     *                  [...] //and more
     *              ],
     *              'orWhereRaw' => [
     *                  ['price > IF(state = "TX", ?, 100)', [200]],
     *                  [...] //and more
     *              ]
     *              'orderBy' => ['id', 'DESC'], //ASC or DESC
     *              'limit' => 2,
     *              'first' => true //if the result has a return value of 1 and no more and return json objects,
     *              'paginate' => [3, 'home'] //{x, y} : x => the amount of content to be displayed, y : pageName for multi pagination (optional)
     *           ]);
     */
    public function Read($table = '', $options = [])
    {
        $table = 'App\\' . ucfirst($table);
        $request = new $table;

        $finalWhere = [];
        $finalOrWhere = [];
        $finalWhereJsonContains = [];
        $finalWhereRaw = [];
        $finalOrWhereRaw = [];

        if (isset($options['id']) && $options['id']) {
            array_push($finalWhere, ['id', '=', $options['id']]);
        }

        // Where
        if (isset($options['where']) && count($options['where'])) {
            foreach ($options['where'] as $key => $value) {
                if (count($value) === 3) {
                    array_push($finalWhere, [$value[0], $value[1], $value[2]]);
                }
            }

            if (isset($finalWhere) && count($finalWhere)) {
                foreach ($finalWhere as $key => $value) {
                    $request = $request->where($value[0], $value[1], $value[2]);
                }
            }
        }

        // Or Where
        if (isset($options['OrWhere']) && count($options['OrWhere'])) {
            foreach ($options['OrWhere'] as $key => $value) {
                if (count($value) === 3) {
                    array_push($finalOrWhere, [$value[0], $value[1], $value[2]]);
                }
            }

            if (isset($finalOrWhere) && count($finalOrWhere)) {
                foreach ($finalOrWhere as $key => $value) {
                    $request = $request->orWhere($value[0], $value[1], $value[2]);
                }
            }
        }

        // Where Json Contains
        if (isset($options['whereJsonContains']) && count($options['whereJsonContains'])) {
            foreach ($options['whereJsonContains'] as $key => $value) {
                if (count($value) === 2) {
                    array_push($finalWhereJsonContains, [$value[0], $value[1]]);
                }
            }

            if (isset($finalWhereJsonContains) && count($finalWhereJsonContains)) {
                foreach ($finalWhereJsonContains as $key => $value) {
                    $request = $request->whereJsonContains($value[0], $value[1]);
                }
            }
        }

        // Where Raw
        if (isset($options['whereRaw']) && count($options['whereRaw'])) {
            foreach ($options['whereRaw'] as $key => $value) {
                if (count($value) >= 1) {
                    if (isset($value[1])) {
                        array_push($finalWhereRaw, [$value[0], $value[1]]);
                    } else {
                        array_push($finalWhereRaw, [$value[0]]);
                    }
                }
            }

            if (isset($finalWhereRaw) && count($finalWhereRaw)) {
                foreach ($finalWhereRaw as $key => $value) {
                    if (isset($value[1])) {
                        $request = $request->whereRaw($value[0], $value[1]);
                    } else {
                        $request = $request->whereRaw($value[0]);
                    }
                }
            }
        }

        // Or Where Raw
        if (isset($options['orWhereRaw']) && count($options['orWhereRaw'])) {
            foreach ($options['orWhereRaw'] as $key => $value) {
                if (count($value) >= 1) {
                    if (isset($value[1])) {
                        array_push($finalOrWhereRaw, [$value[0], $value[1]]);
                    } else {
                        array_push($finalOrWhereRaw, [$value[0]]);
                    }
                }
            }

            if (isset($finalOrWhereRaw) && count($finalOrWhereRaw)) {
                foreach ($finalOrWhereRaw as $key => $value) {
                    if (isset($value[1])) {
                        $request = $request->orWhereRaw($value[0], $value[1]);
                    } else {
                        $request = $request->orWhereRaw($value[0]);
                    }
                }
            }
        }

        // ----------------------

        if (isset($options['orderBy']) && count($options['orderBy']) >= 1) {
            $request = $request->orderBy($options['orderBy'][0], $options['orderBy'][1]);
        }

        if (isset($options['limit']) && $options['limit']) {
            $request = $request->limit($options['limit']);
        }

        if (isset($options['first']) && $options['first']) {
            $request = $request->get()->first();
            $status = BeautyEloquentTools::arr2Json($request);
            if ($status) {
                if (isset($options['json']) && $options['json'] === true) {
                    return BeautyEloquentTools::arr2Json([
                        'status' => true,
                        'output' => $request
                    ]);
                } else {
                    $response = BeautyEloquentTools::arr2Json([
                        'status' => true,
                        'output' => null
                    ]);

                    $response->output = $request;
                    return $response;
                }
            } else {
                return BeautyEloquentTools::arr2Json([
                    'status' => false
                ]);
            }
        } else {
            $status = [];
            if (isset($options['paginate']) && $options['paginate'][0] >= 2) {
                if (isset($options['paginate'][1])) {
                    $request = $request->paginate($options['paginate'][0], ['*'], $options['paginate'][1]);
                } else {
                    $request = $request->paginate($options['paginate'][0]);
                }
                $status = $request->items();
            } else {
                $request = $request->get();
                $status = BeautyEloquentTools::arr2Json($request);
            }

            if (isset($status) && count($status)) {
                if (isset($options['json']) && $options['json'] === true) {
                    return BeautyEloquentTools::arr2Json([
                        'status' => true,
                        'output' => $request
                    ]);
                } else {
                    $response = BeautyEloquentTools::arr2Json([
                        'status' => true,
                        'output' => null
                    ]);

                    $response->output = $request;
                    return $response;
                }
            } else {
                return BeautyEloquentTools::arr2Json([
                    'status' => false
                ]);
            }
        }
    }

    /**
     * $users = $this->API->Update('user',[
     *                  ['name', 'Jhon'],
     *                  ['age', 26]
     *              ] ,[
     *              'where' => [
     *                  ['role','=','user','or']
     *              ],
     *              'orwhere => [],
     *              'id' => ''
     *          ]);
     */
    public function Update($table = '', $data = [], $options = [])
    {
        $table = 'App\\' . ucfirst($table);
        $request = new $table;

        $finalWhere = [];
        $finalOrWhere = [];

        if (isset($options['id']) && $options['id']) {
            array_push($finalWhere, ['id', '=', $options['id']]);
        }

        if (isset($options['where']) && count($options['where'])) {
            foreach ($options['where'] as $key => $value) {
                if (isset($value[3]) && $value[3] === 'or') {
                    array_push($finalOrWhere, [$value[0], $value[1], $value[2]]);
                } else {
                    array_push($finalWhere, [$value[0], $value[1], $value[2]]);
                }
            }
        }

        foreach ($finalWhere as $key => $value) {
            $request = $request->where($value[0], $value[1], $value[2]);
        }

        if (isset($finalOrWhere) && count($finalOrWhere)) {
            foreach ($finalOrWhere as $key => $value) {
                $request = $request->orWhere($value[0], $value[1], $value[2]);
            }
        }

        $status = $request->get();
        $status = BeautyEloquentTools::arr2Json($status);
        if (isset($status) && count($status) && isset($data) && count($data)) {
            $finalData = [];
            foreach ($data as $key => $value) {
                $finalData[$value[0]] = $value[1];
            }
            $request = $request->update($finalData);
            return BeautyEloquentTools::arr2Json([
                'status' => true
            ]);
        } else {
            return BeautyEloquentTools::arr2Json([
                'status' => false
            ]);
        }
    }

    /**
     * $users = $this->API->Delete('user', [
     *              'where' => [
     *                  ['role','=','user','or']
     *              ],
     *              'orwhere => [],
     *              'id' => ''
     *          ]);
     */
    public function Delete($table = '', $options = [])
    {
        $table = 'App\\' . ucfirst($table);
        $request = new $table;

        $finalWhere = [];
        $finalOrWhere = [];

        if (isset($options['id']) && $options['id']) {
            $request = $request->where('id', $options['id']);
        }

        if (isset($options['where']) && count($options['where'])) {
            foreach ($options['where'] as $key => $value) {
                if (isset($value[3]) && $value[3] === 'or') {
                    array_push($finalOrWhere, [$value[0], $value[1], $value[2]]);
                } else {
                    array_push($finalWhere, [$value[0], $value[1], $value[2]]);
                }
            }
        }

        foreach ($finalWhere as $key => $value) {
            $request = $request->where($value[0], $value[1], $value[2]);
        }

        if (isset($finalOrWhere) && count($finalOrWhere)) {
            foreach ($finalOrWhere as $key => $value) {
                $request = $request->orWhere($value[0], $value[1], $value[2]);
            }
        }
        $check = $request->get();
        $check = json_encode($check);
        $check = json_decode($check);
        if (count($check) >= 1) {
            $request = $request->delete();

            //Check status
            $status = new $table;
            foreach ($finalWhere as $key => $value) {
                $status = $status->where($value[0], $value[1], $value[2]);
            }

            if (isset($finalOrWhere) && count($finalOrWhere)) {
                foreach ($finalOrWhere as $key => $value) {
                    $status = $status->orWhere($value[0], $value[1], $value[2]);
                }
            }

            $status = $status->get();
            $status = BeautyEloquentTools::arr2Json($status);
            if (isset($status) && count($status)) {
                $fail = 0;
                foreach ($status as $sts) {
                    if ($sts->id === $options['id']) {
                        $fail = $fail + 1;
                    }
                }

                if ($fail <= 0) {
                    return BeautyEloquentTools::arr2Json([
                        'status' => true
                    ]);
                } else {
                    return BeautyEloquentTools::arr2Json([
                        'status' => false
                    ]);
                }
            } else {
                return BeautyEloquentTools::arr2Json([
                    'status' => true
                ]);
            }
        } else {
            return BeautyEloquentTools::arr2Json([
                'status' => false
            ]);
        }
    }
}
