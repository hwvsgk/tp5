<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/25 0025
 * Time: 下午 4:05
 */
namespace app\index\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
      'account' => ['require','alphaDash','max' => 25,'token' => '__token__'],
      'pwd' => ['require','alphaDash','min' => 6],
      'repwd' => ['require','confirm' => 'pwd'],
      'email' => 'email',
      'mobile' => ['require','regex' => '(13[0-9])\d{8}']
    ];
    protected $message = [
        'account.require' => '账号不能为空',
        'account.alphaDash' => '账号由字母，数字或者下划线构成',
        'account.max' => '账号不能超过25个字符'
    ];
    protected $scene = [
        'personal' => ['account','pwd','repwd','mobile']
    ];
}