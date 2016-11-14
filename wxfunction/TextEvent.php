<?php
class TextEvent{
	private $text;
	private $xmlObject;
	private $content='';
	function __construct($xmlObject){
		$this->text = $xmlObject->Content;
		$this->xmlObject = $xmlObject;
	}
	public function response(){
		switch ($this->text) {
			case 'debug':
				# code...
				break;

			case '菜单':
				$this->get_menu();
				break;
			
			default:
				# code...
				break;
		}
		return $this->content;
	} 
	private function get_debug(){

	}

	private function get_menu(){
		$this->content='*回复“菜单”，显示本操作菜单。
        *回复“笑话”随机生成笑话。
        *回复“糗事”随机生成糗事。
        *回复“微小说”随机生成微小说。
        *回复“文章”随机生成one和redream文章           
		*回复‘表白’进入表白墙；
		*回复‘心情’进入心情簿；
		*回复‘许愿’进入许愿墙；
		*回复‘广场’进入南开广场；
		';
	}
}


