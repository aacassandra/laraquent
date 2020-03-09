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
    public function Create($table, $data=[])
    {
        $table = 'App\\'.ucfirst($table);
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
     *              ],
     *              'first' => true
     *           ]);
     */
    public function Read($table='', $options=[])
    {
        $table = 'App\\'.ucfirst($table);
        $request = new $table;

        $finalWhere = [];
        $finalOrWhere = [];

        if (isset($options['id']) && $options['id']) {
            array_push($finalWhere, ['id', '=', $options['id']]);
        }

        if (isset($options['where']) && count($options['where'])) {
            foreach ($options['where'] as $key => $value) {
                if (isset($value[3]) && $value[3] === 'or') {
                    array_push($finalOrWhere, [$value[0],$value[1],$value[2]]);
                } else {
                    array_push($finalWhere, [$value[0],$value[1],$value[2]]);
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
            $request = $request->get();
            $status = BeautyEloquentTools::arr2Json($request);
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
    public function Update($table='', $data=[], $options=[])
    {
        $table = 'App\\'.ucfirst($table);
        $request = new $table;

        $finalWhere = [];
        $finalOrWhere = [];

        if (isset($options['id']) && $options['id']) {
            array_push($finalWhere, ['id', '=', $options['id']]);
        }

        if (isset($options['where']) && count($options['where'])) {
            foreach ($options['where'] as $key => $value) {
                if (isset($value[3]) && $value[3] === 'or') {
                    array_push($finalOrWhere, [$value[0],$value[1],$value[2]]);
                } else {
                    array_push($finalWhere, [$value[0],$value[1],$value[2]]);
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
    public function Delete($table='', $options=[])
    {
        $table = 'App\\'.ucfirst($table);
        $request = new $table;

        $finalWhere = [];
        $finalOrWhere = [];

        if (isset($options['id']) && $options['id']) {
            $request = $request->where('id', $options['id']);
        }

        if (isset($options['where']) && count($options['where'])) {
            foreach ($options['where'] as $key => $value) {
                if (isset($value[3]) && $value[3] === 'or') {
                    array_push($finalOrWhere, [$value[0],$value[1],$value[2]]);
                } else {
                    array_push($finalWhere, [$value[0],$value[1],$value[2]]);
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
            return BeautyEloquentTools::arr2Json([
                'status' => false
            ]);
        } else {
            return BeautyEloquentTools::arr2Json([
                'status' => true
            ]);
        }
    }
}
