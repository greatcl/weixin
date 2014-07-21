<?php
class main extends spController
{
    public function index(){
        echo '<h2 style="font-family: \'Microsoft YaHei\'; text-align: center;">Hello, this is WeChat App</h2>';
        echo '<a href='. spUrl(main, login) . ' /> 登录 </a>';
    }

    public function login(){
        $this->display('admin/login.html');
    }

    public function processLogin(){
        if (isset($_POST['email'], $_POST['p'])){
            $email = $_POST['email'];
            $password = $_POST['p']; // The hashed password.

            if (spClass('lib_login')->login($email, $password) == true){
                // 登录成功
                header('Location: ../protected_page.php');
            } else {
                // 登录失败
                header('Location: ../index.php?error=1');
            }
        } else {
            // 正确的POST变量没有发送到这个页面
            echo 'Invalid Request';
        }
    }

    public function logout(){
        // 删除全部的session变量
        $_SESSION = array();

        // 获取session参数
        $params = session_get_cookie_params();

        // 删除真正的cookie
        setcookie(session_name(), '', time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]);

        // 销毁session
        session_destroy();
        header('Location: ../index.php');
    }
}