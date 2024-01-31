<?php 

include_once('DiDOM/autoloader.php');

use DiDom\Document;
use DiDom\Element;

/**
 * Extracting resource files (ex: css, js, image, font, video) from html or css
 * @author bangnd <bangnd@24h.com.vn>
 */
class HtmlCssProcessor
{
	private $content;				// nội dung html/css sau xử lý
	private $content_type; 			// kiểu nội dung: html/css
	private $origin_content;  		// nội dung html/css gốc (không đổi)
	private $content_template;		// template của nội dung html/css
	private $maps = array(); 		// mảng chứa dữ liệu mapping của các phần tử được trích xuất từ nội dung html/css
	private $wrapper = array(); 	// kí tự bao ngoài mã của phần tử (code) để cấu thành placeholder trong template của nội dung html/css
	private $demo = array(
		'image' 	=> 'https://via.placeholder.com/1920x1080',
		'video' 	=> 'https://mdn.github.io/learning-area/html/multimedia-and-embedding/video-and-audio-content/rabbit320.mp4',
		'audio' 	=> 'https://mdn.github.io/learning-area/html/multimedia-and-embedding/video-and-audio-content/viper.mp3',
		'iframe' 	=> 'https://www.youtube.com/embed/7b_SGyrgSPE',
		'link'		=> 'https://www.24h.com.vn',
		'title'		=> 'Tiêu đề demo',
		'paragraph' => 'Đây là đoạn văn demo',
	);
	/**
     * trích xuất phần tử chia làm 2 kiểu:
     * - auto: tự động, các phần tử trích xuất tự động không thể chỉnh sửa các thuộc tính, thường áp dụng cho các file css, js, ảnh icon
     * - defined: trích xuất theo cấu hình mà nsd tự định nghĩa, các phần tử được trích xuất theo cách này có thể chỉnh sửa các thuộc tính
	 * mảng định nghĩa cấu hình để trích xuất các phần tử thuộc loại phần tử (type) : image, iframe, paragraph, video, audio, title, link, css, js, font, icon
	 * data_type (kiểu dữ liệu của các thuộc tính - attribute của phần tử) được định nghĩa thành 4 dạng là: file, url,int,text dùng để validate dữ liệu
	 * - file: là path hoặc url của 1 file image, audio, video, css, js, font. phân loại để nhận biết, hiển thị form input html và so sánh, thay thế file sau này
	 * - url: là đường dẫn tới 1 website, loại trừ các đường dẫn file, ví dụ: link (href), iframe(src)
	 * - int: kiểu số nguyên, thường sử dụng cho các thuộc tính: width, height của image hay video
	 * - text: các kiểu dữ liệu dạng chuỗi, thường sử dụng cho các loại phần tử: title va paragraph, và các attribute như: image(alt), image(title), ...
	 * @var array
	 */
	private $matchers = array(
		'html' => array(
			'defined' => array(
				'wrapper' => array('[[~~', '~~]]'), // kí tự bao ngoài mã của phần tử (code) để cấu thành placeholder trong template của nội dung html/css
				'elements' => array(
					array(
						'code' 			=> 'image_%s', // định dạng mã của phần tử
						'type' 			=> 'image',     // loại phần tử
						'selector' 		=> '[data-is-df-image]',     // pattern khi cấu hình sử dụng css selector để matching phần tử
						'xpath'			=> '//[@data-is-df-image]',	// query khi cấu hình sử dụng xpath để matching phần tử
						'attributes' 	=> array(
							array(
								'name' 			=> 'src', // tên thuộc tính (attribute)
								'data_type' 	=> 'file', // kiểu dữ liệu của thuộc tính
								'is_required' 	=> true, // có bắt buộc phải nhập hay không ?
							),
						),
					),
					array(
						'code' 			=> 'iframe_%s',
						'type' 			=> 'iframe',
						'selector' 		=> 'iframe[data-is-df-iframe]',
						'xpath'			=> '//iframe[@data-is-df-iframe]',
						'attributes' 	=> array(
							array(
								'name' 			=> 'src',
								'data_type' 	=> 'url',
								'is_required' 	=> true,
							),
							array(
								'name' 			=> 'width',
								'data_type' 	=> 'int',
								'is_required' 	=> true,
							),
							array(
								'name' 			=> 'height',
								'data_type' 	=> 'int',
								'is_required' 	=> true,
							),
						),
					),
					array(
						'code' 			=> 'video_%s',
						'type' 			=> 'video',
						'selector' 		=> 'video[data-is-df-video]',
						'xpath'			=> '//video[@data-is-df-video]',
						'attributes' => array(
							array(
								'name' 			=> 'src',
								'data_type' 	=> 'file',
								'is_required' 	=> true,
							),
							array(
								'name' 			=> 'poster',
								'data_type' 	=> 'file',
								'is_required' 	=> false,
							),
							array(
								'name' 			=> 'width',
								'data_type' 	=> 'int',
								'is_required' 	=> false,
							),
							array(
								'name' 			=> 'height',
								'data_type' 	=> 'int',
								'is_required' 	=> false,
							),
						),
					),
					array(
						'code' 			=> 'audio_%s',
						'type' 			=> 'audio',
						'selector' 		=> 'audio[data-is-df-audio]',
						'xpath'			=> '//audio[@data-is-df-audio]',
						'attributes' 	=> array(
							array(
								'name' 			=> 'src',
								'data_type' 	=> 'file',
								'is_required' 	=> true,
							),
						),
					),
					array(
						'code' 			=> 'link_%s',
						'type' 			=> 'link',
						'selector' 		=> 'a[data-is-df-link]',
						'xpath'			=> '//a[@data-is-df-link]',
						'attributes' 	=> array(
							array(
								'name' 			=> 'href',
								'data_type' 	=> 'url',
								'is_required' 	=> true,
							),
							array(
								'name' 			=> 'title',
								'data_type' 	=> 'text',
								'is_required' 	=> false,
							),
							array(
								'name' 			=> 'target',
								'data_type' 	=> 'text',
								'is_required' 	=> false,
							),
							array(
								'name' 			=> 'rel',
								'data_type' 	=> 'text',
								'is_required' 	=> false,
							),
						),
					),
					array(
						'code' 			=> 'title_%s',
						'type' 			=> 'title',
						'selector' 		=> '[data-is-df-title]',
						'xpath'			=> '//@data-is-df-title',
						'attributes' 	=> array(),
					),
					array(
						'code' 			=> 'paragraph_%s',
						'type' 			=> 'paragraph',
						'selector' 		=> '[data-is-df-paragraph]',
						'xpath'			=> '//@data-is-df-paragraph',
						'attributes' 	=> array(),
					),
				),
			),
			'auto' => array( //mảng định nghĩa cấu hình để trích xuất tự động cá phần tử như file css,js,images
				'wrapper' 	=> array('{{{', '}}}'), // các kí tự bao ngoài placeholder
				'elements' 	=> array(
					array(
						'code' 			=> 'css_%s',
						'type' 			=> 'css',
						'selector' 		=> 'link[rel="stylesheet"]',
						'xpath'			=> '//link[@rel="stylesheet"]',
						'attributes' 	=> array(
							array(
								'name' 			=> 'href',
								'data_type' 	=> 'file',
								'is_required' 	=> true,
							),
						),
					),
					array(
						'code' 			=> 'js_%s',
						'type' 			=> 'js',
						'selector'		=> 'script[src]',
						'xpath'			=> '//script[@src]',
						'attributes' 	=> array(
							array(
								'name' 			=> 'src',
								'data_type' 	=> 'file',
								'is_required' 	=> true,
							),
						),
					),
					array(
						'code' 			=> 'image_%s',
						'type' 			=> 'image',
						'selector' 		=> 'img[src]:not([data-is-df-image])',
						'xpath'			=> '//img[(@src) and not(@data-is-df-image)]',
						'attributes' 	=> array(
							array(
								'name' 			=> 'src',
								'data_type' 	=> 'file',
								'is_required' 	=> true,
							),
							array(
								'name' 			=> 'alt',
								'data_type' 	=> 'text',
								'is_required' 	=> false,
							),
						),
					),
				),
			),
		),
		'css'  => array(
			'auto' => array(
				'wrapper' 	=> array('{{{', '}}}'),
				'elements' 	=> array(
					array(
						'code' 			=> 'font_%s',
						'type'			=> 'font',
						'data_type' 	=> 'file',
						'regex' 		=> '/url\([\"\']*([^\)\[]+?\.(?:woff|eot|woff2|ttf|svg)[^\"\'\)]*)/i', // exception: IMG_0445 (2).jpg
					),
					array(
						'code' 			=> 'image_%s',
						'type'			=> 'image',
						'data_type' 	=> 'file',
						'regex' 		=> '/url\([\"\']*([^\)\[]+?\.(?:png|jpg|jpeg|gif|svg)[^\"\'\)]*)/i',
					),
				),
			),
		),
	);
	
	function __construct($content, $content_type = 'html')
	{
		// mb_internal_encoding("UTF-8");
		$this->content_template = $this->content = $this->origin_content = html_entity_decode($content);
		$this->content_type  	= $content_type;
	}

    /**
     * hàm thực hiện trích xuất các phần tử từ nội dung html/css
     * @author bangnd <bangnd@24h.com.vn>
     * @return HtmlCssProcessor
     * @throws Exception
     */
	public function process()
	{
		$matchers = $this->matchers[$this->content_type];
		foreach ($matchers as $match_type => $m) { // match_type: auto/defined
			// set wrapper
			$this->wrapper = $m['wrapper'];
			// loop over configs
			foreach ($m['elements'] as $element_type => $element_config) {
				// trích xuất phần tử dựa theo cấu hình định nghĩa của loại phần tử (type)
				if ($this->content_type == 'html') {
					$this->extractHtmlElements($element_config, $match_type);
				} elseif ($this->content_type == 'css') {
					// TODO: 
					$this->extractCssElements($element_config, $match_type);
				}
			}
		}
		return $this;
	}

    /**
     * thông tin sau trích xuất phần tử từ nội dung html
     * @author bangnd <bangnd@24h.com.vn>
     * @param null|string $key
     * @return array
     */
	public function info($key = null)
	{
		$info =  array(
			'content_origin'	=> $this->origin_content,   // nội dung gốc
			'content'			=> $this->content,          // nội dung sau trích xuất
			'template' 			=> $this->content_template, // template của nội dung gốc
			'map' 				=> $this->maps,             // dữ liệu mapping của các phần tử được trích xuất tự động (auto) hoặc tự định nghĩa (defined)
		);

		if (is_null($key)) {
			return $info;
		}

		return empty($info[$key]) ? [] : $info[$key];

	}

	/**
	 * Matching phần tử trong nội dung html theo cấu hình sử dụng css selector hoặc xpath
	 * @author bangnd <bangnd@24h.com.vn>
	 * @param  Document $document [instance of DiDom\Document]
	 * @param  array    $config   [mảng cấu hình để trích xuất các loại phần tử được định nghĩa]
	 * @return array
	 */
	private function getElements(Document $document, array $config)
	{
		if ( !empty($config['selector']) ) {
			return $document->find($config['selector']);
		} elseif ( !empty($config['xpath']) ) {
			return $document->xpath($config['xpath']);
		}
		return [];
	}

    /**
     * trích xuất các phần tử trong nội dung html
     * @author bangnd <bangnd@24h.com.vn>
     * @param  array $config [mảng cấu hình để matching phần tử trong nội dung html]
     * @param  string $match_type [kiểu trích xuất: auto/defined]
     * @return HtmlCssProcessor
     * @throws Exception
     */
	private function extractHtmlElements(array $config, $match_type)
	{
		$document = new Document();
		$document->preserveWhiteSpace();
		$document->loadHtml( $this->content_template, LIBXML_NOBLANKS );
		
		if( count( $elements = $this->getElements( $document, $config ) ) > 0 ) {
			// $element is enstance of DiDom\Element
			foreach ( $elements as $stt => $element ) {
				$result = array();
				$result['stt'] = $stt;
				// ex: css/js/image/paragraph,...
				$result['type'] = $element_type = $config['type'];
				// ex: css_1
			    $result['code'] = $code = vsprintf( $config['code'], $stt );
			    // ex: {{{css_1}}} or [[~~image_1~~]]
			    // danh dau vi tri thay the du lieu nhap tu nguoi dung
			    $result['placeholder']  = $this->wrapper[0] . $code . $this->wrapper[1];
			    // lay tat ca attribute cua phan tu
			    $result['attributes'] 	= $element->attributes();
			    // ex: <img alt="abc" src="abc.png" data-is-df-image/>
			    $result['html_preview'] = $this->removeAutoGeneratedCloseTag( $element->html() );
			    // @reference: http://sg2.php.net/manual/en/domnode.getnodepath.php
			    // co the su dung de tim kiem phan tu sau nay su dung XPath
			    $result['node_path']	= $element->getNode()->getNodePath();
			    // trich xuat du lieu tu dong va co the chinh sua
			    $this->getArrData($element, $config, $result);
			    // trich xuat quy tac cho cac phan tu co the chinh sua
			    $this->getDefinedRules($element, $config, $result);
			    // remove data-df-rules attribute
			    $element->removeAttribute('data-df-rules');
			    unset($result['attributes']['data-df-rules']);
			    // luu vao mang cac phan tu trich xuat dc tu html
			    $this->maps[$match_type][$element_type][] = $result;
			    // xuat ra template html, remove the </source> tu dong sinh ra sau khi load html qua DomDocument
			    $this->content_template = $this->removeAutoGeneratedCloseTag( urldecode($document->html()) );
			}
		}
		return $this;
	}

	/**
     * Loại bỏ các thẻ tự động sinh ra khi load html qua DomDocument gây mất validate html
     * ví dụ: đối với thẻ <source> không có thẻ đóng </source>
	 * @param  string 	$str
	 * @return string
	 */
	public function removeAutoGeneratedCloseTag($str)
	{
		return str_replace('</source>', '', $str);
	}

    /**
     * Trích xuất dữ liệu từng thuộc tính của phần tử
     * @author bangnd <bangnd@24h.com.vn>
     * @param  Element $element [instance of DiDom\Element]
     * @param  array $config [cấu hình dùng để trích xuất dữ liệu của phần tử]
     * @param  array $result [mảng dữ liệu mapping sau trích xuất của phần tử]
     * @return array
     */
	private function getArrData(Element $element, array $config, array &$result)
	{
		if ( !empty($config['attributes']) && !empty($result['attributes']) ) {
			foreach ($config['attributes'] as $index => $attr_config) {
				$attribute = $attr_config['name'];
				// mặc định phần tử đầu tiên trong mảng attributes là thuộc tính chính
				if($index == 0) $result['main_attribute'] = $attribute;
				// nếu là attribute phụ sẽ có hậu tố phía sau mã phần tử (code)
				// ex: <img src="abc.png" alt="abc" data-is-df-image/> ===>  <img src="[[image_0]]" alt="[[image_0__alt]]" data-is-df-image/>
				$code = $index == 0 ? $result['code'] : $result['code'] . '__' . $attribute; 
				// ex: {{{css_1}}} or [[~~image_1~~]]
				// mã đánh dấu vị trí thay thế dữ liệu nhập từ người dùng
				$placeholder = $this->wrapper[0] . $code . $this->wrapper[1];

				if ( !is_null($data = $element->getAttribute( $attribute )) ) {
					// thay thế mã placeholder vào nội dung html gốc để cấu thành template của nội dung html
					$element->setAttribute( $attribute, $placeholder );
				} elseif ($config['type'] == 'audio' || $config['type'] == 'video') {
		    		// SPEC: xử lý cho trường hợp thuộc audio hoặc video có nhiều hơn 1 source => thuộc tính src không nằm trên thẻ <audio>/<video>
		    		// <video controls>
                    //		<source src="myVideo.mp4" type="video/mp4">
					// </video>
					if ( count($children = $element->children()) > 0 ) {
						foreach ($children as $child) {
							// chi lay attribute o <source> dau tien tim duoc
							if ($child->tag == 'source') {
								if ( !is_null($data = $child->getAttribute( $attribute )) ) {
									// thay the placehoder vao html -> contruct template
									$child->setAttribute( $attribute, $placeholder );
									break;
								}
							}
						}
					}
				}

				if (!is_null($data)) {
					$result['arr_data'][$attribute]	= array(
						'type' 			=> $result['type'], // loại phần tử: image, video,...
						'code' 			=> $code,           // mã của thuộc tính, là duy nhất, không trùng lặp
						'placeholder' 	=> $placeholder,    // mã thay thế cảu phần tử trong template
						'data' 			=> $data,           // data của thuộc tính
						'data_origin'	=> $data,           // data gốc, không đổi, dùng để so sánh sau này
						'data_type'		=> $attr_config['data_type'],
						'attr'			=> $attribute,      // tên thuộc tính chính, vd: với image thường là src
						'is_main_attr'	=> ($index == 0) ? true : false, // đánh dấu thuộc tính chính
						'is_required' 	=> $attr_config['is_required'], // đánh dấu có bắt buộc nhập hay không ?
						'html_tag'		=> $element->tag,   // vd: với paragraph là p
					);
				}
			}
			// SPEC: xu ly them doi voi image ton tai attribute co dang: data-src*
			// ex: <img src="abc.png" data-src="abc_1.png" data-src-789px="abc_2.png" />
			if ($config['type'] == 'image') {
				if ($element->tag == 'img') {
					$pattern = '/^data-src.*$/s';
					// loop qua toan bo attribute cua phan tu
					foreach ($result['attributes'] as $attribute => $value) {
						// lay ra cac attribute co dang: data-src*
						if (preg_match($pattern, $attribute, $matches)) {

							$code 		 = $result['code'] . '__' . $attribute;
							$placeholder = $this->wrapper[0] . $code . $this->wrapper[1];
							$data 		 = $result['attributes'][$attribute];

							$result['arr_data'][$attribute] = array(
								'type'			=> 'image',
								'code' 			=> $code,
								'placeholder' 	=> $placeholder,
								'data' 			=> $data,
								'data_origin'	=> $data,
								'data_type'		=> 'file',
								'attr'			=> $attribute,
								'is_main_attr'	=> false,
								'is_required' 	=> $attr_config['is_required'],
								'html_tag'		=> $element->tag,
							);
							// thay the placehoder vao html -> contruct template
							$element->setAttribute( $attribute, $placeholder );
						}
					}
				} else {
					// xu ly them voi truong hop image la background cua 1 phan tu khong phai <img>, (dang bai onepage)
					// ex: <div style="background-image:url(https://example.com/abc.jpg)" data-is-df-image>
					$pattern = '/\bbackground(?:-image)?:.*?url\([\'\"]?([^\'\"\)]*)/si';
					if (!empty($result['attributes']['style']) 
						&& preg_match($pattern, $result['attributes']['style'], $matches)) {

						$attribute = 'style';
						$result['main_attribute'] = $attribute;

						$code 		 = $result['code'] . '__' . $attribute;
						$placeholder = $this->wrapper[0] . $code . $this->wrapper[1];
						$data 		 = $matches[1];

						$result['arr_data'][$attribute] = array(
							'type'			=> 'image',
							'code' 			=> $code,
							'placeholder' 	=> $placeholder,
							'data' 			=> $data,
							'data_origin'	=> $data,
							'data_type'		=> 'file',
							'attr'			=> $attribute,
							'is_main_attr'	=> true,
							'is_required' 	=> $attr_config['is_required'],
							'html_tag'		=> $element->tag,
						);

						$style_placeholder = str_replace($data, $placeholder, $result['attributes']['style']);

						// thay the placehoder vao html -> contruct template
						$element->setAttribute( $attribute, $style_placeholder );
					}
				}
			
			}

			// need to urlencode coz {{{}}} will be convert to %7B%7B%7B %7D%7D%7D after setAttribute()
			// ex: <img alt="abc"  src="{{{image_1}}}" data-is-df-image="1"/>
			$result['html_template'] = $this->removeAutoGeneratedCloseTag( urldecode($element->html()) );

		} elseif ($config['type'] == 'title' // neu la tieu de
			|| $config['type'] == 'paragraph' ) { // hoac doan van

			$code 			= $result['code'];
			$placeholder 	= $this->wrapper[0] . $code . $this->wrapper[1];
			// vi title va paragraph ko su dung attribute nen mac dinh attribute la text
			$result['main_attribute'] = $attribute = 'text'; 
			// ex: <p data-is-df-paragraph>this is a <strong>paragraph</strong></p>
			// => data: this is a <strong>paragraph</strong>
			$data = $this->removeAutoGeneratedCloseTag( $element->innerHtml() );

			$result['arr_data'][$attribute] = array(
				'type'			=> $config['type'],
				'code' 			=> $code,
				'placeholder' 	=> $placeholder,
				'data' 			=> $data,
				'data_origin'	=> $data,
				'data_type'		=> ( $config['type'] == 'paragraph' ) ? 'html' : 'text',
				'attr'			=> $attribute,
				'is_main_attr'	=> true,
				'is_required' 	=> true,
				// chu yeu su dung trong doan van
				// neu html_tag la p,span thi noi dung editor can phai loai bo het the p bao ngoai, tranh loi html
				// neu html_tag la div thi lay toan bo noi dung trong editor
				'html_tag'		=> $element->tag,
			);
			// thay the placehoder vao html -> contruct template
			$element->setInnerHtml($placeholder);
			// ex: <p data-is-df-paragraph>[[~~paragraph_2~~]]</p>
			$result['html_template'] = urldecode( $element->html() );
		}

		return $result;
	}

    /**
     * Trích xuất các quy tắc của các phần tử có thể chỉnh sửa
     * @author bangnd <bangnd@24h.com.vn>
     * @param  Element $element [instance of DiDom\Element]
     * @param  array $config [cấu hình dùng để trích xuất dữ liệu của phần tử]
     * @param  array $result [mảng dữ liệu mapping sau trích xuất của phần tử]
     * @return array
     * @throws Exception
     */
	private function getDefinedRules(Element $element, array $config, array &$result)
	{
		if ( $df_str = $element->getAttribute('data-df-rules') ) {
			// chuoi dinh nghia se co dang: 
			// df_str = [<attribute1>:]<rule1> = <value1>;<rule2> < <value2>;... | <attribute2>:<rule1> = <value1>;...
			// ex: width>600;height>100;file_size<3MB;type=png,jpg|data-src-768px:width<900;file_size<500KB
			if ( !empty($df_str = str_replace(' ','', $df_str)) ) { // remove whitespace from rule string
				if (!empty($arr_df_lv1 = explode('|', $df_str))) {
					foreach ($arr_df_lv1 as $k => $df_str_lv1) {
						// ex: df_str_lv1 = 'data-src-768px:width<900;file_size<500KB'
						if ( empty($arr_df_lv2 = explode(':', $df_str_lv1)) ) continue;

						$count = count($arr_df_lv2);
						if ($count == 2) { // day du thanh phan
							$attribute = $arr_df_lv2[0];
							$rules_str = $arr_df_lv2[1];
						} elseif ($count == 1 && $k == 0) { 
							// ko du thanh phan, nhung la phan tu dau tien 
							// thi mac dinh la chuoi dinh nghia cho attribute chinh
							$attribute = $result['main_attribute'];
							$rules_str = $arr_df_lv2[0];
						} else {
							throw new \Exception("Chuỗi tự định nghĩa không đúng định dạng: $df_str");
						}

						if ( empty($rules = explode(';', $rules_str)) ) continue;

						$meta = array();
						foreach($rules as $rule) { // ex: width>200, type=jpg,png
						    $pattern = '/([a-z\_]+)([\>\=\<]{1,2})([^\>\=\<]+)/s';
						    // bieu thuc  khong dug dinh dang thi bo qua
						    if(!preg_match($pattern, $rule, $matches)) continue;
						    /**
						     * bieu thuc hop le sau khi match
						     * array(
						     * 	0 => width>200
						     * 	1 => width
						     * 	2 => >
						     * 	3 => 200
						     * )
						     */
						    if(count($matches) == 4) {
						        $meta[$matches[1]] = array(
						            'operator' => $matches[2], 
						            'value' => $matches[3],
						        );
						    }
						}
						// luu lai vao mang kq
						if ( !empty($result['arr_data'][$attribute]) ) {
							$result['arr_data'][$attribute]['metadata'] = $meta;
						}
					}
				}
			}
		} else { // neu khong thi lay cac quy tac mac dinh tu cau hinh
			$configAll = _get_module_config('template_magazine', 'template_element_config');
			// cac quy tac nay mac dinh su dung cho attribute chinh cua phan tu
			$attribute = $result['main_attribute'];
			$meta = array();
			if ( !empty($config = $configAll[$result['type']]) ) { // $result['type'] = image,video,audio,...
				// dung luong toi da
				if (!empty($config['max_file_size'])) {
					$meta['file_size'] = array(
						'operator' => '<', 
						'value' => mzt_readable_filesize( intval($config['max_file_size']), 0),
					);
				}
				// chieu rong toi da
				if (!empty($config['max_width'])) {
					$meta['width'] = array(
						'operator' => '<', 
						'value' => intval($config['max_width']),
					);
				}
				// chieu cao toi da
				if (!empty($config['max_height'])) {
					$meta['height'] = array(
						'operator' => '<', 
						'value' => intval($config['max_height']),
					);
				}
				// dinh dang file cho phep upload
				if (!empty($config['allow_extensions'])) {
					$meta['type'] = array(
						'operator' => '=', 
						'value' => implode(',', $config['allow_extensions']),
					);
				}
			}
			// luu lai vao mang kq
			if ( !empty($result['arr_data'][$attribute]) ) {
				$result['arr_data'][$attribute]['metadata'] = $meta;
			}
		}
		return $result;
	}

    /**
     * Trích xuất các phần tử trong file css
     * @author bangnd <bangnd@24h.com.vn>
     * @param  array $config [cấu hình dùng để trích xuất dữ liệu của phần tử]
     * @param  string $match_type
     * @return HtmlCssProcessor
     */
	private function extractCssElements(array $config, $match_type)
	{
		if (!empty($config['regex'])) {
			if(preg_match_all($config['regex'], $this->content_template, $matches)) {
				// remove duplicated match
				$items = array_unique($matches[1]);
				foreach ($items as $k => $item) {
					$result = array();
					// ex: font, icon
					$result['type'] = $element_type = $config['type'];
					// ex: file, text
					$result['data_type'] = $config['data_type'];
					// ex: icon_1
					$result['code'] = $code = vsprintf($config['code'], $k);
					// ex: {{{font_0}}}
					$result['placeholder'] = $placeholder = $this->wrapper[0] . $code . $this->wrapper[1];
					// du lieu ban dau, tuy nhien dsau nay co the thay doi
					$result['data'] = $item;
					// du lieu ban dau, khong doi
					$result['data_origin'] = $item;

					$this->maps[$match_type][$element_type][] = $result;
					// thay the placehoder vao noi dung => construct template
					$this->content_template = str_replace($item, $placeholder, $this->content_template);
				}
			}
		}
		// function chaining 
		return $this;
	}

    /**
     * Thay thế đường dẫn file trong nội dung gốc
     * @param  array $files [danh sách các file đac được tải lên]
     * @return HtmlCssProcessor
     */
	public function replaceFilePath(array $files)
	{
		if (!empty($this->maps)) {
			foreach ($files as $k => $file) {
				foreach ($this->maps as $mkind => &$map) { // mkind: auto/defined
					if (empty($map)) continue; // bo qua
					foreach ($map as $element_type => &$arr_data) { // element_type: image,js,css,title,...
						if (empty($arr_data)) continue; // bo qua
						
						foreach ($arr_data as $index => &$element_data) {
							if ( $this->content_type == 'html' ) {
								if ( !empty($element_data['arr_data']) ) {
									// html cua phan tu de hien thi trong cot preview trang chi tiet template magazine
									$preview_html = $element_data['html_template'];
									foreach ($element_data['arr_data'] as $attribute => &$data) {
										$this->replaceMapData($file, $data);
										// anh la thuoc tinh background cua 1 phan tu
										if ($data['type'] == 'image' && $data['attr'] == 'style') {
											$preview_html = '<img src="' . $data['data'] . '" alt="">';
										} else {
											$preview_html = str_replace($data['placeholder'], $data['data'], $preview_html);
										}
										// prevent strange behavior of foreach pass-by-reference
										unset($data);
									}
									$element_data['html_preview'] = $preview_html;
								}
							} elseif ( $this->content_type == 'css' ) {
								$this->replaceMapData($file, $element_data);
							}
							unset($element_data);
						}
						unset($arr_data);
					}
					unset($map);
				}
			}
			// build lai content tu template
			$this->rebuildContent();
		}
		// function chaining 
		return $this;
	}

    /**
     * Tái lập lại nội dung tử template và mảng dữ liệu mapping của các phần tử được trích xuất
     * @return void
     */
	private function rebuildContent()
	{
		if (!empty($this->maps)) {
			// dua noi dung ve dang template de rebuild 
			$this->content = $this->content_template;
			foreach ($this->maps as $mkind => $map) { // mkind: auto/defined
				if (empty($map)) continue; // bo qua
				foreach ($map as $element_type => $arr_data) { // element_type: image,js,css,title,...
					if (empty($arr_data)) continue; // bo qua
					
					foreach ($arr_data as $index => $element_data) {
						if ( $this->content_type == 'html' ) {
							if ( !empty($element_data['arr_data']) ) {
								foreach ($element_data['arr_data'] as $attribute => $data) {
									$this->content = str_replace($data['placeholder'], $data['data'], $this->content);
								}
							}
						} elseif ( $this->content_type == 'css' ) {
							$this->content = str_replace($element_data['placeholder'], $element_data['data'], $this->content);
						}
					}
				}
			}
		}
		
	}

    /**
     * Nếu các file được trích xuất có tên trùng với tên các file được tải lên thì thay thế bằng đường dẫn của file tải lên trong dữ liệu map của phần tử
     * @param array $file
     * @param array $data
     * @return $this
     */
	private function replaceMapData($file, &$data)
	{
        if (empty($data['data_type']) 
        	|| $data['data_type'] != 'file' // ko la file
        	|| !empty($data['is_uploaded'])) // hoac da duoc danh dau
        	return $this; // bo qua

	    $v_url = ( empty($data['data']) || is_null($data['data']) ) ? '' : $data['data'];

        // neu la remote url (http://abc.xyz)
        if(parse_url($v_url, PHP_URL_HOST)) {
            $data['is_remote'] = 1; // danh dau file la remote url
        } else { // neu la duong dan tuong doi
            if(basename($v_url) == $file['c_original_name']) { // neu trung ten file thi
                // luu vao json map
                $data['data'] 				= $file['c_url'];
                $data['file_name_origin']   = $file['c_original_name'];
                $data['file_name']          = $file['c_name'];
                $data['fileupload_id']      = $file['pk_magazine_template_fileupload'];
                $data['hash']               = $file['c_hash'];
                $data['is_uploaded']        = 1; // danh dau file da duoc thay the

                // cac duong dan cua image tai len co the thay luon vao noi dung goc
                // de dam bao trai nghiem preview trong editor cua nguoi dung
                if ( $file['c_type'] == 'image' ) {
                	$this->origin_content = str_replace('"' . $v_url . '"', '"' . $file['c_url'] . '"', $this->origin_content);
                }
            }
        }
        // function chaining 
        return $this;
	}
}