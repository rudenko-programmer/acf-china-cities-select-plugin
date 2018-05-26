<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('rudenko_acf_field_china_cities_select') ) :


class rudenko_acf_field_china_cities_select extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function __construct( $settings ) {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'china_cities_select';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('China cities select', 'china_cities_select');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'choice';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'multiple_select' => false,
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('FIELD_NAME', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'TEXTDOMAIN'),
		);
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		
		
		// do not delete!
    	parent::__construct();
    	
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {
		
		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Multiple Select','TEXTDOMAIN'),
			'instructions'	=> __('','TEXTDOMAIN'),
			'type'			=> 'true_false',
			'name'			=> 'multiple_select',
			'ui'			=> 1,
		));

	}
	
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {
		
		
		/*
		*  Review the data of $field.
		*  This will show what data is available
		*/
		
//		echo '<pre>';
//			print_r( $field );
//		echo '</pre>';
		
		
		/*
		*  Create a simple text input using the 'font_size' setting.
		*/
		
		?>
        <select name="<?php echo esc_attr($field['name']) ?><?php echo $field['multiple_select']?'[]':''; ?>" data-placeholder="Choose a city..." <?php echo $field['multiple_select']?'multiple':''; ?> class="chosen-select">
            <?php foreach ($this->china_cities_data() as $city):
                    $text = sprintf('%s(%s), province: %s', $city['city'], $city['city_ch'], $city['province'] )
            ?>
                <option value="<?php echo esc_attr($city['city']); ?>" <?php $this->selected($field['value'], $city['city'], $field['multiple_select'])?>><?php echo $text; ?></option>
            <?php endforeach; ?>
        </select>
        <?php
	}

	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/


	
	function input_admin_enqueue_scripts() {
		
		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		
//		// register & include JS
		wp_register_script('chosen.jquery.min', "{$url}assets/js/chosen.jquery.min.js", array('acf-input'), $version);
		wp_enqueue_script('chosen.jquery.min');
//		// register & include JS
		wp_register_script('china_cities_select-input', "{$url}assets/js/input.js", array('acf-input'), $version, true);
		wp_enqueue_script('china_cities_select-input');
//
//
//		// register & include CSS
		wp_register_style('chosen.min', "{$url}assets/css/chosen.min.css", array('acf-input'), $version);
		wp_enqueue_style('chosen.min');

	}
	

	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_head() {
	
		
		
	}
	
	*/
	
	
	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and 
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/
   	
   	/*
   	
   	function input_form_data( $args ) {
	   	
		
	
   	}
   	
   	*/
	
	
	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_footer() {
	
		
		
	}
	
	*/
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_enqueue_scripts() {
		
	}
	
	*/

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_head() {
	
	}
	
	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	/*
	
	function load_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	/*
	
	function update_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
		
	/*
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value
		if( empty($value) ) {
		
			return $value;
			
		}
		
		
		// apply setting
		if( $field['font_size'] > 12 ) { 
			
			// format the value
			// $value = 'something';
		
		}
		
		
		// return
		return $value;
	}
	
	*/
	
	
	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
	
	/*
	
	function validate_value( $valid, $value, $field, $input ){
		
		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}
		
		
		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','TEXTDOMAIN'),
		}
		
		
		// return
		return $valid;
		
	}
	
	*/
	
	
	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/
	
	/*
	
	function delete_value( $post_id, $key ) {
		
		
		
	}
	
	*/
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0	
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*

	function load_field( $field ) {

		return $field;

	}

	*/
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*

	function update_field( $field ) {

		return $field;

	}

	*/
	
	
	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/
	
	/*
	
	function delete_field( $field ) {
		
		
		
	}	
	
	*/

	function china_cities_data(){
	    return [
		    [
			    "city"=>"Hong Kong",
			    "city_ch"=>"香港特别行政区",
			    "province"=>"autonomous",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Macau",
			    "city_ch"=>"澳门特别行政区",
			    "province"=>"autonomous",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Beijing",
			    "city_ch"=>"北京市",
			    "province"=>"municipal",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Chongqing",
			    "city_ch"=>"重庆市",
			    "province"=>"municipal",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Shanghai",
			    "city_ch"=>"上海市",
			    "province"=>"municipal",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Tianjin",
			    "city_ch"=>"天津市",
			    "province"=>"municipal",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Anqing",
			    "city_ch"=>"安庆市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Bengbu",
			    "city_ch"=>"蚌埠市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Bozhou",
			    "city_ch"=>"亳州市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chaohu",
			    "city_ch"=>"巢湖市",
			    "province"=>"Anhui",
			    "prefecture"=>"Hefei"
		    ],
		    [
			    "city"=>"Chizhou",
			    "city_ch"=>"池州市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chuzhou",
			    "city_ch"=>"滁州市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Fuyang",
			    "city_ch"=>"阜阳市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Hefei",
			    "city_ch"=>"合肥市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Huaibei",
			    "city_ch"=>"淮北市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Huainan",
			    "city_ch"=>"淮南市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Huangshan",
			    "city_ch"=>"黄山市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jieshou",
			    "city_ch"=>"界首市",
			    "province"=>"Anhui",
			    "prefecture"=>"Fuyang"
		    ],
		    [
			    "city"=>"Lu'an",
			    "city_ch"=>"六安市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Ma'anshan",
			    "city_ch"=>"马鞍山市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Mingguang",
			    "city_ch"=>"明光市",
			    "province"=>"Anhui",
			    "prefecture"=>"Chuzhou"
		    ],
		    [
			    "city"=>"Ningguo",
			    "city_ch"=>"宁国市",
			    "province"=>"Anhui",
			    "prefecture"=>"Xuancheng"
		    ],
		    [
			    "city"=>"Suzhou",
			    "city_ch"=>"宿州市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Tianchang",
			    "city_ch"=>"天长市",
			    "province"=>"Anhui",
			    "prefecture"=>"Chuzhou"
		    ],
		    [
			    "city"=>"Tongcheng",
			    "city_ch"=>"桐城市",
			    "province"=>"Anhui",
			    "prefecture"=>"Anqing"
		    ],
		    [
			    "city"=>"Tongling",
			    "city_ch"=>"铜陵市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wuhu",
			    "city_ch"=>"芜湖市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xuancheng",
			    "city_ch"=>"宣城市",
			    "province"=>"Anhui",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Fu'an",
			    "city_ch"=>"福安市",
			    "province"=>"Fujian",
			    "prefecture"=>"Ningde"
		    ],
		    [
			    "city"=>"Fuding",
			    "city_ch"=>"福鼎市",
			    "province"=>"Fujian",
			    "prefecture"=>"Ningde"
		    ],
		    [
			    "city"=>"Fuqing",
			    "city_ch"=>"福清市",
			    "province"=>"Fujian",
			    "prefecture"=>"Fuzhou"
		    ],
		    [
			    "city"=>"Fuzhou",
			    "city_ch"=>"福州市",
			    "province"=>"Fujian",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jian'ou",
			    "city_ch"=>"建瓯市",
			    "province"=>"Fujian",
			    "prefecture"=>"Nanping"
		    ],
		    [
			    "city"=>"Jinjiang",
			    "city_ch"=>"晋江市",
			    "province"=>"Fujian",
			    "prefecture"=>"Quanzhou"
		    ],
		    [
			    "city"=>"Longhai",
			    "city_ch"=>"龙海市",
			    "province"=>"Fujian",
			    "prefecture"=>"Zhangzhou"
		    ],
		    [
			    "city"=>"Longyan",
			    "city_ch"=>"龙岩市",
			    "province"=>"Fujian",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Nan'an",
			    "city_ch"=>"南安市",
			    "province"=>"Fujian",
			    "prefecture"=>"Quanzhou"
		    ],
		    [
			    "city"=>"Nanping",
			    "city_ch"=>"南平市",
			    "province"=>"Fujian",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Ningde",
			    "city_ch"=>"宁德市",
			    "province"=>"Fujian",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Putian",
			    "city_ch"=>"莆田市",
			    "province"=>"Fujian",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Quanzhou",
			    "city_ch"=>"泉州市",
			    "province"=>"Fujian",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Sanming",
			    "city_ch"=>"三明市",
			    "province"=>"Fujian",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shaowu",
			    "city_ch"=>"邵武市",
			    "province"=>"Fujian",
			    "prefecture"=>"Nanping"
		    ],
		    [
			    "city"=>"Shishi",
			    "city_ch"=>"石狮市",
			    "province"=>"Fujian",
			    "prefecture"=>"Quanzhou"
		    ],
		    [
			    "city"=>"Wuyishan",
			    "city_ch"=>"武夷山市",
			    "province"=>"Fujian",
			    "prefecture"=>"Nanping"
		    ],
		    [
			    "city"=>"Xiamen",
			    "city_ch"=>"厦门市",
			    "province"=>"Fujian",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yong'an",
			    "city_ch"=>"永安市",
			    "province"=>"Fujian",
			    "prefecture"=>"Sanming"
		    ],
		    [
			    "city"=>"Zhangping",
			    "city_ch"=>"漳平市",
			    "province"=>"Fujian",
			    "prefecture"=>"Longyan"
		    ],
		    [
			    "city"=>"Zhangzhou",
			    "city_ch"=>"漳州市",
			    "province"=>"Fujian",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Baiyin",
			    "city_ch"=>"白银市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dingxi",
			    "city_ch"=>"定西市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dunhuang",
			    "city_ch"=>"敦煌市",
			    "province"=>"Gansu",
			    "prefecture"=>"Jiuquan"
		    ],
		    [
			    "city"=>"Hezuo",
			    "city_ch"=>"合作市",
			    "province"=>"Gansu",
			    "prefecture"=>"Gannan"
		    ],
		    [
			    "city"=>"Jiayuguan",
			    "city_ch"=>"嘉峪关市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jinchang",
			    "city_ch"=>"金昌市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jiuquan",
			    "city_ch"=>"酒泉市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Lanzhou",
			    "city_ch"=>"兰州市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Linxia",
			    "city_ch"=>"临夏市",
			    "province"=>"Gansu",
			    "prefecture"=>"Linxia"
		    ],
		    [
			    "city"=>"Longnan",
			    "city_ch"=>"陇南市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Pingliang",
			    "city_ch"=>"平凉市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Qingyang",
			    "city_ch"=>"庆阳市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Tianshui",
			    "city_ch"=>"天水市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wuwei",
			    "city_ch"=>"武威市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yumen",
			    "city_ch"=>"玉门市",
			    "province"=>"Gansu",
			    "prefecture"=>"Jiuquan"
		    ],
		    [
			    "city"=>"Zhangye",
			    "city_ch"=>"张掖市",
			    "province"=>"Gansu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chaozhou",
			    "city_ch"=>"潮州市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dongguan",
			    "city_ch"=>"东莞市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Enping",
			    "city_ch"=>"恩平市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Jiangmen"
		    ],
		    [
			    "city"=>"Foshan",
			    "city_ch"=>"佛山市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Gaozhou",
			    "city_ch"=>"高州市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Maoming"
		    ],
		    [
			    "city"=>"Guangzhou",
			    "city_ch"=>"广州市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Heshan",
			    "city_ch"=>"鹤山市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Jiangmen"
		    ],
		    [
			    "city"=>"Heyuan",
			    "city_ch"=>"河源市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Huazhou",
			    "city_ch"=>"化州市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Maoming"
		    ],
		    [
			    "city"=>"Huizhou",
			    "city_ch"=>"惠州市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jiangmen",
			    "city_ch"=>"江门市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jieyang",
			    "city_ch"=>"揭阳市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Kaiping",
			    "city_ch"=>"开平市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Jiangmen"
		    ],
		    [
			    "city"=>"Lechang",
			    "city_ch"=>"乐昌市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Shaoguan"
		    ],
		    [
			    "city"=>"Leizhou",
			    "city_ch"=>"雷州市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Zhanjiang"
		    ],
		    [
			    "city"=>"Lianjiang",
			    "city_ch"=>"廉江市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Zhanjiang"
		    ],
		    [
			    "city"=>"Lianzhou",
			    "city_ch"=>"连州市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Qingyuan"
		    ],
		    [
			    "city"=>"Lufeng",
			    "city_ch"=>"陆丰市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Shanwei"
		    ],
		    [
			    "city"=>"Luoding",
			    "city_ch"=>"罗定市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Yunfu"
		    ],
		    [
			    "city"=>"Maoming",
			    "city_ch"=>"茂名市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Meizhou",
			    "city_ch"=>"梅州市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Nanxiong",
			    "city_ch"=>"南雄市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Shaoguan"
		    ],
		    [
			    "city"=>"Puning",
			    "city_ch"=>"普宁市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Jieyang"
		    ],
		    [
			    "city"=>"Qingyuan",
			    "city_ch"=>"清远市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shantou",
			    "city_ch"=>"汕头市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shanwei",
			    "city_ch"=>"汕尾市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shaoguan",
			    "city_ch"=>"韶关市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shenzhen",
			    "city_ch"=>"深圳市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Sihui",
			    "city_ch"=>"四会市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Zhaoqing"
		    ],
		    [
			    "city"=>"Taishan",
			    "city_ch"=>"台山市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Jiangmen"
		    ],
		    [
			    "city"=>"Wuchuan",
			    "city_ch"=>"吴川市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Zhanjiang"
		    ],
		    [
			    "city"=>"Xingning",
			    "city_ch"=>"兴宁市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Meizhou"
		    ],
		    [
			    "city"=>"Xinyi",
			    "city_ch"=>"信宜市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Maoming"
		    ],
		    [
			    "city"=>"Yangchun",
			    "city_ch"=>"阳春市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Yangjiang"
		    ],
		    [
			    "city"=>"Yangjiang",
			    "city_ch"=>"阳江市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yingde",
			    "city_ch"=>"英德市",
			    "province"=>"Guangdong",
			    "prefecture"=>"Qingyuan"
		    ],
		    [
			    "city"=>"Yunfu",
			    "city_ch"=>"云浮市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhanjiang",
			    "city_ch"=>"湛江市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhaoqing",
			    "city_ch"=>"肇庆市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhongshan",
			    "city_ch"=>"中山市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhuhai",
			    "city_ch"=>"珠海市",
			    "province"=>"Guangdong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Baise",
			    "city_ch"=>"百色市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Beihai",
			    "city_ch"=>"北海市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Beiliu",
			    "city_ch"=>"北流市",
			    "province"=>"Guangxi",
			    "prefecture"=>"Yulin"
		    ],
		    [
			    "city"=>"Cenxi",
			    "city_ch"=>"岑溪市",
			    "province"=>"Guangxi",
			    "prefecture"=>"Wuzhou"
		    ],
		    [
			    "city"=>"Chongzuo",
			    "city_ch"=>"崇左市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dongxing",
			    "city_ch"=>"东兴市",
			    "province"=>"Guangxi",
			    "prefecture"=>"Fangchenggang"
		    ],
		    [
			    "city"=>"Fangchenggang",
			    "city_ch"=>"防城港市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Guigang",
			    "city_ch"=>"贵港市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Guilin",
			    "city_ch"=>"桂林市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Guiping",
			    "city_ch"=>"桂平市",
			    "province"=>"Guangxi",
			    "prefecture"=>"Guigang"
		    ],
		    [
			    "city"=>"Hechi",
			    "city_ch"=>"河池市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Heshan",
			    "city_ch"=>"合山市",
			    "province"=>"Guangxi",
			    "prefecture"=>"Laibin"
		    ],
		    [
			    "city"=>"Hezhou",
			    "city_ch"=>"贺州市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jingxi",
			    "city_ch"=>"靖西市",
			    "province"=>"Guangxi",
			    "prefecture"=>"Baise"
		    ],
		    [
			    "city"=>"Laibin",
			    "city_ch"=>"来宾市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Liuzhou",
			    "city_ch"=>"柳州市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Nanning",
			    "city_ch"=>"南宁市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Pingxiang",
			    "city_ch"=>"凭祥市",
			    "province"=>"Guangxi",
			    "prefecture"=>"Chongzuo"
		    ],
		    [
			    "city"=>"Qinzhou",
			    "city_ch"=>"钦州市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wuzhou",
			    "city_ch"=>"梧州市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yulin",
			    "city_ch"=>"玉林市",
			    "province"=>"Guangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Anshun",
			    "city_ch"=>"安顺市",
			    "province"=>"Guizhou",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Bijie",
			    "city_ch"=>"毕节市",
			    "province"=>"Guizhou",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chishui",
			    "city_ch"=>"赤水市",
			    "province"=>"Guizhou",
			    "prefecture"=>"Zunyi"
		    ],
		    [
			    "city"=>"Duyun",
			    "city_ch"=>"都匀市",
			    "province"=>"Guizhou",
			    "prefecture"=>"Qiannan"
		    ],
		    [
			    "city"=>"Fuquan",
			    "city_ch"=>"福泉市",
			    "province"=>"Guizhou",
			    "prefecture"=>"Qiannan"
		    ],
		    [
			    "city"=>"Guiyang",
			    "city_ch"=>"贵阳市",
			    "province"=>"Guizhou",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Kaili",
			    "city_ch"=>"凯里市",
			    "province"=>"Guizhou",
			    "prefecture"=>"Qiandongnan"
		    ],
		    [
			    "city"=>"Liupanshui",
			    "city_ch"=>"六盘水市",
			    "province"=>"Guizhou",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Panzhou",
			    "city_ch"=>"盘州市",
			    "province"=>"Guizhou",
			    "prefecture"=>"Liupanshui"
		    ],
		    [
			    "city"=>"Qingzhen",
			    "city_ch"=>"清镇市",
			    "province"=>"Guizhou",
			    "prefecture"=>"Guiyang"
		    ],
		    [
			    "city"=>"Renhuai",
			    "city_ch"=>"仁怀市",
			    "province"=>"Guizhou",
			    "prefecture"=>"Zunyi"
		    ],
		    [
			    "city"=>"Tongren",
			    "city_ch"=>"铜仁市",
			    "province"=>"Guizhou",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xingyi",
			    "city_ch"=>"兴义市",
			    "province"=>"Guizhou",
			    "prefecture"=>"Qianxinan"
		    ],
		    [
			    "city"=>"Zunyi",
			    "city_ch"=>"遵义市",
			    "province"=>"Guizhou",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Danzhou",
			    "city_ch"=>"儋州市",
			    "province"=>"Hainan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dongfang",
			    "city_ch"=>"东方市",
			    "province"=>"Hainan",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Haikou",
			    "city_ch"=>"海口市",
			    "province"=>"Hainan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Qionghai",
			    "city_ch"=>"琼海市",
			    "province"=>"Hainan",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Sansha",
			    "city_ch"=>"三沙市",
			    "province"=>"Hainan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Sanya",
			    "city_ch"=>"三亚市",
			    "province"=>"Hainan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wanning",
			    "city_ch"=>"万宁市",
			    "province"=>"Hainan",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Wenchang",
			    "city_ch"=>"文昌市",
			    "province"=>"Hainan",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Wuzhishan",
			    "city_ch"=>"五指山市",
			    "province"=>"Hainan",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Anguo",
			    "city_ch"=>"安国市",
			    "province"=>"Hebei",
			    "prefecture"=>"Baoding"
		    ],
		    [
			    "city"=>"Baoding",
			    "city_ch"=>"保定市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Bazhou",
			    "city_ch"=>"霸州市",
			    "province"=>"Hebei",
			    "prefecture"=>"Langfang"
		    ],
		    [
			    "city"=>"Botou",
			    "city_ch"=>"泊头市",
			    "province"=>"Hebei",
			    "prefecture"=>"Cangzhou"
		    ],
		    [
			    "city"=>"Cangzhou",
			    "city_ch"=>"沧州市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chengde",
			    "city_ch"=>"承德市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dingzhou",
			    "city_ch"=>"定州市",
			    "province"=>"Hebei",
			    "prefecture"=>"Baoding"
		    ],
		    [
			    "city"=>"Gaobeidian",
			    "city_ch"=>"高碑店市",
			    "province"=>"Hebei",
			    "prefecture"=>"Baoding"
		    ],
		    [
			    "city"=>"Handan",
			    "city_ch"=>"邯郸市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Hengshui",
			    "city_ch"=>"衡水市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Hejian",
			    "city_ch"=>"河间市",
			    "province"=>"Hebei",
			    "prefecture"=>"Cangzhou"
		    ],
		    [
			    "city"=>"Huanghua",
			    "city_ch"=>"黄骅市",
			    "province"=>"Hebei",
			    "prefecture"=>"Cangzhou"
		    ],
		    [
			    "city"=>"Jinzhou",
			    "city_ch"=>"晋州市",
			    "province"=>"Hebei",
			    "prefecture"=>"Shijiazhuang"
		    ],
		    [
			    "city"=>"Langfang",
			    "city_ch"=>"廊坊市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Nangong",
			    "city_ch"=>"南宫市",
			    "province"=>"Hebei",
			    "prefecture"=>"Xingtai"
		    ],
		    [
			    "city"=>"Pingquan",
			    "city_ch"=>"平泉市",
			    "province"=>"Hebei",
			    "prefecture"=>"Chengde"
		    ],
		    [
			    "city"=>"Qian'an",
			    "city_ch"=>"迁安市",
			    "province"=>"Hebei",
			    "prefecture"=>"Tangshan"
		    ],
		    [
			    "city"=>"Qinhuangdao",
			    "city_ch"=>"秦皇岛市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Renqiu",
			    "city_ch"=>"任丘市",
			    "province"=>"Hebei",
			    "prefecture"=>"Cangzhou"
		    ],
		    [
			    "city"=>"Sanhe",
			    "city_ch"=>"三河市",
			    "province"=>"Hebei",
			    "prefecture"=>"Langfang"
		    ],
		    [
			    "city"=>"Shahe",
			    "city_ch"=>"沙河市",
			    "province"=>"Hebei",
			    "prefecture"=>"Xingtai"
		    ],
		    [
			    "city"=>"Shenzhou",
			    "city_ch"=>"深州市",
			    "province"=>"Hebei",
			    "prefecture"=>"Hengshui"
		    ],
		    [
			    "city"=>"Shijiazhuang",
			    "city_ch"=>"石家庄市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Tangshan",
			    "city_ch"=>"唐山市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xinji",
			    "city_ch"=>"辛集市",
			    "province"=>"Hebei",
			    "prefecture"=>"Shijiazhuang"
		    ],
		    [
			    "city"=>"Wu'an",
			    "city_ch"=>"武安市",
			    "province"=>"Hebei",
			    "prefecture"=>"Handan"
		    ],
		    [
			    "city"=>"Xingtai",
			    "city_ch"=>"邢台市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xinle",
			    "city_ch"=>"新乐市",
			    "province"=>"Hebei",
			    "prefecture"=>"Shijiazhuang"
		    ],
		    [
			    "city"=>"Zhangjiakou",
			    "city_ch"=>"张家口市",
			    "province"=>"Hebei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhuozhou",
			    "city_ch"=>"涿州市",
			    "province"=>"Hebei",
			    "prefecture"=>"Baoding"
		    ],
		    [
			    "city"=>"Zunhua",
			    "city_ch"=>"遵化市",
			    "province"=>"Hebei",
			    "prefecture"=>"Tangshan"
		    ],
		    [
			    "city"=>"Anda",
			    "city_ch"=>"安达市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Suihua"
		    ],
		    [
			    "city"=>"Bei'an",
			    "city_ch"=>"北安市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Heihe"
		    ],
		    [
			    "city"=>"Daqing",
			    "city_ch"=>"大庆市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dongning",
			    "city_ch"=>"东宁市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Mudanjiang"
		    ],
		    [
			    "city"=>"Fujin",
			    "city_ch"=>"富锦市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Jiamusi"
		    ],
		    [
			    "city"=>"Fuyuan",
			    "city_ch"=>"抚远市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Jiamusi"
		    ],
		    [
			    "city"=>"Hailin",
			    "city_ch"=>"海林市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Mudanjiang"
		    ],
		    [
			    "city"=>"Hailun",
			    "city_ch"=>"海伦市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Suihua"
		    ],
		    [
			    "city"=>"Harbin",
			    "city_ch"=>"哈尔滨市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Hegang",
			    "city_ch"=>"鹤岗市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Heihe",
			    "city_ch"=>"黑河市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Hulin",
			    "city_ch"=>"虎林市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Jixi"
		    ],
		    [
			    "city"=>"Jiamusi",
			    "city_ch"=>"佳木斯市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jixi",
			    "city_ch"=>"鸡西市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Mishan",
			    "city_ch"=>"密山市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Jixi"
		    ],
		    [
			    "city"=>"Mudanjiang",
			    "city_ch"=>"牡丹江市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Muling",
			    "city_ch"=>"穆棱市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Mudanjiang"
		    ],
		    [
			    "city"=>"Nehe",
			    "city_ch"=>"讷河市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Qiqihar"
		    ],
		    [
			    "city"=>"Ning'an",
			    "city_ch"=>"宁安市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Mudanjiang"
		    ],
		    [
			    "city"=>"Qiqihar",
			    "city_ch"=>"齐齐哈尔市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Qitaihe",
			    "city_ch"=>"七台河市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shangzhi",
			    "city_ch"=>"尚志市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Harbin"
		    ],
		    [
			    "city"=>"Shuangyashan",
			    "city_ch"=>"双鸭山市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Suifenhe",
			    "city_ch"=>"绥芬河市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Mudanjiang"
		    ],
		    [
			    "city"=>"Suihua",
			    "city_ch"=>"绥化市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Tieli",
			    "city_ch"=>"铁力市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Yichun"
		    ],
		    [
			    "city"=>"Tongjiang",
			    "city_ch"=>"同江市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Jiamusi"
		    ],
		    [
			    "city"=>"Wuchang",
			    "city_ch"=>"五常市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Harbin"
		    ],
		    [
			    "city"=>"Wudalianchi",
			    "city_ch"=>"五大连池市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Heihe"
		    ],
		    [
			    "city"=>"Yichun",
			    "city_ch"=>"伊春市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhaodong",
			    "city_ch"=>"肇东市",
			    "province"=>"Heilongjiang",
			    "prefecture"=>"Suihua"
		    ],
		    [
			    "city"=>"Anyang",
			    "city_ch"=>"安阳市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Changge",
			    "city_ch"=>"长葛市",
			    "province"=>"Henan",
			    "prefecture"=>"Xuchang"
		    ],
		    [
			    "city"=>"Dengfeng",
			    "city_ch"=>"登封市",
			    "province"=>"Henan",
			    "prefecture"=>"Zhengzhou"
		    ],
		    [
			    "city"=>"Dengzhou",
			    "city_ch"=>"邓州市",
			    "province"=>"Henan",
			    "prefecture"=>"Nanyang"
		    ],
		    [
			    "city"=>"Gongyi",
			    "city_ch"=>"巩义市",
			    "province"=>"Henan",
			    "prefecture"=>"Zhengzhou"
		    ],
		    [
			    "city"=>"Hebi",
			    "city_ch"=>"鹤壁市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Huixian",
			    "city_ch"=>"辉县市",
			    "province"=>"Henan",
			    "prefecture"=>"Xinxiang"
		    ],
		    [
			    "city"=>"Jiaozuo",
			    "city_ch"=>"焦作市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jiyuan",
			    "city_ch"=>"济源市",
			    "province"=>"Henan",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Kaifeng",
			    "city_ch"=>"开封市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Lingbao",
			    "city_ch"=>"灵宝市",
			    "province"=>"Henan",
			    "prefecture"=>"Sanmenxia"
		    ],
		    [
			    "city"=>"Linzhou",
			    "city_ch"=>"林州市",
			    "province"=>"Henan",
			    "prefecture"=>"Anyang"
		    ],
		    [
			    "city"=>"Luohe",
			    "city_ch"=>"漯河市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Luoyang",
			    "city_ch"=>"洛阳市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Mengzhou",
			    "city_ch"=>"孟州市",
			    "province"=>"Henan",
			    "prefecture"=>"Jiaozuo"
		    ],
		    [
			    "city"=>"Nanyang",
			    "city_ch"=>"南阳市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Pingdingshan",
			    "city_ch"=>"平顶山市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Puyang",
			    "city_ch"=>"濮阳市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Qinyang",
			    "city_ch"=>"沁阳市",
			    "province"=>"Henan",
			    "prefecture"=>"Jiaozuo"
		    ],
		    [
			    "city"=>"Ruzhou",
			    "city_ch"=>"汝州市",
			    "province"=>"Henan",
			    "prefecture"=>"Pingdingshan"
		    ],
		    [
			    "city"=>"Sanmenxia",
			    "city_ch"=>"三门峡市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shangqiu",
			    "city_ch"=>"商丘市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Weihui",
			    "city_ch"=>"卫辉市",
			    "province"=>"Henan",
			    "prefecture"=>"Xinxiang"
		    ],
		    [
			    "city"=>"Wugang",
			    "city_ch"=>"舞钢市",
			    "province"=>"Henan",
			    "prefecture"=>"Pingdingshan"
		    ],
		    [
			    "city"=>"Xiangcheng",
			    "city_ch"=>"项城市",
			    "province"=>"Henan",
			    "prefecture"=>"Zhoukou"
		    ],
		    [
			    "city"=>"Xingyang",
			    "city_ch"=>"荥阳市",
			    "province"=>"Henan",
			    "prefecture"=>"Zhengzhou"
		    ],
		    [
			    "city"=>"Xinmi",
			    "city_ch"=>"新密市",
			    "province"=>"Henan",
			    "prefecture"=>"Zhengzhou"
		    ],
		    [
			    "city"=>"Xinxiang",
			    "city_ch"=>"新乡市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xinyang",
			    "city_ch"=>"信阳市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xinzheng",
			    "city_ch"=>"新郑市",
			    "province"=>"Henan",
			    "prefecture"=>"Zhengzhou"
		    ],
		    [
			    "city"=>"Xuchang",
			    "city_ch"=>"许昌市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yanshi",
			    "city_ch"=>"偃师市",
			    "province"=>"Henan",
			    "prefecture"=>"Luoyang"
		    ],
		    [
			    "city"=>"Yima",
			    "city_ch"=>"义马市",
			    "province"=>"Henan",
			    "prefecture"=>"Sanmenxia"
		    ],
		    [
			    "city"=>"Yongcheng",
			    "city_ch"=>"永城市",
			    "province"=>"Henan",
			    "prefecture"=>"Shangqiu"
		    ],
		    [
			    "city"=>"Yuzhou",
			    "city_ch"=>"禹州市",
			    "province"=>"Henan",
			    "prefecture"=>"Xuchang"
		    ],
		    [
			    "city"=>"Zhengzhou",
			    "city_ch"=>"郑州市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhoukou",
			    "city_ch"=>"周口市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhumadian",
			    "city_ch"=>"驻马店市",
			    "province"=>"Henan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Anlu",
			    "city_ch"=>"安陆市",
			    "province"=>"Hubei",
			    "prefecture"=>"Xiaogan"
		    ],
		    [
			    "city"=>"Chibi",
			    "city_ch"=>"赤壁市",
			    "province"=>"Hubei",
			    "prefecture"=>"Xianning"
		    ],
		    [
			    "city"=>"Dangyang",
			    "city_ch"=>"当阳市",
			    "province"=>"Hubei",
			    "prefecture"=>"Yichang"
		    ],
		    [
			    "city"=>"Danjiangkou",
			    "city_ch"=>"丹江口市",
			    "province"=>"Hubei",
			    "prefecture"=>"Shiyan"
		    ],
		    [
			    "city"=>"Daye",
			    "city_ch"=>"大冶市",
			    "province"=>"Hubei",
			    "prefecture"=>"Huangshi"
		    ],
		    [
			    "city"=>"Enshi",
			    "city_ch"=>"恩施市",
			    "province"=>"Hubei",
			    "prefecture"=>"Enshi"
		    ],
		    [
			    "city"=>"Ezhou",
			    "city_ch"=>"鄂州市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Guangshui",
			    "city_ch"=>"广水市",
			    "province"=>"Hubei",
			    "prefecture"=>"Suizhou"
		    ],
		    [
			    "city"=>"Hanchuan",
			    "city_ch"=>"汉川市",
			    "province"=>"Hubei",
			    "prefecture"=>"Xiaogan"
		    ],
		    [
			    "city"=>"Honghu",
			    "city_ch"=>"洪湖市",
			    "province"=>"Hubei",
			    "prefecture"=>"Jingzhou"
		    ],
		    [
			    "city"=>"Huanggang",
			    "city_ch"=>"黄冈市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Huangshi",
			    "city_ch"=>"黄石市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jingmen",
			    "city_ch"=>"荆门市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jingshan",
			    "city_ch"=>"京山市",
			    "province"=>"Hubei",
			    "prefecture"=>"Jingmen"
		    ],
		    [
			    "city"=>"Jingzhou",
			    "city_ch"=>"荆州市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Laohekou",
			    "city_ch"=>"老河口市",
			    "province"=>"Hubei",
			    "prefecture"=>"Xiangyang"
		    ],
		    [
			    "city"=>"Lichuan",
			    "city_ch"=>"利川市",
			    "province"=>"Hubei",
			    "prefecture"=>"Enshi"
		    ],
		    [
			    "city"=>"Macheng",
			    "city_ch"=>"麻城市",
			    "province"=>"Hubei",
			    "prefecture"=>"Huanggang"
		    ],
		    [
			    "city"=>"Qianjiang",
			    "city_ch"=>"潜江市",
			    "province"=>"Hubei",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Shishou",
			    "city_ch"=>"石首市",
			    "province"=>"Hubei",
			    "prefecture"=>"Jingzhou"
		    ],
		    [
			    "city"=>"Shiyan",
			    "city_ch"=>"十堰市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Suizhou",
			    "city_ch"=>"随州市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Songzi",
			    "city_ch"=>"松滋市",
			    "province"=>"Hubei",
			    "prefecture"=>"Jingzhou"
		    ],
		    [
			    "city"=>"Tianmen",
			    "city_ch"=>"天门市",
			    "province"=>"Hubei",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Wuhan",
			    "city_ch"=>"武汉市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wuxue",
			    "city_ch"=>"武穴市",
			    "province"=>"Hubei",
			    "prefecture"=>"Huanggang"
		    ],
		    [
			    "city"=>"Xiangyang",
			    "city_ch"=>"襄阳市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xianning",
			    "city_ch"=>"咸宁市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xiantao",
			    "city_ch"=>"仙桃市",
			    "province"=>"Hubei",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Xiaogan",
			    "city_ch"=>"孝感市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yichang",
			    "city_ch"=>"宜昌市",
			    "province"=>"Hubei",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yicheng",
			    "city_ch"=>"宜城市",
			    "province"=>"Hubei",
			    "prefecture"=>"Xiangyang"
		    ],
		    [
			    "city"=>"Yidu",
			    "city_ch"=>"宜都市",
			    "province"=>"Hubei",
			    "prefecture"=>"Yichang"
		    ],
		    [
			    "city"=>"Yingcheng",
			    "city_ch"=>"应城市",
			    "province"=>"Hubei",
			    "prefecture"=>"Xiaogan"
		    ],
		    [
			    "city"=>"Zaoyang",
			    "city_ch"=>"枣阳市",
			    "province"=>"Hubei",
			    "prefecture"=>"Xiangyang"
		    ],
		    [
			    "city"=>"Zhijiang",
			    "city_ch"=>"枝江市",
			    "province"=>"Hubei",
			    "prefecture"=>"Yichang"
		    ],
		    [
			    "city"=>"Zhongxiang",
			    "city_ch"=>"钟祥市",
			    "province"=>"Hubei",
			    "prefecture"=>"Jingmen"
		    ],
		    [
			    "city"=>"Changde",
			    "city_ch"=>"常德市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Changning",
			    "city_ch"=>"常宁市",
			    "province"=>"Hunan",
			    "prefecture"=>"Hengyang"
		    ],
		    [
			    "city"=>"Changsha",
			    "city_ch"=>"长沙市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chenzhou",
			    "city_ch"=>"郴州市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Hengyang",
			    "city_ch"=>"衡阳市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Hongjiang",
			    "city_ch"=>"洪江市",
			    "province"=>"Hunan",
			    "prefecture"=>"Huaihua"
		    ],
		    [
			    "city"=>"Huaihua",
			    "city_ch"=>"怀化市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jinshi",
			    "city_ch"=>"津市市",
			    "province"=>"Hunan",
			    "prefecture"=>"Changde"
		    ],
		    [
			    "city"=>"Jishou",
			    "city_ch"=>"吉首市",
			    "province"=>"Hunan",
			    "prefecture"=>"Xiangxi"
		    ],
		    [
			    "city"=>"Leiyang",
			    "city_ch"=>"耒阳市",
			    "province"=>"Hunan",
			    "prefecture"=>"Hengyang"
		    ],
		    [
			    "city"=>"Lengshuijiang",
			    "city_ch"=>"冷水江市",
			    "province"=>"Hunan",
			    "prefecture"=>"Loudi"
		    ],
		    [
			    "city"=>"Lianyuan",
			    "city_ch"=>"涟源市",
			    "province"=>"Hunan",
			    "prefecture"=>"Loudi"
		    ],
		    [
			    "city"=>"Liling",
			    "city_ch"=>"醴陵市",
			    "province"=>"Hunan",
			    "prefecture"=>"Zhuzhou"
		    ],
		    [
			    "city"=>"Linxiang",
			    "city_ch"=>"临湘市",
			    "province"=>"Hunan",
			    "prefecture"=>"Yueyang"
		    ],
		    [
			    "city"=>"Liuyang",
			    "city_ch"=>"浏阳市",
			    "province"=>"Hunan",
			    "prefecture"=>"Changsha"
		    ],
		    [
			    "city"=>"Loudi",
			    "city_ch"=>"娄底市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Miluo",
			    "city_ch"=>"汨罗市",
			    "province"=>"Hunan",
			    "prefecture"=>"Yueyang"
		    ],
		    [
			    "city"=>"Ningxiang",
			    "city_ch"=>"宁乡市",
			    "province"=>"Hunan",
			    "prefecture"=>"Changsha"
		    ],
		    [
			    "city"=>"Shaoshan",
			    "city_ch"=>"韶山市",
			    "province"=>"Hunan",
			    "prefecture"=>"Xiangtan"
		    ],
		    [
			    "city"=>"Shaoyang",
			    "city_ch"=>"邵阳市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wugang",
			    "city_ch"=>"武冈市",
			    "province"=>"Hunan",
			    "prefecture"=>"Shaoyang"
		    ],
		    [
			    "city"=>"Xiangtan",
			    "city_ch"=>"湘潭市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xiangxiang",
			    "city_ch"=>"湘乡市",
			    "province"=>"Hunan",
			    "prefecture"=>"Xiangtan"
		    ],
		    [
			    "city"=>"Yiyang",
			    "city_ch"=>"益阳市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yongzhou",
			    "city_ch"=>"永州市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yuanjiang",
			    "city_ch"=>"沅江市",
			    "province"=>"Hunan",
			    "prefecture"=>"Yiyang"
		    ],
		    [
			    "city"=>"Yueyang",
			    "city_ch"=>"岳阳市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhangjiajie",
			    "city_ch"=>"张家界市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhuzhou",
			    "city_ch"=>"株洲市",
			    "province"=>"Hunan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zixing",
			    "city_ch"=>"资兴市",
			    "province"=>"Hunan",
			    "prefecture"=>"Chenzhou"
		    ],
		    [
			    "city"=>"Arxan",
			    "city_ch"=>"阿尔山市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Hinggan"
		    ],
		    [
			    "city"=>"Baotou",
			    "city_ch"=>"包头市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Bayannur",
			    "city_ch"=>"巴彦淖尔市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chifeng",
			    "city_ch"=>"赤峰市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Erenhot",
			    "city_ch"=>"二连浩特市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Xilingol"
		    ],
		    [
			    "city"=>"Ergun",
			    "city_ch"=>"额尔古纳市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Hulunbuir"
		    ],
		    [
			    "city"=>"Fengzhen",
			    "city_ch"=>"丰镇市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Ulanqab"
		    ],
		    [
			    "city"=>"Genhe",
			    "city_ch"=>"根河市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Hulunbuir"
		    ],
		    [
			    "city"=>"Hohhot",
			    "city_ch"=>"呼和浩特市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Holingol",
			    "city_ch"=>"霍林郭勒市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Tongliao"
		    ],
		    [
			    "city"=>"Hulunbuir",
			    "city_ch"=>"呼伦贝尔市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Manzhouli",
			    "city_ch"=>"满洲里市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Hulunbuir"
		    ],
		    [
			    "city"=>"Ordos",
			    "city_ch"=>"鄂尔多斯市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Tongliao",
			    "city_ch"=>"通辽市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Ulanhot",
			    "city_ch"=>"乌兰浩特市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Hinggan"
		    ],
		    [
			    "city"=>"Ulanqab",
			    "city_ch"=>"乌兰察布市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wuhai",
			    "city_ch"=>"乌海市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xilinhot",
			    "city_ch"=>"锡林浩特市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Xilingol"
		    ],
		    [
			    "city"=>"Yakeshi",
			    "city_ch"=>"牙克石市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Hulunbuir"
		    ],
		    [
			    "city"=>"Zhalantun",
			    "city_ch"=>"扎兰屯市",
			    "province"=>"Inner Mongolia",
			    "prefecture"=>"Hulunbuir"
		    ],
		    [
			    "city"=>"Changshu",
			    "city_ch"=>"常熟市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Suzhou"
		    ],
		    [
			    "city"=>"Changzhou",
			    "city_ch"=>"常州市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Danyang",
			    "city_ch"=>"丹阳市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Zhenjiang"
		    ],
		    [
			    "city"=>"Dongtai",
			    "city_ch"=>"东台市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Yancheng"
		    ],
		    [
			    "city"=>"Gaoyou",
			    "city_ch"=>"高邮市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Yangzhou"
		    ],
		    [
			    "city"=>"Hai'an",
			    "city_ch"=>"海安市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Nantong"
		    ],
		    [
			    "city"=>"Haimen",
			    "city_ch"=>"海门市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Nantong"
		    ],
		    [
			    "city"=>"Huai'an",
			    "city_ch"=>"淮安市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jiangyin",
			    "city_ch"=>"江阴市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Wuxi"
		    ],
		    [
			    "city"=>"Jingjiang",
			    "city_ch"=>"靖江市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Taizhou"
		    ],
		    [
			    "city"=>"Jurong",
			    "city_ch"=>"句容市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Zhenjiang"
		    ],
		    [
			    "city"=>"Liyang",
			    "city_ch"=>"溧阳市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Changzhou"
		    ],
		    [
			    "city"=>"Lianyungang",
			    "city_ch"=>"连云港市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Kunshan",
			    "city_ch"=>"昆山市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Suzhou"
		    ],
		    [
			    "city"=>"Nanjing",
			    "city_ch"=>"南京市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Nantong",
			    "city_ch"=>"南通市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Pizhou",
			    "city_ch"=>"邳州市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Xuzhou"
		    ],
		    [
			    "city"=>"Qidong",
			    "city_ch"=>"启东市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Nantong"
		    ],
		    [
			    "city"=>"Rugao",
			    "city_ch"=>"如皋市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Nantong"
		    ],
		    [
			    "city"=>"Suqian",
			    "city_ch"=>"宿迁市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Suzhou",
			    "city_ch"=>"苏州市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Taicang",
			    "city_ch"=>"太仓市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Suzhou"
		    ],
		    [
			    "city"=>"Taixing",
			    "city_ch"=>"泰兴市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Taizhou"
		    ],
		    [
			    "city"=>"Taizhou",
			    "city_ch"=>"泰州市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wuxi",
			    "city_ch"=>"无锡市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xinghua",
			    "city_ch"=>"兴化市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Taizhou"
		    ],
		    [
			    "city"=>"Xinyi",
			    "city_ch"=>"新沂市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Xuzhou"
		    ],
		    [
			    "city"=>"Xuzhou",
			    "city_ch"=>"徐州市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yancheng",
			    "city_ch"=>"盐城市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yangzhong",
			    "city_ch"=>"扬中市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Zhenjiang"
		    ],
		    [
			    "city"=>"Yangzhou",
			    "city_ch"=>"扬州市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yixing",
			    "city_ch"=>"宜兴市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Wuxi"
		    ],
		    [
			    "city"=>"Yizheng",
			    "city_ch"=>"仪征市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Yangzhou"
		    ],
		    [
			    "city"=>"Zhangjiagang",
			    "city_ch"=>"张家港市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"Suzhou"
		    ],
		    [
			    "city"=>"Zhenjiang",
			    "city_ch"=>"镇江市",
			    "province"=>"Jiangsu",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dexing",
			    "city_ch"=>"德兴市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Shangrao"
		    ],
		    [
			    "city"=>"Fengcheng",
			    "city_ch"=>"丰城市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Yichun"
		    ],
		    [
			    "city"=>"Fuzhou",
			    "city_ch"=>"抚州市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Ganzhou",
			    "city_ch"=>"赣州市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Gao'an",
			    "city_ch"=>"高安市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Yichun"
		    ],
		    [
			    "city"=>"Gongqingcheng",
			    "city_ch"=>"共青城市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Jiujiang"
		    ],
		    [
			    "city"=>"Guixi",
			    "city_ch"=>"贵溪市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Yingtan"
		    ],
		    [
			    "city"=>"Ji'an",
			    "city_ch"=>"吉安市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jingdezhen",
			    "city_ch"=>"景德镇市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jinggangshan",
			    "city_ch"=>"井冈山市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Ji'an"
		    ],
		    [
			    "city"=>"Jiujiang",
			    "city_ch"=>"九江市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Leping",
			    "city_ch"=>"乐平市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Jingdezhen"
		    ],
		    [
			    "city"=>"Lushan",
			    "city_ch"=>"庐山市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Jiujiang"
		    ],
		    [
			    "city"=>"Nanchang",
			    "city_ch"=>"南昌市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Pingxiang",
			    "city_ch"=>"萍乡市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Ruichang",
			    "city_ch"=>"瑞昌市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Jiujiang"
		    ],
		    [
			    "city"=>"Ruijin",
			    "city_ch"=>"瑞金市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Ganzhou"
		    ],
		    [
			    "city"=>"Shangrao",
			    "city_ch"=>"上饶市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xinyu",
			    "city_ch"=>"新余市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yichun",
			    "city_ch"=>"宜春市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yingtan",
			    "city_ch"=>"鹰潭市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhangshu",
			    "city_ch"=>"樟树市",
			    "province"=>"Jiangxi",
			    "prefecture"=>"Yichun"
		    ],
		    [
			    "city"=>"Baicheng",
			    "city_ch"=>"白城市",
			    "province"=>"Jilin",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Baishan",
			    "city_ch"=>"白山市",
			    "province"=>"Jilin",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Changchun",
			    "city_ch"=>"长春市",
			    "province"=>"Jilin",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Da'an",
			    "city_ch"=>"大安市",
			    "province"=>"Jilin",
			    "prefecture"=>"Baicheng"
		    ],
		    [
			    "city"=>"Dehui",
			    "city_ch"=>"德惠市",
			    "province"=>"Jilin",
			    "prefecture"=>"Changchun"
		    ],
		    [
			    "city"=>"Dunhua",
			    "city_ch"=>"敦化市",
			    "province"=>"Jilin",
			    "prefecture"=>"Yanbian"
		    ],
		    [
			    "city"=>"Fuyu",
			    "city_ch"=>"扶余市",
			    "province"=>"Jilin",
			    "prefecture"=>"Songyuan"
		    ],
		    [
			    "city"=>"Gongzhuling",
			    "city_ch"=>"公主岭市",
			    "province"=>"Jilin",
			    "prefecture"=>"Siping"
		    ],
		    [
			    "city"=>"Helong",
			    "city_ch"=>"和龙市",
			    "province"=>"Jilin",
			    "prefecture"=>"Yanbian"
		    ],
		    [
			    "city"=>"Huadian",
			    "city_ch"=>"桦甸市",
			    "province"=>"Jilin",
			    "prefecture"=>"Jilin"
		    ],
		    [
			    "city"=>"Hunchun",
			    "city_ch"=>"珲春市",
			    "province"=>"Jilin",
			    "prefecture"=>"Yanbian"
		    ],
		    [
			    "city"=>"Ji'an",
			    "city_ch"=>"集安市",
			    "province"=>"Jilin",
			    "prefecture"=>"Tonghua"
		    ],
		    [
			    "city"=>"Jiaohe",
			    "city_ch"=>"蛟河市",
			    "province"=>"Jilin",
			    "prefecture"=>"Jilin"
		    ],
		    [
			    "city"=>"Jilin",
			    "city_ch"=>"吉林市",
			    "province"=>"Jilin",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Liaoyuan",
			    "city_ch"=>"辽源市",
			    "province"=>"Jilin",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Linjiang",
			    "city_ch"=>"临江市",
			    "province"=>"Jilin",
			    "prefecture"=>"Baishan"
		    ],
		    [
			    "city"=>"Longjing",
			    "city_ch"=>"龙井市",
			    "province"=>"Jilin",
			    "prefecture"=>"Yanbian"
		    ],
		    [
			    "city"=>"Meihekou",
			    "city_ch"=>"梅河口市",
			    "province"=>"Jilin",
			    "prefecture"=>"Tonghua"
		    ],
		    [
			    "city"=>"Panshi",
			    "city_ch"=>"磐石市",
			    "province"=>"Jilin",
			    "prefecture"=>"Jilin"
		    ],
		    [
			    "city"=>"Shuangliao",
			    "city_ch"=>"双辽市",
			    "province"=>"Jilin",
			    "prefecture"=>"Siping"
		    ],
		    [
			    "city"=>"Shulan",
			    "city_ch"=>"舒兰市",
			    "province"=>"Jilin",
			    "prefecture"=>"Jilin"
		    ],
		    [
			    "city"=>"Siping",
			    "city_ch"=>"四平市",
			    "province"=>"Jilin",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Songyuan",
			    "city_ch"=>"松原市",
			    "province"=>"Jilin",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Taonan",
			    "city_ch"=>"洮南市",
			    "province"=>"Jilin",
			    "prefecture"=>"Baicheng"
		    ],
		    [
			    "city"=>"Tonghua",
			    "city_ch"=>"通化市",
			    "province"=>"Jilin",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Tumen",
			    "city_ch"=>"图们市",
			    "province"=>"Jilin",
			    "prefecture"=>"Yanbian"
		    ],
		    [
			    "city"=>"Yanji",
			    "city_ch"=>"延吉市",
			    "province"=>"Jilin",
			    "prefecture"=>"Yanbian"
		    ],
		    [
			    "city"=>"Yushu",
			    "city_ch"=>"榆树市",
			    "province"=>"Jilin",
			    "prefecture"=>"Changchun"
		    ],
		    [
			    "city"=>"Anshan",
			    "city_ch"=>"鞍山市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Benxi",
			    "city_ch"=>"本溪市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Beipiao",
			    "city_ch"=>"北票市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Chaoyang"
		    ],
		    [
			    "city"=>"Beizhen",
			    "city_ch"=>"北镇市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Jinzhou"
		    ],
		    [
			    "city"=>"Chaoyang",
			    "city_ch"=>"朝阳市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dalian",
			    "city_ch"=>"大连市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dandong",
			    "city_ch"=>"丹东市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dashiqiao",
			    "city_ch"=>"大石桥市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Yingkou"
		    ],
		    [
			    "city"=>"Dengta",
			    "city_ch"=>"灯塔市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Liaoyang"
		    ],
		    [
			    "city"=>"Diaobingshan",
			    "city_ch"=>"调兵山市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Tieling"
		    ],
		    [
			    "city"=>"Donggang",
			    "city_ch"=>"东港市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Dandong"
		    ],
		    [
			    "city"=>"Fengcheng",
			    "city_ch"=>"凤城市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Dandong"
		    ],
		    [
			    "city"=>"Fushun",
			    "city_ch"=>"抚顺市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Fuxin",
			    "city_ch"=>"阜新市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Gaizhou",
			    "city_ch"=>"盖州市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Yingkou"
		    ],
		    [
			    "city"=>"Haicheng",
			    "city_ch"=>"海城市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Anshan"
		    ],
		    [
			    "city"=>"Huludao",
			    "city_ch"=>"葫芦岛市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jinzhou",
			    "city_ch"=>"锦州市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Kaiyuan",
			    "city_ch"=>"开原市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Tieling"
		    ],
		    [
			    "city"=>"Liaoyang",
			    "city_ch"=>"辽阳市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Linghai",
			    "city_ch"=>"凌海市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Jinzhou"
		    ],
		    [
			    "city"=>"Lingyuan",
			    "city_ch"=>"凌源市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Chaoyang"
		    ],
		    [
			    "city"=>"Panjin",
			    "city_ch"=>"盘锦市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shenyang",
			    "city_ch"=>"沈阳市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Tieling",
			    "city_ch"=>"铁岭市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wafangdian",
			    "city_ch"=>"瓦房店市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Dalian"
		    ],
		    [
			    "city"=>"Xingcheng",
			    "city_ch"=>"兴城市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Huludao"
		    ],
		    [
			    "city"=>"Xinmin",
			    "city_ch"=>"新民市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Shenyang"
		    ],
		    [
			    "city"=>"Yingkou",
			    "city_ch"=>"营口市",
			    "province"=>"Liaoning",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhuanghe",
			    "city_ch"=>"庄河市",
			    "province"=>"Liaoning",
			    "prefecture"=>"Dalian"
		    ],
		    [
			    "city"=>"Guyuan",
			    "city_ch"=>"固原市",
			    "province"=>"Ningxia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Lingwu",
			    "city_ch"=>"灵武市",
			    "province"=>"Ningxia",
			    "prefecture"=>"Yinchuan"
		    ],
		    [
			    "city"=>"Qingtongxia",
			    "city_ch"=>"青铜峡市",
			    "province"=>"Ningxia",
			    "prefecture"=>"Wuzhong"
		    ],
		    [
			    "city"=>"Shizuishan",
			    "city_ch"=>"石嘴山市",
			    "province"=>"Ningxia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wuzhong",
			    "city_ch"=>"吴忠市",
			    "province"=>"Ningxia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yinchuan",
			    "city_ch"=>"银川市",
			    "province"=>"Ningxia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhongwei",
			    "city_ch"=>"中卫市",
			    "province"=>"Ningxia",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Delingha",
			    "city_ch"=>"德令哈市",
			    "province"=>"Qinghai",
			    "prefecture"=>"Haixi"
		    ],
		    [
			    "city"=>"Golmud",
			    "city_ch"=>"格尔木市",
			    "province"=>"Qinghai",
			    "prefecture"=>"Haixi"
		    ],
		    [
			    "city"=>"Haidong",
			    "city_ch"=>"海东市",
			    "province"=>"Qinghai",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xining",
			    "city_ch"=>"西宁市",
			    "province"=>"Qinghai",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yushu",
			    "city_ch"=>"玉树市",
			    "province"=>"Qinghai",
			    "prefecture"=>"Yushu"
		    ],
		    [
			    "city"=>"Ankang",
			    "city_ch"=>"安康市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Baoji",
			    "city_ch"=>"宝鸡市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Binzhou",
			    "city_ch"=>"彬州市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"Xianyang"
		    ],
		    [
			    "city"=>"Hancheng",
			    "city_ch"=>"韩城市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"Weinan"
		    ],
		    [
			    "city"=>"Hanzhong",
			    "city_ch"=>"汉中市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Huayin",
			    "city_ch"=>"华阴市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"Weinan"
		    ],
		    [
			    "city"=>"Shangluo",
			    "city_ch"=>"商洛市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shenmu",
			    "city_ch"=>"神木市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"Yulin"
		    ],
		    [
			    "city"=>"Tongchuan",
			    "city_ch"=>"铜川市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Weinan",
			    "city_ch"=>"渭南市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xi'an",
			    "city_ch"=>"西安市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xianyang",
			    "city_ch"=>"咸阳市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xingping",
			    "city_ch"=>"兴平市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"Xianyang"
		    ],
		    [
			    "city"=>"Yan'an",
			    "city_ch"=>"延安市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yulin",
			    "city_ch"=>"榆林市",
			    "province"=>"Shaanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Anqiu",
			    "city_ch"=>"安丘市",
			    "province"=>"Shandong",
			    "prefecture"=>"Weifang"
		    ],
		    [
			    "city"=>"Binzhou",
			    "city_ch"=>"滨州市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Changyi",
			    "city_ch"=>"昌邑市",
			    "province"=>"Shandong",
			    "prefecture"=>"Weifang"
		    ],
		    [
			    "city"=>"Dezhou",
			    "city_ch"=>"德州市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dongying",
			    "city_ch"=>"东营市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Feicheng",
			    "city_ch"=>"肥城市",
			    "province"=>"Shandong",
			    "prefecture"=>"Tai'an"
		    ],
		    [
			    "city"=>"Gaomi",
			    "city_ch"=>"高密市",
			    "province"=>"Shandong",
			    "prefecture"=>"Weifang"
		    ],
		    [
			    "city"=>"Haiyang",
			    "city_ch"=>"海阳市",
			    "province"=>"Shandong",
			    "prefecture"=>"Yantai"
		    ],
		    [
			    "city"=>"Heze",
			    "city_ch"=>"菏泽市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jiaozhou",
			    "city_ch"=>"胶州市",
			    "province"=>"Shandong",
			    "prefecture"=>"Qingdao"
		    ],
		    [
			    "city"=>"Jinan",
			    "city_ch"=>"济南市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jining",
			    "city_ch"=>"济宁市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Laiwu",
			    "city_ch"=>"莱芜市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Laixi",
			    "city_ch"=>"莱西市",
			    "province"=>"Shandong",
			    "prefecture"=>"Qingdao"
		    ],
		    [
			    "city"=>"Laiyang",
			    "city_ch"=>"莱阳市",
			    "province"=>"Shandong",
			    "prefecture"=>"Yantai"
		    ],
		    [
			    "city"=>"Laizhou",
			    "city_ch"=>"莱州市",
			    "province"=>"Shandong",
			    "prefecture"=>"Yantai"
		    ],
		    [
			    "city"=>"Leling",
			    "city_ch"=>"乐陵市",
			    "province"=>"Shandong",
			    "prefecture"=>"Dezhou"
		    ],
		    [
			    "city"=>"Liaocheng",
			    "city_ch"=>"聊城市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Linqing",
			    "city_ch"=>"临清市",
			    "province"=>"Shandong",
			    "prefecture"=>"Liaocheng"
		    ],
		    [
			    "city"=>"Linyi",
			    "city_ch"=>"临沂市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Longkou",
			    "city_ch"=>"龙口市",
			    "province"=>"Shandong",
			    "prefecture"=>"Yantai"
		    ],
		    [
			    "city"=>"Penglai",
			    "city_ch"=>"蓬莱市",
			    "province"=>"Shandong",
			    "prefecture"=>"Yantai"
		    ],
		    [
			    "city"=>"Pingdu",
			    "city_ch"=>"平度市",
			    "province"=>"Shandong",
			    "prefecture"=>"Qingdao"
		    ],
		    [
			    "city"=>"Qingdao",
			    "city_ch"=>"青岛市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Qingzhou",
			    "city_ch"=>"青州市",
			    "province"=>"Shandong",
			    "prefecture"=>"Weifang"
		    ],
		    [
			    "city"=>"Qixia",
			    "city_ch"=>"栖霞市",
			    "province"=>"Shandong",
			    "prefecture"=>"Yantai"
		    ],
		    [
			    "city"=>"Qufu",
			    "city_ch"=>"曲阜市",
			    "province"=>"Shandong",
			    "prefecture"=>"Jining"
		    ],
		    [
			    "city"=>"Rizhao",
			    "city_ch"=>"日照市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Rongcheng",
			    "city_ch"=>"荣成市",
			    "province"=>"Shandong",
			    "prefecture"=>"Weihai"
		    ],
		    [
			    "city"=>"Rushan",
			    "city_ch"=>"乳山市",
			    "province"=>"Shandong",
			    "prefecture"=>"Weihai"
		    ],
		    [
			    "city"=>"Shouguang",
			    "city_ch"=>"寿光市",
			    "province"=>"Shandong",
			    "prefecture"=>"Weifang"
		    ],
		    [
			    "city"=>"Tai'an",
			    "city_ch"=>"泰安市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Tengzhou",
			    "city_ch"=>"滕州市",
			    "province"=>"Shandong",
			    "prefecture"=>"Zaozhuang"
		    ],
		    [
			    "city"=>"Weifang",
			    "city_ch"=>"潍坊市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Weihai",
			    "city_ch"=>"威海市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xintai",
			    "city_ch"=>"新泰市",
			    "province"=>"Shandong",
			    "prefecture"=>"Tai'an"
		    ],
		    [
			    "city"=>"Yantai",
			    "city_ch"=>"烟台市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yucheng",
			    "city_ch"=>"禹城市",
			    "province"=>"Shandong",
			    "prefecture"=>"Dezhou"
		    ],
		    [
			    "city"=>"Zaozhuang",
			    "city_ch"=>"枣庄市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhaoyuan",
			    "city_ch"=>"招远市",
			    "province"=>"Shandong",
			    "prefecture"=>"Yantai"
		    ],
		    [
			    "city"=>"Zhucheng",
			    "city_ch"=>"诸城市",
			    "province"=>"Shandong",
			    "prefecture"=>"Weifang"
		    ],
		    [
			    "city"=>"Zibo",
			    "city_ch"=>"淄博市",
			    "province"=>"Shandong",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zoucheng",
			    "city_ch"=>"邹城市",
			    "province"=>"Shandong",
			    "prefecture"=>"Jining"
		    ],
		    [
			    "city"=>"Changzhi",
			    "city_ch"=>"长治市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Datong",
			    "city_ch"=>"大同市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Fenyang",
			    "city_ch"=>"汾阳市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Lüliang"
		    ],
		    [
			    "city"=>"Gaoping",
			    "city_ch"=>"高平市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Jincheng"
		    ],
		    [
			    "city"=>"Gujiao",
			    "city_ch"=>"古交市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Taiyuan"
		    ],
		    [
			    "city"=>"Hejin",
			    "city_ch"=>"河津市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Yuncheng"
		    ],
		    [
			    "city"=>"Houma",
			    "city_ch"=>"侯马市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Linfen"
		    ],
		    [
			    "city"=>"Huozhou",
			    "city_ch"=>"霍州市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Linfen"
		    ],
		    [
			    "city"=>"Jiexiu",
			    "city_ch"=>"介休市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Jinzhong"
		    ],
		    [
			    "city"=>"Jincheng",
			    "city_ch"=>"晋城市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jinzhong",
			    "city_ch"=>"晋中市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Linfen",
			    "city_ch"=>"临汾市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Lucheng",
			    "city_ch"=>"潞城市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Changzhi"
		    ],
		    [
			    "city"=>"Lüliang",
			    "city_ch"=>"吕梁市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shuozhou",
			    "city_ch"=>"朔州市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Taiyuan",
			    "city_ch"=>"太原市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xiaoyi",
			    "city_ch"=>"孝义市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Lüliang"
		    ],
		    [
			    "city"=>"Xinzhou",
			    "city_ch"=>"忻州市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yangquan",
			    "city_ch"=>"阳泉市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yongji",
			    "city_ch"=>"永济市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Yuncheng"
		    ],
		    [
			    "city"=>"Yuncheng",
			    "city_ch"=>"运城市",
			    "province"=>"Shanxi",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yuanping",
			    "city_ch"=>"原平市",
			    "province"=>"Shanxi",
			    "prefecture"=>"Xinzhou"
		    ],
		    [
			    "city"=>"Barkam",
			    "city_ch"=>"马尔康市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Ngawa"
		    ],
		    [
			    "city"=>"Bazhong",
			    "city_ch"=>"巴中市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chengdu",
			    "city_ch"=>"成都市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chongzhou",
			    "city_ch"=>"崇州市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Chengdu"
		    ],
		    [
			    "city"=>"Dazhou",
			    "city_ch"=>"达州市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Deyang",
			    "city_ch"=>"德阳市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Dujiangyan",
			    "city_ch"=>"都江堰市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Chengdu"
		    ],
		    [
			    "city"=>"Emeishan",
			    "city_ch"=>"峨眉山市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Leshan"
		    ],
		    [
			    "city"=>"Guang'an",
			    "city_ch"=>"广安市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Guanghan",
			    "city_ch"=>"广汉市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Deyang"
		    ],
		    [
			    "city"=>"Guangyuan",
			    "city_ch"=>"广元市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Huaying",
			    "city_ch"=>"华蓥市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Guang'an"
		    ],
		    [
			    "city"=>"Jiangyou",
			    "city_ch"=>"江油市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Mianyang"
		    ],
		    [
			    "city"=>"Jianyang",
			    "city_ch"=>"简阳市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Chengdu"
		    ],
		    [
			    "city"=>"Kangding",
			    "city_ch"=>"康定市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Garzê"
		    ],
		    [
			    "city"=>"Langzhong",
			    "city_ch"=>"阆中市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Nanchong"
		    ],
		    [
			    "city"=>"Leshan",
			    "city_ch"=>"乐山市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Longchang",
			    "city_ch"=>"隆昌市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Neijiang"
		    ],
		    [
			    "city"=>"Luzhou",
			    "city_ch"=>"泸州市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Mianzhu",
			    "city_ch"=>"绵竹市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Deyang"
		    ],
		    [
			    "city"=>"Meishan",
			    "city_ch"=>"眉山市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Mianyang",
			    "city_ch"=>"绵阳市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Nanchong",
			    "city_ch"=>"南充市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Neijiang",
			    "city_ch"=>"内江市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Panzhihua",
			    "city_ch"=>"攀枝花市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Pengzhou",
			    "city_ch"=>"彭州市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Chengdu"
		    ],
		    [
			    "city"=>"Qionglai",
			    "city_ch"=>"邛崃市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Chengdu"
		    ],
		    [
			    "city"=>"Shifang",
			    "city_ch"=>"什邡市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Deyang"
		    ],
		    [
			    "city"=>"Suining",
			    "city_ch"=>"遂宁市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wanyuan",
			    "city_ch"=>"万源市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Dazhou"
		    ],
		    [
			    "city"=>"Xichang",
			    "city_ch"=>"西昌市",
			    "province"=>"Sichuan",
			    "prefecture"=>"Liangshan"
		    ],
		    [
			    "city"=>"Ya'an",
			    "city_ch"=>"雅安市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yibin",
			    "city_ch"=>"宜宾市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zigong",
			    "city_ch"=>"自贡市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Ziyang",
			    "city_ch"=>"资阳市",
			    "province"=>"Sichuan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Lhasa",
			    "city_ch"=>"拉萨市",
			    "province"=>"Tibet",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Nagqu",
			    "city_ch"=>"那曲市",
			    "province"=>"Tibet",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Nyingchi",
			    "city_ch"=>"林芝市",
			    "province"=>"Tibet",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Qamdo",
			    "city_ch"=>"昌都市",
			    "province"=>"Tibet",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shannan",
			    "city_ch"=>"山南市",
			    "province"=>"Tibet",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Xigazê",
			    "city_ch"=>"日喀则市",
			    "province"=>"Tibet",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Aksu",
			    "city_ch"=>"阿克苏市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Aksu"
		    ],
		    [
			    "city"=>"Alashankou",
			    "city_ch"=>"阿拉山口市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Bortala"
		    ],
		    [
			    "city"=>"Altay",
			    "city_ch"=>"阿勒泰市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Altay"
		    ],
		    [
			    "city"=>"Aral",
			    "city_ch"=>"阿拉尔市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Artux",
			    "city_ch"=>"阿图什市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Kizilsu"
		    ],
		    [
			    "city"=>"Beitun",
			    "city_ch"=>"北屯市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Bole",
			    "city_ch"=>"博乐市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Bortala"
		    ],
		    [
			    "city"=>"Changji",
			    "city_ch"=>"昌吉市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Changji"
		    ],
		    [
			    "city"=>"Fukang",
			    "city_ch"=>"阜康市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Changji"
		    ],
		    [
			    "city"=>"Hami",
			    "city_ch"=>"哈密市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Hotan",
			    "city_ch"=>"和田市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Hotan"
		    ],
		    [
			    "city"=>"Karamay",
			    "city_ch"=>"克拉玛依市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Kashgar",
			    "city_ch"=>"喀什市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Kashgar"
		    ],
		    [
			    "city"=>"Khorgas",
			    "city_ch"=>"霍尔果斯市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Ili"
		    ],
		    [
			    "city"=>"Kokdala",
			    "city_ch"=>"可克达拉市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Korla",
			    "city_ch"=>"库尔勒市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Bayingolin"
		    ],
		    [
			    "city"=>"Kuytun",
			    "city_ch"=>"奎屯市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Ili"
		    ],
		    [
			    "city"=>"Kunyu",
			    "city_ch"=>"昆玉市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Shihezi",
			    "city_ch"=>"石河子市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Shuanghe",
			    "city_ch"=>"双河市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Tacheng",
			    "city_ch"=>"塔城市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Tacheng"
		    ],
		    [
			    "city"=>"Tiemenguan",
			    "city_ch"=>"铁门关市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Tumxuk",
			    "city_ch"=>"图木舒克市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Turpan",
			    "city_ch"=>"吐鲁番市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Ürümqi",
			    "city_ch"=>"乌鲁木齐市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Wujiaqu",
			    "city_ch"=>"五家渠市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"none"
		    ],
		    [
			    "city"=>"Wusu",
			    "city_ch"=>"乌苏市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Tacheng"
		    ],
		    [
			    "city"=>"Yining",
			    "city_ch"=>"伊宁市",
			    "province"=>"Xinjiang",
			    "prefecture"=>"Ili"
		    ],
		    [
			    "city"=>"Anning",
			    "city_ch"=>"安宁市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Kunming"
		    ],
		    [
			    "city"=>"Baoshan",
			    "city_ch"=>"保山市",
			    "province"=>"Yunnan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Chuxiong",
			    "city_ch"=>"楚雄市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Chuxiong"
		    ],
		    [
			    "city"=>"Dali",
			    "city_ch"=>"大理市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Dali"
		    ],
		    [
			    "city"=>"Gejiu",
			    "city_ch"=>"个旧市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Honghe"
		    ],
		    [
			    "city"=>"Jinghong",
			    "city_ch"=>"景洪市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Xishuangbanna"
		    ],
		    [
			    "city"=>"Kaiyuan",
			    "city_ch"=>"开远市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Honghe"
		    ],
		    [
			    "city"=>"Kunming",
			    "city_ch"=>"昆明市",
			    "province"=>"Yunnan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Lincang",
			    "city_ch"=>"临沧市",
			    "province"=>"Yunnan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Lijiang",
			    "city_ch"=>"丽江市",
			    "province"=>"Yunnan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Lushui",
			    "city_ch"=>"泸水市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Nujiang"
		    ],
		    [
			    "city"=>"Mang",
			    "city_ch"=>"芒市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Dehong"
		    ],
		    [
			    "city"=>"Mengzi",
			    "city_ch"=>"蒙自市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Honghe"
		    ],
		    [
			    "city"=>"Mile",
			    "city_ch"=>"弥勒市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Honghe"
		    ],
		    [
			    "city"=>"Pu'er",
			    "city_ch"=>"普洱市",
			    "province"=>"Yunnan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Qujing",
			    "city_ch"=>"曲靖市",
			    "province"=>"Yunnan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Ruili",
			    "city_ch"=>"瑞丽市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Dehong"
		    ],
		    [
			    "city"=>"Shangri-La",
			    "city_ch"=>"香格里拉市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Dêqên"
		    ],
		    [
			    "city"=>"Tengchong",
			    "city_ch"=>"腾冲市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Baoshan"
		    ],
		    [
			    "city"=>"Wenshan",
			    "city_ch"=>"文山市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Wenshan"
		    ],
		    [
			    "city"=>"Xuanwei",
			    "city_ch"=>"宣威市",
			    "province"=>"Yunnan",
			    "prefecture"=>"Qujing"
		    ],
		    [
			    "city"=>"Yuxi",
			    "city_ch"=>"玉溪市",
			    "province"=>"Yunnan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhaotong",
			    "city_ch"=>"昭通市",
			    "province"=>"Yunnan",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Cixi",
			    "city_ch"=>"慈溪市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Ningbo"
		    ],
		    [
			    "city"=>"Dongyang",
			    "city_ch"=>"东阳市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Jinhua"
		    ],
		    [
			    "city"=>"Haining",
			    "city_ch"=>"海宁市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Jiaxing"
		    ],
		    [
			    "city"=>"Hangzhou",
			    "city_ch"=>"杭州市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Huzhou",
			    "city_ch"=>"湖州市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jiande",
			    "city_ch"=>"建德市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Hangzhou"
		    ],
		    [
			    "city"=>"Jiangshan",
			    "city_ch"=>"江山市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Quzhou"
		    ],
		    [
			    "city"=>"Jiaxing",
			    "city_ch"=>"嘉兴市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Jinhua",
			    "city_ch"=>"金华市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Lanxi",
			    "city_ch"=>"兰溪市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Jinhua"
		    ],
		    [
			    "city"=>"Linhai",
			    "city_ch"=>"临海市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Taizhou"
		    ],
		    [
			    "city"=>"Lishui",
			    "city_ch"=>"丽水市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Longquan",
			    "city_ch"=>"龙泉市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Lishui"
		    ],
		    [
			    "city"=>"Ningbo",
			    "city_ch"=>"宁波市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Pinghu",
			    "city_ch"=>"平湖市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Jiaxing"
		    ],
		    [
			    "city"=>"Quzhou",
			    "city_ch"=>"衢州市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Ruian",
			    "city_ch"=>"瑞安市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Wenzhou"
		    ],
		    [
			    "city"=>"Shaoxing",
			    "city_ch"=>"绍兴市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Shengzhou",
			    "city_ch"=>"嵊州市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Shaoxing"
		    ],
		    [
			    "city"=>"Taizhou",
			    "city_ch"=>"台州市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Tongxiang",
			    "city_ch"=>"桐乡市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Jiaxing"
		    ],
		    [
			    "city"=>"Wenling",
			    "city_ch"=>"温岭市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Taizhou"
		    ],
		    [
			    "city"=>"Wenzhou",
			    "city_ch"=>"温州市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Yiwu",
			    "city_ch"=>"义乌市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Jinhua"
		    ],
		    [
			    "city"=>"Yongkang",
			    "city_ch"=>"永康市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Jinhua"
		    ],
		    [
			    "city"=>"Yueqing",
			    "city_ch"=>"乐清市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Wenzhou"
		    ],
		    [
			    "city"=>"Yuhuan",
			    "city_ch"=>"玉环市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Taizhou"
		    ],
		    [
			    "city"=>"Yuyao",
			    "city_ch"=>"余姚市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Ningbo"
		    ],
		    [
			    "city"=>"Zhoushan",
			    "city_ch"=>"舟山市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"direct administration"
		    ],
		    [
			    "city"=>"Zhuji",
			    "city_ch"=>"诸暨市",
			    "province"=>"Zhejiang",
			    "prefecture"=>"Shaoxing"
		    ],
	    ];
    }
	
	function selected($selected, $current, $multiple = false, $echo = true ){
	    if($multiple && is_array($selected)){
	        if(in_array($current, $selected)){
		        if(!$echo){
		            return 'selected';
		        }
		        echo 'selected';
            }
        }else {
		    if ( !$echo ) {
		        return selected( $selected, $current, FALSE );
		    }
		    selected( $selected, $current );
	    }
	    return '';
    }
}


// initialize
new rudenko_acf_field_china_cities_select( $this->settings );


// class_exists check
endif;

?>