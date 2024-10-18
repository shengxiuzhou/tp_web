<?php
    namespace app\controller;

    use app\BaseController;
    use app\model\News;
    use think\facade\Requests;
    use think\facade\Db;



    class NewsController extends BaseController {
        // public function __constructor($request = null) {
        //     parent::__constructor($request);
        //     $this->setCorHeaders();
        // }
        protected function initialize() {
            parent::initialize();
            header('Access-Control-Allow-Origin:*');
            header('Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS');
            header('Access-Control-Allow-Headers:Origin,X-Requested-With,Content-Type,Accept,Authorization');
            if ($this->request->method(true) === "OPTIONS") {
                header('Access-Control-Max-Age:86400');
                return '';
            }
        }
        public function add_news() {
            // $data = [[
            //     'title' => '国风华服，秀出“Z世代”的文化表达（有一种风华叫中国·华服风①）',
            //     'content' => '国风华服，秀出“Z世代”的文化表达（有一种风华叫中国·华服风①）',
            //     'datetime'=> '2024.10.18 12:20:00'
            //     ],[
            //         'title' => '隰（xí）县“小西天”为何这么火？',
            //         'content' => '隰（xí）县“小西天”为何这么火？',
            //         'datetime'=> '2024.10.18 16:20:00'
            //     ],
            //     [
            //         'title' => '隰（xí）县',
            //         'content' => '隰（xí）县',
            //         'datetime'=> '2024.10.18 16:20:00'
            //     ]
            // ];
            $data = isset($_POST['data']) ? $_POST['data'] : [
                // 'title' => '隰（xí）县',
                // 'content' => '隰（xí）县',
                // 'datetime'=> '2024.10.18 16:20:00'
                'title' => '隰（xí）县“小西天”为何这么火？',
                'content' => '隰（xí）县“小西天”为何这么火？',
                'datetime'=> '2024.10.18 16:20:00'
            ];
            $check = true;
            if (mb_strlen($data['content']) <10 || mb_strlen($data['title']) <10) {
                $check = false;
            }
            
            if (!$check) {
                return json([
                    "code" => -1,
                    "msg"=> '添加失败,title或content字段不能小于10'
                ]);
            }
            $res = Db::table('news')->insert($data);
            if ($res) {
                return json([
                    "code" => 0,
                    "msg"=> '添加成功'
                ]);
            } else {
                return json([
                    "code" => -1,
                    "msg"=> '添加失败'
                ]);
            }
       
        }
        public function delete_news() {
            $id = $_POST['id'];
            $news = Db::table('news')->find($id);
            if($news) {
                Db::table('news')->where('id', $id)->delete();
                return json([
                    "code" => 0,
                    "msg"=> '删除成功'
                ]);
            } else {
                return json([
                    "code" => -1,
                    "msg"=> '删除失败'
                ]);
            }
        }
        public function modify_news() {
            $data = $_POST['data'];
            if (mb_strlen($data['content'])>10 && mb_strlen($data['title'])>10) {
                $res = Db::table('news')->where('id', $data['id'])->update($data);
                if($res) {
                    return json([
                        "code" => 0,
                        "msg"=> '修改成功'
                    ]); 
                } else {
                    return json([
                        "code" => 0,
                        "msg"=> '修改失败'
                    ]);
                }
            } else {
                return json([
                    "code" => 0,
                    "msg"=> '修改失败，长度不够，请检查'
                ]);
            }
        }
        public function get_news() {
            $data = isset($_POST['data']) ? $_POST['data'] : ['title' => ''];
            if ($data['title']) {
                $news = Db::table('news')->whereLike('title', $data['title'])->select();
                if (count($news) > 0) {
                    return json([
                        "code" => 0,
                        "data"=> $news,
                        "msg"=> '查询成功',
                    ]);
                } else {
                    return json([
                        "code" => 0,
                        "msg"=> '无查询内容',
                        "data"=> []
                    ]);
                }
               
            } else {
                $news = Db::table('news')->order('datetime','desc')->limit(4)->select();
                return json([
                    "code" => 0,
                    "msg"=> '查询成功',
                    "data"=> $news
                ]);
            }
        }
    }

?>