<?php if (!defined('NEOFRAG_CMS')) exit;
/**************************************************************************
Copyright © 2015 Michaël BILCOT & Jérémy VALENTIN

This file is part of NeoFrag.

NeoFrag is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

NeoFrag is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with NeoFrag. If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

class Form2_IconPicker extends Form2_Labelable
{
	public function __invoke($name)
	{
		$this->_template[] = function(&$input){
			$this	->css('bootstrap-iconpicker.min')
					->js('bootstrap-iconpicker-iconset-fontawesome-4.7.0.min')
					->js('bootstrap-iconpicker.min')
					->js('iconpicker');

			$input = $this	->html('button')
							->attr('type', 'button')
							->attr('class', 'btn btn-'.($this->_errors ? 'danger' : 'default').' iconpicker')
							->attr('data-icon', $this->_value)
							->attr_if($this->_disabled || $this->_read_only, 'disabled')
							->content('<i class="fa"></i>');
		};

		parent::__invoke($name);

		$this->_check[] = function($post, &$data){
			if ($this->_required && (!isset($post[$this->_name]) || $post[$this->_name] == 'fa-'))
			{
				$this->_errors[] = 'Veuillez sélectionner une icône';
			}
			else if (isset($post[$this->_name]) && $post[$this->_name] !== '' && (!preg_match('/^fa-(.+)/', $post[$this->_name], $match) || !in_array($match[1], ['500px', 'address-book', 'address-book-o', 'address-card', 'address-card-o', 'adjust', 'adn', 'align-center', 'align-justify', 'align-left', 'align-right', 'amazon', 'ambulance', 'american-sign-language-interpreting', 'anchor', 'android', 'angellist', 'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 'angle-left', 'angle-right', 'angle-up', 'apple', 'archive', 'area-chart', 'arrow-circle-down', 'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'arrow-up', 'assistive-listening-systems', 'asterisk', 'at', 'audio-description', 'backward', 'balance-scale', 'ban', 'bandcamp', 'bar-chart', 'barcode', 'bars', 'bath', 'battery-empty', 'battery-full', 'battery-half', 'battery-quarter', 'battery-three-quarters', 'bed', 'beer', 'behance', 'behance-square', 'bell', 'bell-o', 'bell-slash', 'bell-slash-o', 'bicycle', 'binoculars', 'birthday-cake', 'bitbucket', 'bitbucket-square', 'black-tie', 'blind', 'bluetooth', 'bluetooth-b', 'bold', 'bolt', 'bomb', 'book', 'bookmark', 'bookmark-o', 'braille', 'briefcase', 'btc', 'bug', 'building', 'building-o', 'bullhorn', 'bullseye', 'bus', 'buysellads', 'calculator', 'calendar', 'calendar-check-o', 'calendar-minus-o', 'calendar-o', 'calendar-plus-o', 'calendar-times-o', 'camera', 'camera-retro', 'car', 'caret-down', 'caret-left', 'caret-right', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'caret-up', 'cart-arrow-down', 'cart-plus', 'cc', 'cc-amex', 'cc-diners-club', 'cc-discover', 'cc-jcb', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'certificate', 'chain-broken', 'check', 'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'child', 'chrome', 'circle', 'circle-o', 'circle-o-notch', 'circle-thin', 'clipboard', 'clock-o', 'clone', 'cloud', 'cloud-download', 'cloud-upload', 'code', 'code-fork', 'codepen', 'codiepie', 'coffee', 'cog', 'cogs', 'columns', 'comment', 'commenting', 'commenting-o', 'comment-o', 'comments', 'comments-o', 'compass', 'compress', 'connectdevelop', 'contao', 'copyright', 'creative-commons', 'credit-card', 'credit-card-alt', 'crop', 'crosshairs', 'css3', 'cube', 'cubes', 'cutlery', 'dashcube', 'database', 'deaf', 'delicious', 'desktop', 'deviantart', 'diamond', 'digg', 'dot-circle-o', 'download', 'dribbble', 'dropbox', 'drupal', 'edge', 'eercast', 'eject', 'ellipsis-h', 'ellipsis-v', 'empire', 'envelope', 'envelope-o', 'envelope-open', 'envelope-open-o', 'envelope-square', 'envira', 'eraser', 'etsy', 'eur', 'exchange', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'expand', 'expeditedssl', 'external-link', 'external-link-square', 'eye', 'eyedropper', 'eye-slash', 'facebook', 'facebook-official', 'facebook-square', 'fast-backward', 'fast-forward', 'fax', 'female', 'fighter-jet', 'file', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-o', 'file-pdf-o', 'file-powerpoint-o', 'files-o', 'file-text', 'file-text-o', 'file-video-o', 'file-word-o', 'film', 'filter', 'fire', 'fire-extinguisher', 'firefox', 'first-order', 'flag', 'flag-checkered', 'flag-o', 'flask', 'flickr', 'floppy-o', 'folder', 'folder-o', 'folder-open', 'folder-open-o', 'font', 'font-awesome', 'fonticons', 'fort-awesome', 'forumbee', 'forward', 'foursquare', 'free-code-camp', 'frown-o', 'futbol-o', 'gamepad', 'gavel', 'gbp', 'genderless', 'get-pocket', 'gg', 'gg-circle', 'gift', 'git', 'github', 'github-alt', 'github-square', 'gitlab', 'git-square', 'glass', 'glide', 'glide-g', 'globe', 'google', 'google-plus', 'google-plus-official', 'google-plus-square', 'google-wallet', 'graduation-cap', 'gratipay', 'grav', 'hacker-news', 'hand-lizard-o', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'hand-paper-o', 'hand-peace-o', 'hand-pointer-o', 'hand-rock-o', 'hand-scissors-o', 'handshake-o', 'hand-spock-o', 'hashtag', 'hdd-o', 'header', 'headphones', 'heart', 'heartbeat', 'heart-o', 'history', 'home', 'hospital-o', 'hourglass', 'hourglass-end', 'hourglass-half', 'hourglass-o', 'hourglass-start', 'houzz', 'h-square', 'html5', 'i-cursor', 'id-badge', 'id-card', 'id-card-o', 'ils', 'imdb', 'inbox', 'indent', 'industry', 'info', 'info-circle', 'inr', 'instagram', 'internet-explorer', 'ioxhost', 'italic', 'joomla', 'jpy', 'jsfiddle', 'key', 'keyboard-o', 'krw', 'language', 'laptop', 'lastfm', 'lastfm-square', 'leaf', 'leanpub', 'lemon-o', 'level-down', 'level-up', 'life-ring', 'lightbulb-o', 'line-chart', 'link', 'linkedin', 'linkedin-square', 'linode', 'linux', 'list', 'list-alt', 'list-ol', 'list-ul', 'location-arrow', 'lock', 'long-arrow-down', 'long-arrow-left', 'long-arrow-right', 'long-arrow-up', 'low-vision', 'magic', 'magnet', 'male', 'map', 'map-marker', 'map-o', 'map-pin', 'map-signs', 'mars', 'mars-double', 'mars-stroke', 'mars-stroke-h', 'mars-stroke-v', 'maxcdn', 'meanpath', 'medium', 'medkit', 'meetup', 'meh-o', 'mercury', 'microchip', 'microphone', 'microphone-slash', 'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mixcloud', 'mobile', 'modx', 'money', 'moon-o', 'motorcycle', 'mouse-pointer', 'music', 'neuter', 'newspaper-o', 'object-group', 'object-ungroup', 'odnoklassniki', 'odnoklassniki-square', 'opencart', 'openid', 'opera', 'optin-monster', 'outdent', 'pagelines', 'paint-brush', 'paperclip', 'paper-plane', 'paper-plane-o', 'paragraph', 'pause', 'pause-circle', 'pause-circle-o', 'paw', 'paypal', 'pencil', 'pencil-square', 'pencil-square-o', 'percent', 'phone', 'phone-square', 'picture-o', 'pie-chart', 'pied-piper', 'pied-piper-alt', 'pied-piper-pp', 'pinterest', 'pinterest-p', 'pinterest-square', 'plane', 'play', 'play-circle', 'play-circle-o', 'plug', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 'podcast', 'power-off', 'print', 'product-hunt', 'puzzle-piece', 'qq', 'qrcode', 'question', 'question-circle', 'question-circle-o', 'quora', 'quote-left', 'quote-right', 'random', 'ravelry', 'rebel', 'recycle', 'reddit', 'reddit-alien', 'reddit-square', 'refresh', 'registered', 'renren', 'repeat', 'reply', 'reply-all', 'retweet', 'road', 'rocket', 'rss', 'rss-square', 'rub', 'safari', 'scissors', 'scribd', 'search', 'search-minus', 'search-plus', 'sellsy', 'server', 'share', 'share-alt', 'share-alt-square', 'share-square', 'share-square-o', 'shield', 'ship', 'shirtsinbulk', 'shopping-bag', 'shopping-basket', 'shopping-cart', 'shower', 'signal', 'sign-in', 'sign-language', 'sign-out', 'simplybuilt', 'sitemap', 'skyatlas', 'skype', 'slack', 'sliders', 'slideshare', 'smile-o', 'snapchat', 'snapchat-ghost', 'snapchat-square', 'snowflake-o', 'sort', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'soundcloud', 'space-shuttle', 'spinner', 'spoon', 'spotify', 'square', 'square-o', 'stack-exchange', 'stack-overflow', 'star', 'star-half', 'star-half-o', 'star-o', 'steam', 'steam-square', 'step-backward', 'step-forward', 'stethoscope', 'sticky-note', 'sticky-note-o', 'stop', 'stop-circle', 'stop-circle-o', 'street-view', 'strikethrough', 'stumbleupon', 'stumbleupon-circle', 'subscript', 'subway', 'suitcase', 'sun-o', 'superpowers', 'superscript', 'table', 'tablet', 'tachometer', 'tag', 'tags', 'tasks', 'taxi', 'telegram', 'television', 'tencent-weibo', 'terminal', 'text-height', 'text-width', 'th', 'themeisle', 'thermometer-empty', 'thermometer-full', 'thermometer-half', 'thermometer-quarter', 'thermometer-three-quarters', 'th-large', 'th-list', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'thumb-tack', 'ticket', 'times', 'times-circle', 'times-circle-o', 'tint', 'toggle-off', 'toggle-on', 'trademark', 'train', 'transgender', 'transgender-alt', 'trash', 'trash-o', 'tree', 'trello', 'tripadvisor', 'trophy', 'truck', 'try', 'tty', 'tumblr', 'tumblr-square', 'twitch', 'twitter', 'twitter-square', 'umbrella', 'underline', 'undo', 'universal-access', 'university', 'unlock', 'unlock-alt', 'upload', 'usb', 'usd', 'user', 'user-circle', 'user-circle-o', 'user-md', 'user-o', 'user-plus', 'users', 'user-secret', 'user-times', 'venus', 'venus-double', 'venus-mars', 'viacoin', 'viadeo', 'viadeo-square', 'video-camera', 'vimeo', 'vimeo-square', 'vine', 'vk', 'volume-control-phone', 'volume-down', 'volume-off', 'volume-up', 'weibo', 'weixin', 'whatsapp', 'wheelchair', 'wheelchair-alt', 'wifi', 'wikipedia-w', 'window-close', 'window-close-o', 'window-maximize', 'window-minimize', 'window-restore', 'windows', 'wordpress', 'wpbeginner', 'wpexplorer', 'wpforms', 'wrench', 'xing', 'xing-square', 'yahoo', 'y-combinator', 'yelp', 'yoast', 'youtube', 'youtube-play', 'youtube-square'])))
			{
				$this->_errors[] = 'Icône inconnue';
			}
		};

		return $this;
	}
}

/*
NeoFrag Alpha 0.1.7
./libraries/form2/iconpicker.php
*/