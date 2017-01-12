<?php
	define('AJAX', 'XMLHttpRequest');

	function get($route, $callback) {
		Route::process($route, $callback, 'GET');
	}

	function post($route, $callback) {
		Route::process($route, $callback, 'POST');
	}

	function put($route, $callback) {
		Route::process($route, $callback, 'PUT');
	}

	function delete($route, $callback) {
		Route::process($route, $callback, 'DELETE');
	}

	function ajax($route, $callback) {
		Route::process($route, $callback, AJAX);
	}

	/*
	 * -------------------------------------------------------------
	 * Description:php轻框架路由类
	 * -------------------------------------------------------------
	 * 功能：处理当前URL，按照定义的路由进行匹配并分配控制器与方法
	 * -------------------------------------------------------------
	 * @param $foundRoute:
	 * @param $URI:当前的URL
	 * @param $params：
	 * @param $sMethod:传递数据的方式get,post,put,delete,ajax
	 * @param $route:路由
	 * @param $headers:给浏览器的头文件
	 * @param $ip:
	 * @param $part:控制器中的模块的文件夹的名字，例如后台admin,controller文件夹下要求有相应的admin的文件夹
	 * @param $classname:定义的路由指向某一类
	 * @param $action:定义的路由指向类中的某一方法
	 */
	class Route {
		public static $foundRoute = FALSE;
		public $URI        = '';
		public $params     = array();
		public $sMethod    = '';
		public $route      = '';
		public $headers   = array();
		public $ip         = '';
		public $part       = '';
		public $classname = '';
		public $action     = 'index';

		/*
		 * ------------------------
		 *Afro类初始化函数
		 * ------------------------
		 * @param boolean $foundRoute
		 * @param string $URI:当前的URL
		 * @param array $params:参数
		 * @param string $sMethod:传递数据方式get、post、put、delete、ajax
		 * @param array $headers:浏览器头文件
		 * @param string $ip:ip地址
		 */
		public function __construct() {
			ob_start();
			$this -> URI        = $this -> getURI();
			$this -> params     = explode('/', trim($this -> URI, '/'));
			$this -> params     = array_slice($this -> params, -1, 1);
			$this -> sMethod    = $this -> getMethod();

			spl_autoload_register("self::loadClass");

			// Request Data
			if (!function_exists('getallheaders')) {
				function getallheaders() {
					foreach ($_SERVER as $name => $value) {
						if (substr($name, 0, 5) == 'HTTP_') {
							$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
						}
					}
					return $headers;
				}
			}
			$this->headers = getallheaders();
			$this->ip       = $_SERVER['REMOTE_ADDR'];
		}
		/*
		 * ---------------------
		 * 自动加载类
		 * ---------------------
		 * 功能：new一个不存在的类时，自动加载类文件
		 */
		public static function loadClass($class){
			$class = str_replace('\\', '/', $class);
			$class = substr($class, 0, strrpos($class, '/'));
			$class = CONTROLLER_PATH. $class. ".class.php";
			if(!file_exists($class)){
				header("HTTP/1.1 404 Not Found");
			}
			require_once $class;
		}
		/*
		 * ----------------------
		 * 实例化路由类
		 * ----------------------
		 */
		public static function getInstance() {
			static $instance = NULL;
			if($instance === NULL) {
				$instance = new Route;
			}
			return $instance;
		}
		/*
		 * ----------------------
		 * 按路由分配控制器
		 * ----------------------
		 *@param string $route
		 *@param object or string $callback
		 *@param string $type
		 *@return object callback or include a class
		 */
		public static function process($route, $callback, $type) {
			$Route = static::getInstance();

			$Route -> route = $route;

			if($type === AJAX) {
				$Route -> sMethod = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : 'GET';
			}
			//处理路由:判断是否有大括号，如果有大括号则代表有确定key的参数
			$count_1 = substr_count($route, "{");
			if($count_1 > 0) {
				//得到每个参数的key
				$arr_1 = getCharpos($route, "{");
				$arr_2 = getCharpos($route, "}");
				$param_key = array();
				$str = "(.*?)";
				$count = count($arr_1);
				for($i = 0; $i < $count; $i ++) {
					$param_key[$i] = substr($route, ($arr_1[$i] + 1), ($arr_2[$i] - $arr_1[$i] - 1));
				}
				//替换route
				for($i = 0; $i < $count; $i ++) {
					$po_1 = strpos($route, "{");
					$po_2 = strpos($route, "}");
					$route = substr_replace($route, $str, $po_1, ($po_2 - $po_1 + 1));
				}
			}
			if(static::$foundRoute || (!preg_match('@^'.$route.'(?:\.(\w+))?$@uD', $Route->URI, $matches) || $Route->sMethod != $type)) {
				return FALSE;
			}

			static::$foundRoute = TRUE;
			//没有大括号的情况
			$route = '/^' . str_replace('/', '\/', $route) . '$/';
			if(preg_match($route, $Route -> URI, $params)) {
				if($Route -> sMethod == "GET" || $Route -> sMethod == "PUT" ||$Route -> sMethod == "DELETE") {
					if(isset($param_key)) {
						for($i = 0; $i < count($param_key); $i ++) {
							$_GET[$param_key[$i]] = $params[$i + 1];
						}
					} else {
						$count = count($params) - 1;
						for($i = 0; $i < $count; $i ++) {
							$_GET[$i] = $params[$i+1];
						}
					}
				}
				$routeParams = [$Route];
				$routeParams = $routeParams + $params;
				//$callback如果是闭包函数则返回闭包函数，若是字符串，则分割字符串找到对应的类和方法
				if(is_object($callback)) {
					return call_user_func_array($callback, $routeParams);
				} else {
					$part_exist = strpos($callback, "/");
					$action_exist = strpos($callback, "@");
					if($part_exist !== false) {
						$arr = explode("/", $callback);
						$Route -> part = $arr[0];
						$callback = $arr[1];
					}
					if($action_exist !== false) {
						$arr = explode("@", $callback);
						$callback = $arr[0];
						$Route -> action = $arr[1];
					}
					$Route -> classname = ucwords($callback);
					if($Route -> part != '') {
						$class = "\\{$Route -> part}\\{$Route -> classname}\\{$Route -> part}"."{$Route -> classname}";
					} else {
						$class = "\\{$Route -> classname}\\{$Route -> classname}";
					}
					$action = $Route -> action;
					$DemoObj = new $class();
					$DemoObj -> $action();
				}
			}else{
				return $callback($Route);
			}
		}
		/*
		 * ----------------------------
		 * 得到传递数据的方法
		 * ----------------------------
		 */
		protected function getMethod() {
			return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		}

		/*
		 * ---------------------------
		 * 得到当前URI
		 * ---------------------------
		 */
		public function getURI($prefixSlash = TRUE) {
			if(isset($_SERVER['PATH_INFO'])) {
				$uri = $_SERVER['PATH_INFO'];
			}elseif(isset($_SERVER['REQUEST_URI'])) {
				$uri = $_SERVER['REQUEST_URI'];
				if(strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
					$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
				}elseif(strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
					$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
				}
				$_SERVER['QUERY_STRING'] = '';
				$_GET                    = array();
			} else {
				return FALSE;
			}
			$URIString = ($prefixSlash ? '/' : '') . str_replace(array('//', '../'), '/', trim($uri, '/'));
			return $URIString;
		}
	}

	$Route = Route::getInstance();

