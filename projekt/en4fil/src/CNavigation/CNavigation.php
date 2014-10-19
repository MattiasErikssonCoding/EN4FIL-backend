<?php
class CNavigation {
	public static function GenerateMenu($menu) {
      		$html = "<nav>\n<ul class='{$menu['class']}'>\n";
      		foreach($menu['items'] as $item) {
        		$selected = $menu['callback_selected']($item['url']) ? " class='selected' " : null;
        		$html .= "<li{$selected}><a href='{$item['url']}' title='{$item['title']}'>{$item['text']}</a></li>\n";
      		}
      		$html .= "</ul>\n</nav>\n";
      		return $html;
  	}
}; 
