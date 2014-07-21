<?php
/**
 * User: greatcl
 * Date: 14-7-10
 * Time: 下午2:42
 */

class lib_login {

    public function login($email, $password){
        $result = spClass('m_memebers')->find(array(
            'email' => $email
        ), NULL, 'ID, username, password, salt', 1);
        if ($result){
            $user_id = $result['ID'];
            $username = $result['username'];
            $db_password = $result['password'];
            $salt = $result['salt'];

            $password = hash('sha512', $password . $salt);
            if ($this->checkBrute($user_id) == true){
                //@todo 用户已锁定，发邮件告知
                return false;
            } else {
                // 验证密码是否正确
                if ($db_password == $password){
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $password . $user_browser);

                    // 登录成功
                    return true;
                } else {
                    $now = time();
                    spClass('m_login_attempts')->findSql("INSERT INTO login_attempts(user_id, time) VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        }
        return false;
    }

    public function checkBrute($user_id){
        // 获取当前时间的时间戳
        $now = time();

        // 计算最近2小时尝试登录的次数
        $valid_attempts = $now - (2 * 60 * 60);
        $conditions = " user_id = $user_id AND time > '$valid_attempts'";
        $result = spClass('m_login_attempts')->findAll($conditions, NULL, 'time');
        if ($result && count($result) > 5){
            return true;
        }
        return false;
    }

    public function checkLogin(){
        // 检测是否所有的session变量已经设置
        if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])){
            $user_id = $_SESSION['user_id'];
            $login_string = $_SESSION['login_string'];
            $username = $_SESSION['username'];

            // 获取用户的 user-agent
            $user_browser = $_SERVER['HTTP_USER_AGENT'];

            $result = spClass('m_members')->find(array(
                'ID' => $user_id
            ), NULL, 'password', 1);

            if ($result){
                $password = $result['password'];
                $login_check = hash('sha512', $password . $user_browser);
                if ($login_check = $login_string){
                    // 登录成功
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $url
     * @return mixed|string
     * @todo what's the use of this function
     */
    public function esc_url($url) {

        if ('' == $url) {
            return $url;
        }

        $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

        $strip = array('%0d', '%0a', '%0D', '%0A');
        $url = (string) $url;

        $count = 1;
        while ($count) {
            $url = str_replace($strip, '', $url, $count);
        }

        $url = str_replace(';//', '://', $url);

        $url = htmlentities($url);

        $url = str_replace('&amp;', '&#038;', $url);
        $url = str_replace("'", '&#039;', $url);

        if ($url[0] !== '/') {
            // We're only interested in relative links from $_SERVER['PHP_SELF']
            return '';
        } else {
            return $url;
        }
    }
} 