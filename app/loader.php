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
			else{
				$this->site->index();
			}
		}
		//no site requested, open home page
		else{
			require './app/loaders/home.php';
			$home=new Home();
			$home->index();
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