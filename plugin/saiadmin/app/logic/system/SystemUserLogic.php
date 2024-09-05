<?php
namespace plugin\saiadmin\app\logic\system;

use plugin\saiadmin\app\model\system\SystemUser;
use plugin\saiadmin\basic\BaseLogic;
use plugin\saiadmin\utils\JwtAuth;
use plugin\saiadmin\exception\ApiException;
use Webman\Event\Event;

/**
 * 用户信息逻辑层
 */
class SystemUserLogic extends BaseLogic
{
    public function __construct()
    {
        $this->model = new SystemUser();
    }

    public function read($id)
    {
        $admin = $this->model->find($id);
        $data = $admin->toArray();
        $data['roleList'] = $admin->roles->toArray() ?: [];
        $data['postList'] = $admin->posts->toArray() ?: [];
        $data['deptList'] = $admin->depts;
        return $data;
    }

    public function add($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->transaction(function () use ($data) {
            $role_ids = $data['role_ids'] ?? [];
            $post_ids = $data['post_ids'] ?? [];
            $user = SystemUser::create($data);
            $user->roles()->detach();
            $user->posts()->detach();
            $user->roles()->saveAll($role_ids);
            if (!empty($post_ids)) {
                $user->posts()->save($post_ids);
            }
            return $user->getKey();
        });
    }

    public function edit($data, $id)
    {
        unset($data['password']);
        return $this->transaction(function () use ($data, $id) {
            $role_ids = $data['role_ids'] ?? [];
            $post_ids = $data['post_ids'] ?? [];
            $result = $this->model->update($data,['id' => $id]);
            $user = $this->model->find($id);
            if ($result && $user) {
                $user->roles()->detach();
                $user->posts()->detach();
                $user->roles()->saveAll($role_ids);
                if (!empty($post_ids)) {
                    $user->posts()->save($post_ids);
                }
            }
            return $result;
        });
    }

    /**
     * 用户登录
     * @param string $username
     * @param string $password
     * @param string $type
     * @return array
     */
    public function login(string $username, string $password, string $type): array
    {
        $adminInfo = $this->model->where('username', $username)->find();
        $status = 1;
        $message = '登录成功';
        if (!$adminInfo) {
            $status = 0;
            $message = '账号或密码错误，请重新输入!';
            throw new ApiException($message);
        }
        if ($adminInfo->status === 2) {
            $status = 0;
            $message = '您已被禁止登录!';
        }
        if (!password_verify($password, $adminInfo->password)) {
            $status = 0;
            $message = '账号或密码错误，请重新输入!';
        }
        if ($status === 0) {
            // 登录事件
            Event::emit('user.login', compact('username','status','message'));
            throw new ApiException($message);
        }
        $adminInfo->login_time = date('Y-m-d H:i:s');
        $adminInfo->login_ip = request()->getRealIp();
        $adminInfo->save();

        $key = config('plugin.saiadmin.saithink.cross.jwt_key', 'sai_admin');
        $jwt = new JwtAuth($key);
        $token = $jwt->createToken($adminInfo->id, $adminInfo->username, $type);
        // 登录事件
        Event::emit('user.login', compact('username','status','message'));

        return [
            'token' => $token['token'],
            'expires_time' => $token['params']['exp']
        ];
    }

    public function modifyPassword($adminId, $oldPassword, $newPassword)
    {
        $model = $this->model->find($adminId);
        if (password_verify($oldPassword, $model->password)) {
            $model->password = password_hash($newPassword, PASSWORD_DEFAULT);
            return $model->save();
        } else {
            throw new ApiException('原密码错误');
        }
    }

}
