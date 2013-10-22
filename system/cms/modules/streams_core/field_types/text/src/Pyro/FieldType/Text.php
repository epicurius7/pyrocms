<?php namespace Pyro\FieldType;

use Pyro\Module\Streams_core\Core\Field\AbstractField;

/**
 * PyroStreams Text Field Type
 *
 * @package		PyroCMS\Core\Modules\Streams Core\Field Types
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Text extends AbstractField
{
	public $field_type_slug			= 'text';

	public $db_col_type				= 'string';

	public $version					= '1.0.0';

	public $author					= array('name'=>'Parse19', 'url'=>'http://parse19.com');

	public $custom_parameters		= array('max_length', 'default_value');

	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function form_output()
	{
		$options['name'] 	= $this->form_slug;
		$options['id']		= $this->form_slug;
		$options['value']	= $this->value;
		$options['autocomplete'] = 'off';

		if ($max_length = $this->getParameter('max_length') and is_numeric($max_length))
		{
			$options['maxlength'] = $max_length;
		}

		return form_input($options);
	}

	// --------------------------------------------------------------------------

	/**
	 * Pre Output
	 *
	 * No PyroCMS tags in text input fields.
	 *
	 * @return string
	 */
	public function pre_output()
	{
		ci()->load->helper('text');
		return escape_tags($this->value);
	}
}