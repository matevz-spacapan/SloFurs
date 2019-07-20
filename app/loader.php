<?php
class Application{
	private $site=null;
	private $action=null;
	private $param_1=null;
	private $param_2=null;
	private $param_3=null;
	private $param_4=null;
	private $param_5=null;

	public function __construct(){
		$this->splitUrl();
		//if site was requested
		if(!isset($_SESSION['lang'])){
			$_SESSION['lang']='si';
		}
		require_once "public/spyc/spyc.php";
		require_once 'public/i18n/i18n.class.php';
		$i18n = new i18n('app/lang/lang_{LANGUAGE}.yml', 'public/i18n/langcache/');
		$i18n->init();
		if(file_exists('./app/loaders/'.$this->site.'.php')){
			require './app/loaders/'.$this->site.'.php';
			$this->site=new $this->site();
			if(method_exists($this->site, $this->action)){
				if(isset($this->param_5)){
					$this->site->{$this->action}($this->param_1, $this->param_2, $this->param_3, $this->param_4, $this->param_5);
				}
				elseif(isset($this->param_4)){
					$this->site->{$this->action}($this->param_1, $this->param_2, $this->param_3, $this->param_4);
				}
				elseif(isset($this->param_3)){
					$this->site->{$this->action}($this->param_1, $this->param_2, $this->param_3);
				}
				elseif(isset($this->param_2)){
					$this->site->{$this->action}($this->param_1, $this->param_2);
				}
				elseif(isset($this->param_1)){
					$this->site->{$this->action}($this->param_1);
				}
				else{
					$this->site->{$this->action}();
				}
			}
			elseif($this->action==null){
				$this->site->index();
			}
			else{
				require './app/sites/404.php';
				$ouch=new Ouch();
				$ouch->index();
			}
		}
		//no site requested, open home page
		elseif($this->site==null){
			require './app/loaders/home.php';
			$home=new Home();
			$home->index();
		}
		else{
			require './app/sites/404.php';
			$ouch=new Ouch();
			$ouch->index();
		}
	}
	private function splitUrl(){
		if(isset($_GET['url'])){
			$url=rtrim($_GET['url'], '/');
			$url=filter_var($url, FILTER_SANITIZE_URL);
			$url=explode('/', $url);
			$this->site=(isset($url[0]) ? $url[0] : null);
			$this->action=(isset($url[1]) ? $url[1] : null);
			$this->param_1=(isset($url[2]) ? $url[2] : null);
			$this->param_2=(isset($url[3]) ? $url[3] : null);
			$this->param_3=(isset($url[4]) ? $url[4] : null);
			$this->param_4=(isset($url[5]) ? $url[5] : null);
			$this->param_5=(isset($url[6]) ? $url[6] : null);
		}
	}

}
