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
    public static function Model()
    {
        return config('laraquent.model.directory');
    }

    public static function Create($table, $data = [])
    {
        if (is_string($table)) {
            $table = BeautyEloquent::Model() . '\\' . $table;
        }

        $request = new $table;
        foreach ($data as $key => $value) {
            $request->$key = $value;
        }
        $request->save();
        $template = BeautyEloquentTools::ResponseTemplate();
        $template->status = true;
        $template->output = $request;

        if (config('laraquent.failure.checking') === false) {
            if ($template->status) {
                return $template->output;
            } else {
                return null;
            }
        } else {
            return $template;
        }
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
    public static function Read($table = '', $options = [])
    {
        if (is_string($table)) {
            $table = BeautyEloquent::Model() . '\\' . $table;
        }

        $request = new $table;

        $finalWhere = [];
        $finalOrWhere = [];
        $finalWhereJsonContains = [];
        $finalWhereRaw = [];
        $finalOrWhereRaw = [];

        if (isset($options['join']) && count($options['join']) >= 1) {
            foreach ($options['join'] as $value) {
                if (count($value) >= 4) {
                    $request = $request->join($value[0], $value[1], $value[2], $value[3]);
                }
            }
        }

        if (isset($options['id']) && $options['id']) {
            $request = $request->where('id', '=', $options['id']);
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
        if (isset($options['orWhere']) && count($options['orWhere'])) {
            foreach ($options['orWhere'] as $key => $value) {
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

        $template = BeautyEloquentTools::ResponseTemplate();
        if (isset($options['pluck']) && count($options['pluck']) >= 1) {
            if (count($options['pluck']) === 1) {
                $status = $request->pluck($options['pluck'][0])->all();
            } else if (count($options['pluck']) === 2) {
                $status = $request->pluck($options['pluck'][0], $options['pluck'][1])->all();
            }


            if (isset($status) && count($status)) {
                $template->status = true;
                $template->output = $status;
            } else {
                $template->status = false;
                $template->output = $status;
            }
        } else if (isset($options['first']) && $options['first']) {
            $request = $request->get()->first();
            $status = BeautyEloquentTools::arr2Json($request);
            if ($status) {
                if (isset($options['json']) && $options['json'] === true) {
                    $template->status = true;
                    $template->output = BeautyEloquentTools::arr2Json($request);
                } else {
                    $template->status = true;
                    $template->output = $request;
                }
            } else {
                $template->status = false;
                $template->output = $request;
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
                    $template->status = true;
                    $template->output = BeautyEloquentTools::arr2Json($request);
                } else {
                    $template->status = true;
                    $template->output = $request;
                }
            } else {
                $template->status = false;
                $template->output = $request;
            }
        }

        if (config('laraquent.failure.checking') === false) {
            if ($template->status) {
                return $template->output;
            } else {
                return [];
            }
        } else {
            return $template;
        }
    }

    /**
     * $users = $this->API->Update('user',[
     *                  'name' => 'Jhon',
     *                  'age' => 26
     *              ] ,[
     *              'where' => [
     *                  ['role','=','user','or']
     *              ],
     *              'orwhere => [],
     *              'id' => ''
     *          ]);
     */
    public static function Update($table = '', $data = [], $options = [])
    {
        if (is_string($table)) {
            $table = BeautyEloquent::Model() . '\\' . $table;
        }

        $request = new $table;

        $finalWhere = [];
        $finalOrWhere = [];

        if (isset($options['id']) && $options['id']) {
            $request = $request->where('id', '=', $options['id']);
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
        if (isset($options['orWhere']) && count($options['orWhere'])) {
            foreach ($options['orWhere'] as $key => $value) {
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

        $status = $request->get();
        $status = BeautyEloquentTools::arr2Json($status);
        $template = BeautyEloquentTools::ResponseTemplate();
        if (isset($status) && count($status)) {
            if (isset($data) && count($data)) {
                $request = $request->update($data);
                $template->status = true;
                $template->output = $request;
            } else {
                $template->status = false;
                $template->output = 'Please insert data';
            }
        } else {
            $template->status = false;
            $template->output = $request;
        }

        if (config('laraquent.failure.checking') === false) {
            if ($template->status) {
                return $template->output;
            } else {
                return null;
            }
        } else {
            return $template;
        }
    }

    /**
     * $users = $this->API->Delete('user', [
     *              'where' => [
     *                  ['role','=','user']
     *                  [...] //and more
     *              ],
     *              'orwhere => [],
     *              'id' => ''
     *          ]);
     */
    public static function Delete($table = '', $options = [])
    {
        if (is_string($table)) {
            $table = BeautyEloquent::Model() . '\\' . $table;
        }

        $request = new $table;

        $finalWhere = [];
        $finalOrWhere = [];

        if (isset($options['id']) && $options['id']) {
            $request = $request->where('id', '=', $options['id']);
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
        if (isset($options['orWhere']) && count($options['orWhere'])) {
            foreach ($options['orWhere'] as $key => $value) {
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

        $check = $request->get();
        $check = json_encode($check);
        $check = json_decode($check);
        $template = BeautyEloquentTools::ResponseTemplate();
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
                    $template->status = true;
                } else {
                    $template->status = false;
                }
            } else {
                $template->status = true;
            }
        } else {
            $template->status = false;
        }

        $template->output = $request;

        if (config('laraquent.failure.checking') === false) {
            if ($template->status) {
                return $template->output;
            } else {
                return null;
            }
        } else {
            return $template;
        }
    }

    /**
     * Retrieve all roles
     */
    public static function Roles($options = [])
    {
        $model = config('laraquent.model.role');
        return BeautyEloquent::Read(new $model, $options);
    }

    /**
     * Add new role
     * $permissions = [
     *     0 => "1"
     *     1 => "2"
     *     2 => "3"
     *     3 => "4"
     *     4 => "8"
     * ]
     */
    public static function CreateRole($name, $permissions = [], $guardName = '')
    {
        $model = config('laraquent.model.role');
        $table = new $model;
        $dataRole = [
            'name' => $name
        ];

        if ($guardName !== '') {
            $dataRole['guard_name'] = $guardName;
        }

        $role = $table->create($dataRole);
        if (isset($permissions) && count($permissions) >= 1) {
            $role->givePermissionTo($permissions);
        }
        $template = BeautyEloquentTools::ResponseTemplate();
        $template->status = true;
        $template->output = $role;

        if (config('laraquent.failure.checking') === false) {
            if ($template->status) {
                return $template->output;
            } else {
                return null;
            }
        } else {
            return $template;
        }
    }

    /**
     * Edit specific role by id
     * $permissions = [
     *     0 => "1"
     *     1 => "2"
     *     2 => "3"
     *     3 => "4"
     *     4 => "8"
     * ]
     */
    public static function UpdateRole($id, $permissions = [])
    {
        $model = config('laraquent.model.role');
        $table = new $model;
        $role = $table->find($id);
        $template = BeautyEloquentTools::ResponseTemplate();
        if ($role !== null) {
            $role->save();
            $role->syncPermissions($permissions);
            $template->status = true;
            $template->output = $role;
        } else {
            $template->status = false;
            $template->output = 'Role not found';
        }

        if (config('laraquent.failure.checking') === false) {
            return $template->output;
        } else {
            return $template;
        }
    }

    /**
     * Delete specific role by id
     */
    public static function DeleteRole($id)
    {
        $model = config('laraquent.model.role');
        $table = new $model;
        $role = $table->find($id);
        $template = BeautyEloquentTools::ResponseTemplate();
        if ($role !== null) {
            $role->delete();
            $template->status = true;
            $template->output = $role;
        } else {
            $template->status = false;
            $template->output = 'Role not found';
        }

        if (config('laraquent.failure.checking') === false) {
            return $template->output;
        } else {
            return $template;
        }
    }

    /**
     * Retrieve all permissions
     */
    public static function Permissions($options = [])
    {
        $model = config('laraquent.model.permission');
        return BeautyEloquent::Read(new $model, $options);
    }

    /**
     * Get specific user by id
     */
    public static function User($id)
    {
        $model = config('laraquent.model.user');
        return BeautyEloquent::Read(new $model, [
            'id' => $id,
            'first' => true
        ]);
    }

    /**
     * $data = $request->all();
     * make sure all request data including the password has been hashed
     * 
     * $roles = [
     *      0 => "Admin"
     *      1 => "User"
     * ]
     */
    public static function CreateUser($data, $roles = [])
    {
        $model = config('laraquent.model.user');
        $table = new $model;
        $user = $table->create($data);
        if (isset($roles) && count($roles) >= 1) {
            $user->assignRole($roles);
        }
        $template = BeautyEloquentTools::ResponseTemplate();
        $template->status = true;
        $template->output = $user;

        if (config('laraquent.failure.checking') === false) {
            if ($template->status) {
                return $template->output;
            } else {
                return null;
            }
        } else {
            return $template;
        }
    }

    /**
     * Edit specific user by id
     * $data = $request->all();
     * 
     * $roles = [
     *      0 => "Admin"
     *      1 => "User"
     * ]
     */
    public static function UpdateUser($id, $data, $roles = [])
    {
        $model = config('laraquent.model.user');
        $table = new $model;
        $user = $table->find($id);
        $template = BeautyEloquentTools::ResponseTemplate();
        if ($user !== null) {
            $user->update($data);
            if (isset($roles) && count($roles) >= 1) {
                $user->syncRoles($roles);
            }
            $template->status = true;
            $template->output = $user;
        } else {
            $template->status = false;
            $template->output = 'User not found';
        }

        if (config('laraquent.failure.checking') === false) {
            return $template->output;
        } else {
            return $template;
        }
    }

    /**
     * Delete specific user by id
     */
    public static function DeleteUser($id)
    {
        $model = config('laraquent.model.user');
        $table = new $model;
        $user = $table->find($id);
        $template = BeautyEloquentTools::ResponseTemplate();
        if ($user !== null) {
            $user->delete();
            $template->status = true;
            $template->output = $user;
        } else {
            $template->status = false;
            $template->output = 'User not found';
        }

        if (config('laraquent.failure.checking') === false) {
            return $template->output;
        } else {
            return $template;
        }
    }

    /**
     * Retrieve all users
     */
    public static function Users($options = [])
    {
        $model = config('laraquent.model.user');
        return BeautyEloquent::Read(new $model, $options);
    }
}
