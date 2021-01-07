<?php
/**
 * Description of this file
 *
 * @author  maxiongfei <maxiongfei@vchangyi.com>
 * @date    2019/1/10 4:55 PM
 */

use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Firebase\JWT\JWT;

if (!function_exists('config_path')) {
    /**
     * @description get the configuration path
     *
     * @param string $path
     *
     * @return string
     * @author      guilong
     * @date        2018-08-01
     */
    function config_path($path = '')
    {
        return app()->basePath().'/config'.($path ? '/'.$path : $path);
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param  string $value
     * @param  array  $options
     *
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->driver('bcrypt')->make($value, $options);
    }
}

if (!function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string $key
     * @param  mixed        $default
     *
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        $value = app('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }
}

if (!function_exists('auth')) {
    /**
     * Get the available auth instance.
     *
     * @param  string|null $guard
     *
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function auth($guard = null)
    {
        if (is_null($guard)) {
            return app(AuthFactory::class);
        }

        return app(AuthFactory::class)->guard($guard);
    }
}

if (!function_exists('nowTime')) {
    /**
     * 当前时间戳
     *
     * @return int
     * @author  maxiongfei <maxiongfei@vchangyi.com>
     * @date    2019/1/11 12:04 PM
     */
    function nowTime()
    {
        return \Carbon\Carbon::now()->timezone('Asia/Shanghai')->timestamp;
    }
}
if (!function_exists('jwtToken')) {
    /**
     * token生成
     *
     * @param array $params
     *
     * @return string
     * @author  maxiongfei <maxiongfei@vchangyi.com>
     * @date    2019/1/21 3:23 PM
     */
    function jwtToken($params)
    {
        $params = is_array($params) ? json_encode($params) : $params;

        $payload = [
            'iss' => "michaelkors-jwt", // Issuer of the token
            'sub' => $params, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + env('JWT_TTL') // Expiration time
        ];

        return \Firebase\JWT\JWT::encode($payload, env('JWT_SECRET'));
    }
}

if (!function_exists('parseJwtToken')) {
    /**
     * 获取jwt token ,去除Bearer
     *
     * @param string $token
     *
     * @return bool|string
     * @author  maxiongfei <maxiongfei@vchangyi.com>
     * @date    2019/1/21 5:51 PM
     */
    function parseJwtToken($token = '')
    {
        $bool = strpos($token, "Bearer ");
        if ($bool !== false && $bool == 0) {
            $token = substr($token, 7);
        }

        return $token;
    }
}

if (!function_exists('xml2arr')) {
    /**
     * 解析xml2array
     *
     * @param string $xml
     *
     * @return mixed
     * @author  maxiongfei <maxiongfei@vchangyi.com>
     * @date    2019/4/25 10:41 AM
     */
    function xml2arr(string $xml = ''): array
    {
        $parser = xml_parser_create(); //创建解析器
        xml_parse_into_struct($parser, $xml, $values, $index); //解析到数组
        xml_parser_free($parser); //释放资源

        return $values;
    }
}
if (!function_exists('guid')) {
    function guid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charId = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charId, 0, 8).$hyphen.substr($charId, 8, 4).$hyphen.substr($charId, 12,
                    4).$hyphen.substr($charId, 16, 4).$hyphen.substr($charId, 20, 12).chr(125);// "}"
            return $uuid;
        }
    }
}

if (!function_exists('list_to_tree')) {
    function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
    {
        /**
         * 创建Tree
         */
        $tree = [];
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }

        return $tree;
    }
}

if (!function_exists('public_path')) {
    /**
     * @description get the configuration path
     *
     * @param string $path
     *
     * @return string
     * @author      guilong
     * @date        2018-08-01
     */
    function public_path($path = '')
    {
        return app()->basePath().'/public'.($path ? '/'.$path : $path);
    }
}

if (!function_exists('route_parameter')) {
    /**
     * Get a given parameter from the route.
     *
     * @param      $name
     * @param null $default
     *
     * @return mixed
     */
    function route_parameter($name, $default = null)
    {
        $routeInfo = app('request')->all();

        return isset($routeInfo[$name]) && $routeInfo[$name] ? $routeInfo[$name] : $default;
    }
}

if (!function_exists('isJson')) {
    /**
     * 判断字符串是否是json
     *
     * @param string $data
     * @param bool   $assoc
     *
     * @return bool|mixed|string
     * @author  caobin <caobin@vchangyi.com>
     * @date    2019/9/6 14:30
     */
    function isJson($data = '', $assoc = false)
    {
        $data = json_decode($data, $assoc);
        if (($data && is_object($data)) || (is_array($data) && !empty($data))) {
            return $data;
        }

        return false;
    }

}

if (!function_exists('decodeJwt')) {

    /**
     * 解密jwt token
     *
     * @param $token
     *
     * @return array|bool
     * @throws Exception
     * @author  maxiongfei <maxiongfei@vchangyi.com>
     * @date    2019/10/9 10:21 AM
     */
    function decodeJwt($token)
    {
        $tks = explode('.', $token);

        if (count($tks) != 3) {
            return false;
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        if (null === ($header = JWT::jsonDecode(JWT::urlsafeB64Decode($headb64)))) {
            return false;
        }
        if (null === $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64))) {
            return false;
        }
        if (false === ($sig = JWT::urlsafeB64Decode($cryptob64))) {
            return false;
        }

        return (array)$payload;

    }
}

if (!function_exists('array_combine_by_key')) {
    /**
     * 根据数组指定的键对应的值, 作为新数组的键名
     *
     * @param array $arr 二维数组
     * @param string $key 键名
     *
     * @return array
     */
    function array_combine_by_key($arr, $key)
    {
        $keys = array_column($arr, $key);
        return array_combine($keys, $arr);
    }
}