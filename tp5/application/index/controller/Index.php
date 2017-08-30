<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Cache;

class Index extends Controller
{
    public function index()
    {
        echo phpinfo();
    }

    public function read($id)
    {
        echo $id;
    }

    public function payment()
    {
        $request = Request::instance();
        $this->assign('url',$request->url(true));
        $this->assign('param',$request->param());
        $data = Db::name('course')->field('title')->where('status=:status and buyable=:buyable')->
        bind(['status'=>'published','buyable'=>[1,\PDO::PARAM_INT]])->paginate(4);
        $page = $data->render();
        $this->assign('data',$data);
        $this->assign('page',$page);
        $options = [
            'host'       => '127.0.0.1',
            'port'       => 6379,
            'password'   => '',
            'select'     => 0,
            'timeout'    => 0,
            'expire'     => 0,
            'persistent' => false,
            'prefix'     => '',
        ];
        Cache::connect($options);
        Cache::set('redis','REDIS');
        $this->assign('redis',Cache::get('redis'));
        $this->assign('root',ROOT_PATH);
        $this->assign('path',$_SERVER['SCRIPT_FILENAME']);
        $this->assign('realpath',realpath($_SERVER['SCRIPT_FILENAME']));
        $this->assign('self',$_SERVER['PHP_SELF']);
        $this->assign('file',__FILE__);
        $token = request()->token('__token__','shal');
        $this->assign('token',$token);
        return $this->fetch();
    }
    public function upload()
    {
        $files = request()->file('image');
        foreach($files as $file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                echo $info->getExtension();
                echo $info->getSaveName();
                echo $info->getFilename();
            }else{
                echo $file->getError();
            }
        }
    }
    public function check(){
        $data = request()->param();
        $validate = validate('user');
        if(!$validate->scene('personal')->check($data)){
            dump($validate->getError());
        }else{
            echo "提交成功";
        }
    }
}
