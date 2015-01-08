<?php

class acf_field_repeater2 extends acf_field
{

	var $settings;
	
	
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'repeater2';
		$this->label = __("Repeater Extended",'acf');
		$this->category = __("Layout",'acf');
		
		
		// do not delete!
    	parent::__construct();
    	

    	// settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0'
		);
		
		
	}
	
	
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// register acf scripts
		wp_register_script( 'acf-input-repeater', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version'] );
		wp_register_style( 'acf-input-repeater', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] ); 
		
		
		// scripts
		wp_enqueue_script(array(
			'acf-input-repeater',	
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-repeater',	
		));
		
	}
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add css + javascript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		wp_register_script( 'acf-field-group-repeater', $this->settings['dir'] . 'js/field-group.js', array('acf-field-group'), $this->settings['version']);
		
		// scripts
		wp_enqueue_script(array(
			'acf-field-group-repeater',	
		));
	}
	
	
	/*
	*  load_field()
	*
	*  This filter is appied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/
	
	function load_field( $field )
	{
		// apply_load field to all sub fields
		if( isset($field['sub_fields']) && is_array($field['sub_fields']) )
		{
			foreach( $field['sub_fields'] as $k => $sub_field )
			{
				// apply filters
				$sub_field = apply_filters('acf/load_field_defaults', $sub_field);
				
				// apply filters
				foreach( array('type', 'name', 'key') as $key )
				{
					// run filters
					$sub_field = apply_filters('acf/load_field/' . $key . '=' . $sub_field[ $key ], $sub_field); // new filter
				}
				
				// update sub field
				$field['sub_fields'][ $k ] = $sub_field;
			}
			
			//if(isset($field['sub_field_name']) and $field['sub_field_name'])
				//$field['sub_fields'][] = array( 'key' => $field['key'], 'type' => 'relationship', 'name' => $field['sub_field_name'], '_name' => $field['sub_field_name'], 'hidden' => true );
		}
		
		return $field;
		
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field )
	{
		// vars
		$defaults = array(
			'row_limit'		=>	0,
			'row_min'		=>	0,
			'layout' 		=> 'table',
			'sub_fields'	=>	array(),
			'button_label'	=>	__("Add Row",'acf'),
			'value'			=>	array(),
		);
		
		$field = array_merge($defaults, $field);
		
		
		// validate types
		$field['row_limit'] = (int) $field['row_limit'];
		$field['row_min'] = (int) $field['row_min'];
		
		
		// value may be false
		if( !is_array($field['value']) )
		{
			$field['value'] = array();
		}
		
		
		// row limit = 0?
		if( $field['row_limit'] < 1 )
		{
			$field['row_limit'] = 999;
		}
		
		

		// min rows
		if( $field['row_min'] > count($field['value']) )
		{
			for( $i = 0; $i < $field['row_min']; $i++ )
			{
				// already have a value? continue...
				if( isset($field['value'][$i]) )
				{
					continue;
				}
				
				// populate values
				$field['value'][$i] = array();
				
				foreach( $field['sub_fields'] as $sub_field)
				{
					$sub_value = isset($sub_field['default_value']) ? $sub_field['default_value'] : false;
					$field['value'][$i][ $sub_field['key'] ] = $sub_value;
				}
				
			}
		}

		
		// max rows
		if( $field['row_limit'] < count($field['value']) )
		{
			for( $i = 0; $i < count($field['value']); $i++ )
			{
				if( $i >= $field['row_limit'] )
				{
					unset( $field['value'][$i] );
				}
			}
		}

		
		// setup values for row clone
		$field['value']['acfcloneindex'] = array();
		foreach( $field['sub_fields'] as $sub_field)
		{
			$sub_value = isset($sub_field['default_value']) ? $sub_field['default_value'] : false;
			$field['value']['acfcloneindex'][ $sub_field['key'] ] = $sub_value;
		}

?>
<div class="repeater" data-min_rows="<?php echo $field['row_min']; ?>" data-max_rows="<?php echo $field['row_limit']; ?>">
	<table class="widefat acf-input-table <?php if( $field['layout'] == 'row' ): ?>row_layout<?php endif; ?>">
	<?php if( $field['layout'] == 'table' ): ?>
		<thead>
			<tr>
				<?php 
				
				// order th
				
				if( $field['row_limit'] > 1 ): ?>
					<th class="order"></th>
				<?php endif; ?>
				
				<?php foreach( $field['sub_fields'] as $sub_field): 
					if($sub_field['hidden'])
						continue;
					// add width attr
					$attr = "";
					
					if( count($field['sub_fields']) > 1 && isset($sub_field['column_width']) && $sub_field['column_width'] )
					{
						$attr = 'width="' . $sub_field['column_width'] . '%"';
					}
					
					?>
					<th class="acf-th-<?php echo $sub_field['name']; ?>" <?php echo $attr; ?>>
						<span><?php echo $sub_field['label']; ?></span>
						<?php if( isset($sub_field['instructions']) ): ?>
							<span class="sub-field-instructions"><?php echo $sub_field['instructions']; ?></span>
						<?php endif; ?>
					</th><?php
				endforeach; ?>
							
				<?php
				
				// remove th
							
				if( $field['row_min'] < $field['row_limit'] ):  ?>
					<th class="remove"></th>
				<?php endif; ?>
			</tr>
		</thead>
	<?php endif; ?>
	<tbody>
	<?php if( $field['value'] ): foreach( $field['value'] as $i => $value ): ?>
		
		<tr class="<?php echo ( (string) $i == 'acfcloneindex') ? "row-clone" : "row"; ?>">
		
		<?php 
		
		// row number
		
		if( $field['row_limit'] > 1 ): ?>
			<td class="order"><?php echo $i+1; ?></td>
		<?php endif; ?>
		
		<?php
		
		// layout: Row
		
		if( $field['layout'] == 'row' ): ?>
			<td class="acf_input-wrap">
				<table class="widefat acf_input">
		<?php endif; ?>
		
		
		<?php
		
		// loop though sub fields
		
		foreach( $field['sub_fields'] as $sub_field ): ?>
		
			<?php
			if($sub_field['hidden'])
				continue;
			// layout: Row
			
			if( $field['layout'] == 'row' ): ?>
				<tr>
					<td class="label">
						<label><?php echo $sub_field['label']; ?></label>
						<?php if( isset($sub_field['instructions']) ): ?>
							<span class="sub-field-instructions"><?php echo $sub_field['instructions']; ?></span>
						<?php endif; ?>
					</td>
			<?php endif; ?>
			
			<td class="sub_field field_type-<?php echo $sub_field['type']; ?> field_key-<?php echo $sub_field['key']; ?>" data-field_type="<?php echo $sub_field['type']; ?>" data-field_key="<?php echo $sub_field['key']; ?>" data-field_name="<?php echo $sub_field['name']; ?>">
				<?php
				
				// add value
				$sub_field['value'] = isset($value[$sub_field['key']]) ? $value[$sub_field['key']] : '';
					
				// add name
				$sub_field['name'] = $field['name'] . '[' . $i . '][' . $sub_field['key'] . ']';
				
				// clear ID (needed for sub fields to work!)
				unset( $sub_field['id'] );
				
				// create field
				do_action('acf/create_field', $sub_field);
				
				?>
			</td>
			
			<?php
		
			// layout: Row
			
			if( $field['layout'] == 'row' ): ?>
				</tr>				
			<?php endif; ?>
			
		<?php endforeach; ?>
			
		<?php
		
		// layout: Row
		
		if( $field['layout'] == 'row' ): ?>
				</table>
			</td>
		<?php endif; ?>
		
		<?php 
		
		// delete row
		
		if( $field['row_min'] < $field['row_limit'] ): ?>
			<td class="remove">
				<a class="acf-button-add add-row-before" href="javascript:;"></a>
				<a class="acf-button-remove" href="javascript:;"></a>
			</td>
		<?php endif; ?>
		
		</tr>
	<?php endforeach; endif; ?>
	</tbody>
	</table>
	<?php if( $field['row_min'] < $field['row_limit'] ): ?>

	<ul class="hl clearfix repeater-footer">
		<li class="right">
			<a href="javascript:;" class="add-row-end acf-button"><?php echo $field['button_label']; ?></a>
		</li>
	</ul>

	<?php endif; ?>	
</div>
		<?php
	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
	{
		// vars
		$defaults = array(
			'reciprocal'    =>  0,
			'row_limit'		=>	'',
			'row_min'		=>	0,
			'layout' 		=> 'table',
			'sub_fields'	=>	array(),
			'button_label'	=>	__("Add Row",'acf'),
			'value'			=>	array(),
		);
		
		$field = array_merge($defaults, $field);
		$key = $field['name'];
		
		
		// validate types
		$field['row_min'] = (int) $field['row_min'];
		
		// add clone
		$field['sub_fields'][] = apply_filters('acf/load_field_defaults',  array(
			'key' => 'field_clone',
			'label' => __("New Field",'acf'),
			'name' => __("new_field",'acf'),
			'type' => 'text',
		));
		
		
		// get name of all fields for use in field type drop down
		$fields_names = apply_filters('acf/registered_fields', array());
		//remove repeater and repeater2
		unset( $fields_names[ __("Layout",'acf') ]['repeater'] );
		unset( $fields_names[ __("Layout",'acf') ]['repeater2'] );
		//remove tab
		unset( $fields_names[ __("Layout",'acf') ]['tab'] );
		
		?>
<? /* sub_field name */?>
<tr class="field_option field_option_<?php echo $this->name; ?> field_option_<?php echo $this->name; ?>_fields">
	<td class="label">
		<label><?php _e("Sub Field Name",'acf'); ?></label>
		<p class="description"><?php _e("Name for field in other relation",'acf'); ?></p>
	</td>
	<td> 
		<div class="repeater">
			<? do_action('acf/create_field', array(
				'type'	=>	'text',
				'name'	=>	'fields[' . $key . '][sub_field_name]',
				'value' => $field['sub_field_name'],
			));?>
		</div>
	</td>
</tr>
<? /* end sub_field name */?>
<tr class="field_option field_option_<?php echo $this->name; ?> field_option_<?php echo $this->name; ?>_fields">
	<td class="label">
		<label><?php _e("Repeater Fields",'acf'); ?></label>
	</td>
	<td>
	<div class="repeater">
		<div class="fields_header">
			<table class="acf widefat">
				<thead>
					<tr>
						<th class="field_order"><?php _e('Field Order','acf'); ?></th>
						<th class="field_label"><?php _e('Field Label','acf'); ?></th>
						<th class="field_name"><?php _e('Field Name','acf'); ?></th>
						<th class="field_type"><?php _e('Field Type','acf'); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<div class="fields">

			<div class="no_fields_message" <?php if(count($field['sub_fields']) > 1){ echo 'style="display:none;"'; } ?>>
				<?php _e("No fields. Click the \"+ Add Sub Field button\" to create your first field.",'acf'); ?>
			</div>
	
			<?php foreach($field['sub_fields'] as $sub_field): 
				
				$fake_name =  $key . '][sub_fields][' . $sub_field['key'];
				
				?>
				<div class="field field_type-<?php echo $sub_field['type']; ?> field_key-<?php echo $sub_field['key']; ?> sub_field" data-id="<?php echo $sub_field['key']; ?>">
					<input type="hidden" class="input-field_key" name="fields[<?php echo $fake_name; ?>][key]" value="<?php echo $sub_field['key']; ?>" />
					<div class="field_meta">
					<table class="acf widefat">
						<tr>
							<td class="field_order"><span class="circle"><?php echo (int)$sub_field['order_no'] + 1; ?></span></td>
							<td class="field_label">
								<strong>
									<a class="acf_edit_field" title="<?php _e("Edit this Field",'acf'); ?>" href="javascript:;"><?php echo $sub_field['label']; ?></a>
								</strong>
								<div class="row_options">
									<span><a class="acf_edit_field" title="<?php _e("Edit this Field",'acf'); ?>" href="javascript:;"><?php _e("Edit",'acf'); ?></a> | </span>
									<span><a title="<?php _e("Read documentation for this field",'acf'); ?>" href="http://www.advancedcustomfields.com/docs/field-types/" target="_blank"><?php _e("Docs",'acf'); ?></a> | </span>
									<span><a class="acf_duplicate_field" title="<?php _e("Duplicate this Field",'acf'); ?>" href="javascript:;"><?php _e("Duplicate",'acf'); ?></a> | </span>
									<span><a class="acf_delete_field" title="<?php _e("Delete this Field",'acf'); ?>" href="javascript:;"><?php _e("Delete",'acf'); ?></a>
								</div>
							</td>
							<td class="field_name"><?php echo $sub_field['name']; ?></td>
							<td class="field_type"><?php echo $sub_field['type']; ?></td>
						</tr>
					</table>
					</div>
					
					<div class="field_form_mask">
					<div class="field_form">
						
						<table class="acf_input widefat">
							<tbody>
								<tr class="field_label">
									<td class="label">
										<label><span class="required">*</span><?php _e("Field Label",'acf'); ?></label>
										<p class="description"><?php _e("This is the name which will appear on the EDIT page",'acf'); ?></p>
									</td>
									<td>
										<?php 
										do_action('acf/create_field', array(
											'type'	=>	'text',
											'name'	=>	'fields[' . $fake_name . '][label]',
											'value'	=>	$sub_field['label'],
											'class'	=>	'label',
										));
										?>
									</td>
								</tr>
								<tr class="field_name">
									<td class="label">
										<label><span class="required">*</span><?php _e("Field Name",'acf'); ?></label>
										<p class="description"><?php _e("Single word, no spaces. Underscores and dashes allowed",'acf'); ?></p>
									</td>
									<td>
										<?php 
										do_action('acf/create_field', array(
											'type'	=>	'text',
											'name'	=>	'fields[' . $fake_name . '][name]',
											'value'	=>	$sub_field['name'],
											'class'	=>	'name',
										));
										?>
									</td>
								</tr>
								<tr class="field_type">
									<td class="label"><label><span class="required">*</span><?php _e("Field Type",'acf'); ?></label></td>
									<td>
										<?php 
										do_action('acf/create_field', array(
											'type'	=>	'select',
											'name'	=>	'fields[' . $fake_name . '][type]',
											'value'	=>	$sub_field['type'],
											'class'	=>	'type',
											'choices'	=>	$fields_names,
											'optgroup' 	=> 	true
										));
										?>
									</td>
								</tr>
								<tr class="field_instructions">
									<td class="label"><label><?php _e("Field Instructions",'acf'); ?></label></td>
									<td>
										<?php
										
										if( !isset($sub_field['instructions']) )
										{
											$sub_field['instructions'] = "";
										}
										
										do_action('acf/create_field', array(
											'type'	=>	'text',
											'name'	=>	'fields[' . $fake_name . '][instructions]',
											'value'	=>	$sub_field['instructions'],
											'class'	=>	'instructions',
										));
										?>
									</td>
								</tr>
								<tr class="field_column_width">
									<td class="label">
										<label><?php _e("Column Width",'acf'); ?></label>
										<p class="description"><?php _e("Leave blank for auto",'acf'); ?></p>
									</td>
									<td>
										<?php 
										
										if( !isset($sub_field['column_width']) )
										{
											$sub_field['column_width'] = "";
										}
										
										do_action('acf/create_field', array(
											'type'	=>	'number',
											'name'	=>	'fields[' . $fake_name . '][column_width]',
											'value'	=>	$sub_field['column_width'],
											'class'	=>	'column_width',
										));
										?> %
									</td>
								</tr>
								<?php
								//reciprocal type for relationship
								if($sub_field['type'] == 'relationship'){ ?>
								<tr class="field_option">
									<td class="label">
										<label for=""><?php _e("Reciprocal",'acf'); ?></label>
									</td>
									<td>
										<?php
										$choices = array(
											1 => __('Yes'),
											2 => __('Reversal'),
											0 => __('No')
										);
										
										do_action('acf/create_field', array(
											'type'	=>	'radio',
											'name'	=>	'fields['.$fake_name.'][reciprocal]',
											'value' => 	$sub_field['reciprocal'],
											'layout'	=>	'horizontal',
											'choices'	=>	$choices,
										));
										
										?>
									</td>
								</tr>
								<tr class="field_option">
									<td class="label">
										<label for=""><?php _e("Reversal With",'acf'); ?></label>
										<p class="description"><?php _e("Only work for reversal-reciprocal",'acf'); ?></p>
										<p class="description"><?php _e("Leave blank for use 'Sub Field Name'",'acf'); ?></p>
									</td>
									<td>
										<?php
										do_action('acf/create_field', array(
											'type'	=>	'text',
											'name'	=>	'fields['.$fake_name.'][reversal]',
											'value' => 	$sub_field['reversal']
										));
										
										?>
									</td>
								</tr>
								<?php }
								
								$sub_field['name'] = $fake_name;
								$sub_field['parent_type'] = 'repeater2';
								
								do_action('acf/create_field_options', $sub_field );
								
								//relation modifier for reversal relation
								if($sub_field['type'] == 'select'){
									// implode choices so they work in a textarea
									if( is_array($sub_field['reversal_choices']) )
									{		
										foreach( $sub_field['reversal_choices'] as $k => $v )
										{
											$sub_field['reversal_choices'][ $k ] = $k . ' : ' . $v;
										}
										$sub_field['reversal_choices'] = implode("\n", $sub_field['reversal_choices']);
									}
								?>
								<tr class="field_option">
									<td class="label">
										<label for=""><?php _e("Reciprocal Choices Modifier",'acf'); ?></label>
										<p><?php _e("For more control, you may specify both a value and reversal-reciprocal value like this:",'acf'); ?></p>
										<p><?php _e("red : blue",'acf'); ?><br /><?php _e("blue : red",'acf'); ?></p>
									</td>
									<td>
										<?php
										do_action('acf/create_field', array(
											'type'	=>	'textarea',
											'class' => 	'textarea field_option-choices',
											'name'	=>	'fields['.$fake_name.'][reversal_choices]',
											'value'	=>	$sub_field['reversal_choices'],
										));
										?>
									</td>
								</tr>
								<?php }
								
								?>
								<tr class="field_save">
									<td class="label">
										<!-- <label><?php _e("Save Field",'acf'); ?></label> -->
									</td>
									<td>
										<ul class="hl clearfix">
											<li>
												<a class="acf_edit_field acf-button grey" title="<?php _e("Close Field",'acf'); ?>" href="javascript:;"><?php _e("Close Sub Field",'acf'); ?></a>
											</li>
										</ul>
									</td>
								</tr>								
							</tbody>
						</table>
				
					</div><!-- End Form -->
					</div><!-- End Form Mask -->
				
				</div>
			<?php endforeach; ?>
		</div>
		<div class="table_footer">
			<div class="order_message"><?php _e('Drag and drop to reorder','acf'); ?></div>
			<a href="javascript:;" id="add_field" class="acf-button"><?php _e('+ Add Sub Field','acf'); ?></a>
		</div>
	</div>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Minimum Rows",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][row_min]',
			'value'	=>	$field['row_min'],
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Maximum Rows",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][row_limit]',
			'value'	=>	$field['row_limit'],
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?> field_option_<?php echo $this->name; ?>_layout">
	<td class="label">
		<label><?php _e("Layout",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'radio',
			'name'	=>	'fields['.$key.'][layout]',
			'value'	=>	$field['layout'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'table'	=>	__("Table (default)",'acf'),
				'row'	=>	__("Row",'acf')
			)
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Button Label",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][button_label]',
			'value'	=>	$field['button_label'],
		));
		?>
	</td>
</tr>
		<?php		
	}
	
	
	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the $post_id of which the value will be saved
	*
	*  @return	$value - the modified value
	*/
	
	function update_value( $value, $post_id, $field )
	{
		$total = 0;
		
		$data = array();
		
		//Load Old Data
		$parent_id = get_post($post_id)->post_parent;
		$post_id_ = $parent_id? $parent_id: $post_id;
		$store_post_data = get_field($field['name'], $post_id_);
		
		//save data
		if( $value )
		{
			// remove dummy field
			unset( $value['acfcloneindex'] );
			
			// loop through rows
			foreach( $value as $row_key => $row )
			{
				//save id post
				if(isset($field['sub_field_name']) and $field['sub_field_name']){
					$sub_field = array( 'key' => $field['key'], 'type' => 'text' );
					$sub_field['name'] = $field['name'] . '_' . $row_key . '_' . $field['sub_field_name'];
					$v = isset( $row[$field['key']] ) ? $row[$field['key']] : array($post_id_);
					apply_filters('acf/update_value', $v, $post_id_, $sub_field );
				}
				
				// loop through sub fields
				foreach( $field['sub_fields'] as $sub_field )
				{
					if($sub_field['hidden'])
						continue;
					
					$v = isset( $row[$sub_field['key']] ) ? $row[$sub_field['key']] : false;
					
					// update full name
					$sub_field['name'] = $field['name'] . '_' . $row_key . '_' . $sub_field['name'];
					$sub_field['reciprocal'] = 0;
					
					// save sub field value
					apply_filters('acf/update_value', $v, $post_id, $sub_field );
				}
				
				$data[] = $row_key;
			}
		}//*/
		
		
		//remove old data
		foreach($store_post_data as $store_key => $store_value){
			if(!isset($value[$store_key])){
				foreach( $field['sub_fields'] as $sub_field ){
					apply_filters('acf/delete_value', $post_id, $field['name'] . '_' . $store_key . '_' . $sub_field['name'] );
				}
				if(isset($field['sub_field_name']) and $field['sub_field_name'])
					apply_filters('acf/delete_value', $post_id, $field['name'] . '_' . $store_key . '_' . $field['sub_field_name'] );
			}
		}//*/
		
		/* remove old relation data */
		if($store_post_data and !isset($field['ignore_reciprocal'])){
			$change_data = array();
			//Analyze modified data
			foreach($store_post_data as $store_key => $store_value){
				foreach($field['sub_fields'] as $sub_field){
					if(!$sub_field['reciprocal'])
						continue;
					
					foreach($store_value[$sub_field['name']] as $post){
						if(!isset($value[$store_key]) or !in_array($post->ID, $value[$store_key][$sub_field['key']])){
							if(get_class($post) == 'WP_Post')
								$post_ = $post->ID;
							else
								$post_ = $post;
							$change_data[$post_][$store_key][] = $sub_field['key'];
						}
					}
					
					/*/check sub_field_name
					if($field['sub_field_name'] and $sub_field['reciprocal'] == 1){
						//add all $sub_field['reciprocal'] == 1;
						foreach($field['sub_fields'] as $sub_field_){
							if($sub_field_['reciprocal'] != 2)
								continue;
							foreach($store_value[$field['sub_field_name']] as $post){
								if(!isset($value[$store_key]) or !in_array($post->ID, $value[$store_key][$sub_field['key']])){
									if(get_class($post) == 'WP_Post')
										$post_ = $post->ID;
									else
										$post_ = $post;
									$change_data[$post_][$store_key][] = $field['key'];
									//$change_data[$post_][$store_key][] = $field['key'];
								}
							}
						}
					}//*/
				}
			}
			
			//Remove post meta-data for removed relations
			foreach($change_data as $post_to_change_id => $metadata){
				$store_data = get_field($field['name'], $post_to_change_id);
				$new_data = array();
				$remove_entry = false;
				
				$store_data = get_field($field['name'], $post_to_change_id);
				
				//get old data to save
				$data_to_store = array();
				foreach($store_data as $store_key => $store_value){
					foreach($store_value as $sub_key => $sub_value){
						$sub_key_ = $sub_key;
						if($sub_key == $field['sub_field_name'])
							$sub_key_ = $field['key'];
						else
							foreach($field['sub_fields'] as $sub_field){
								if($sub_field['name'] == $sub_key)
									$sub_key_ = $sub_field['key'];
							}
						
						$new_data_value = array();
						if(!is_array($sub_value)){
							$new_data_value = $sub_value;
						} else {
							foreach($sub_value as $post){
								if(get_class($post) == 'WP_Post')
									$new_data_value[] = $post->ID;
								else
									$new_data_value[] = $post;
							}
						}
						$data_to_store[$store_key][$sub_key_] = $new_data_value;
					}
				}
				
				//remove data from array
				foreach($data_to_store as $store_key => $store_value){
					$remove_entry = false;
					foreach($field['sub_fields'] as $sub_field){
						if($sub_field['hidden'] or !$sub_field['reciprocal'])
							continue;
						$new_data_value = array();
						//for reciprocal
						if($sub_field['reciprocal'] == 1 and isset($metadata[$store_key])){
							if(!in_array($post_id_, $store_value[$field['key']]))
								continue;
							//update info
							foreach($store_value[$field['key']] as $post)
								if($post != $post_id_)
									$new_data_value[] = $post;
							
							$data_to_store[$store_key][$field['key']] = $new_data_value;
							if(!count($data_to_store[$store_key][$field['key']]))
								$remove_entry = true;
							
							/*/check reversal-reciprocal fields
							foreach($field['sub_fields'] as $sub_field_){
								if($sub_field_['reciprocal'] != 2) //only find reversal-reciprocal field
									continue;
								
								var_dump($store_value[$sub_field_['key']])
								foreach($store_value[$sub_field_['key']] as $post)
									if($post != $post_id_)
										$new_data_value[] = $post;
								
								$data_to_store[$store_key][$sub_field_['key']] = $new_data_value;
								if(!count($data_to_store[$store_key][$sub_field_['key']]))
									$remove_entry = true;
							}*/
						}
						//end reciprocal
						//for reversal-reciprocal
						if($t) var_dump($metadata[$store_key]);
						if($sub_field['reciprocal'] == 2 and in_array($sub_field['key'], $metadata[$store_key])){
							if(in_array($post_id_, $store_value[$sub_field['key']])){ //A - Normal reg
								$new_data_value = array();
								//update info
								foreach($store_value[$sub_field['key']] as $post)
									if($post != $post_id_)
										$new_data_value[] = $post;
								
								$data_to_store[$store_key][$sub_field['key']] = $new_data_value;
								if(!count($data_to_store[$store_key][$sub_field['key']]))
									$remove_entry = true;
							}
							/*elseif(in_array($post_id_, $store_value[$field['key']])){ //B - Reversal reg
								$new_data_value = array();
								//update info
								foreach($store_value[$field['key']] as $post)
									if($post != $post_id_)
										$new_data_value[] = $post;
								
								$data_to_store[$store_key][$field['key']] = $new_data_value;
								if(!count($data_to_store[$store_key][$field['key']]))
									$remove_entry = true;
							}*/
							else
								continue;
						}
						//end reversal-reciprocal relation
						// new reversal-reciprocal reciprocal relation
						
						
						if($remove_entry){
							unset($data_to_store[$store_key]);
							break;
						}
					}
				}
				
				$copy_field = $field;
				$copy_field['ignore_reciprocal'] = true;
				apply_filters('acf/update_value', $data_to_store, $post_to_change_id, $copy_field );
			}
		}
		/* End to remove old data */
		
		/* add reciprocal data to other posts */
		if($value and !isset($field['ignore_reciprocal'])){
			$array_to_save_reciprocal = array();
			$array_to_save_reverse_reciprocal = array();
			
			$array_keys_to_remove = array();
			if($field['sub_field_name'])
				$array_keys_to_remove[] = $field['sub_field_name'];
			//Build array to save data
			foreach($field['sub_fields'] as $sub_field){
				if(!$sub_field['reciprocal'])
					continue;
				if($sub_field['reciprocal'] == 1)
					$array_keys_to_remove[] = $sub_field['name'];
				foreach($value as $row_key => $row){
					foreach($row[$sub_field['key']] as $post_to_copy_data){
						if(in_array($post_to_copy_data, array($post_id, $post_id_))) //no override self post
							continue;
						if($sub_field['reciprocal'] == 1)
							$array_to_save_reciprocal[$post_to_copy_data][$row_key] = $row;
						if($sub_field['reciprocal'] == 2)
							$array_to_save_reverse_reciprocal[$post_to_copy_data][$row_key] = $row;
					}
				}
			}
			
			//saving reciprocal post
			foreach($array_to_save_reciprocal as $post_to_save => $added_data){
				$store_data = get_field($field['name'], $post_to_save);
				
				//get old data to save
				$data_to_store = array();
				foreach($store_data as $store_key => $store_value){
					foreach($store_value as $sub_key => $sub_value){
						$sub_key_ = $sub_key;
						if($sub_key == $field['sub_field_name'])
							$sub_key_ = $field['key'];
						else
							//find field key
							foreach($field['sub_fields'] as $sub_field){
								if($sub_key == $sub_field['name']){
									$sub_key_ = $sub_field['key'];
									break;
								}
							}
						
						$new_data_value = array();
						if(!is_array($sub_value)){
							$new_data_value = $sub_value;
						} else {
							foreach($sub_value as $post){
								if(get_class($post) == 'WP_Post')
									$new_data_value[] = $post->ID;
								else
									$new_data_value[] = $post;
							}
						}
						$data_to_store[$store_key][$sub_key_] = $new_data_value;
					}
				}
				
				//append new data to save
				foreach( $added_data as $row_key => $row ){
					foreach($field['sub_fields'] as $sub_field){
						if($sub_field['reciprocal'] == 1 and isset($field['sub_field_name']) and $field['sub_field_name']){
							//this comment-data work assumed only is allowed 1-1 relationships
							//if(!$data_to_store[$row_key][$field['key']] or !is_array($data_to_store[$row_key][$field['key']]))
								$data_to_store[$row_key][$field['key']] = array($post_id_);
							//elseif(!in_array($post_id_, $data_to_store[$row_key][$field['key']]) and !in_array($post_id_,$data_to_store[$row_key][$sub_field['key']]))
								//$data_to_store[$row_key][$field['key']][] = $post_id_;
							//copy modified data
							$data_to_store[$row_key][$sub_field['key']] = $value[$row_key][$sub_field['key']];
						} else {
							//copy modified data
							$data_to_store[$row_key][$sub_field['key']] = $value[$row_key][$sub_field['key']];
						}
					}
					
					//apply modifiers to values
				}
				
				$copy_field = $field;
				$copy_field['ignore_reciprocal'] = true;
				do_action('acf/update_value', $data_to_store, $post_to_save, $copy_field );
			}
			
			//saving reverse-reciprocal post
			foreach($array_to_save_reverse_reciprocal as $post_to_save => $added_data){
				$store_data = get_field($field['name'], $post_to_save);
				//get old data to save
				$data_to_store = array();
				foreach($store_data as $store_key => $store_value){
					foreach($store_value as $sub_key => $sub_value){
						$sub_key_ = $sub_key;
						if($sub_key == $field['sub_field_name'])
							$sub_key_ = $field['key'];
						else
							//find field key
							foreach($field['sub_fields'] as $sub_field){
								if($sub_key == $sub_field['name']){
									$sub_key_ = $sub_field['key'];
									break;
								}
							}
						
						$new_data_value = array();
						if(!is_array($sub_value)){
							$new_data_value = $sub_value;
						} else {
							foreach($sub_value as $post){
								if(get_class($post) == 'WP_Post')
									$new_data_value[] = $post->ID;
								else
									$new_data_value[] = $post;
							}
						}
						$data_to_store[$store_key][$sub_key_] = $new_data_value;
					}
				}
				
				//append new data to save
				foreach( $added_data as $row_key => $row ){
					foreach($field['sub_fields'] as $sub_field){
						if($sub_field['reciprocal'] == 1 and isset($field['sub_field_name']) and $field['sub_field_name']){
							if(!$data_to_store[$row_key][$field['key']] or !is_array($data_to_store[$row_key][$field['key']]))
								$data_to_store[$row_key][$field['key']] = array($post_id_);
							elseif(!in_array($post_id_, $data_to_store[$row_key][$field['key']]))
								$data_to_store[$row_key][$field['key']][] = $post_id_;
							//copy modified data
							$data_to_store[$row_key][$sub_field['key']] = $value[$row_key][$sub_field['key']];
						} elseif($sub_field['reciprocal'] == 2){
							$store_post_id = array();
							//This is for reversal-reciprocal data
							foreach($data_to_store as $store_key => $store_value){
								if($store_key != $row_key)
									continue;
								foreach($store_value[$sub_field['name']] as $post){
									$store_post_id[] = $post->ID;
								}
								break; //because array is found
							}
							if(!in_array($post_id_, $store_post_id))
								$store_post_id[] = $post_id_;
							//apply reverse-reciprocal select modifier
							foreach($field['sub_fields'] as $sub_field_modifier){
								if($sub_field['hidden'])
									continue;
								if($sub_field_modifier['type'] != 'select' or !$sub_field_modifier['reversal_choices'])
									continue;
								if(isset($sub_field_modifier['reversal_choices'][$data_to_store[$row_key][$sub_field_modifier['key']]]) and $sub_field_modifier['reversal_choices'][$data_to_store[$row_key][$sub_field_modifier['key']]])
									$data_to_store[$row_key][$sub_field_modifier['key']] = $sub_field_modifier['reversal_choices'][$data_to_store[$row_key][$sub_field_modifier['key']]];
							}
							//end apply reverse-reciprocal select modifier
							$data_to_store[$row_key][$sub_field['key']] = $store_post_id;
							//apply reverse-reciprocal data modifier
							if($sub_field['reversal']){
								//find reversal_subfield key
								$sub_field_modifier_key = null;
								foreach($field['sub_fields'] as $sub_field_modifier){
									if($sub_field_modifier['name'] == $sub_field['reversal']){
										$sub_field_modifier_key = $sub_field_modifier['key'];
										break;
									}
								}
								if($sub_field_modifier_key){
									//reversal modification
									$temp = $data_to_store[$row_key][$sub_field_modifier_key];
									$data_to_store[$row_key][$sub_field_modifier_key] = $data_to_store[$row_key][$sub_field['key']];
									$data_to_store[$row_key][$sub_field['key']] = $temp;
								}
							}
							//end apply reverse-reciprocal data modifier
							//end reversal-reciprocal data
						} else {
							//copy modified data
							$data_to_store[$row_key][$sub_field['key']] = $value[$row_key][$sub_field['key']];
						}
					}
				}
				
				$copy_field = $field;
				$copy_field['ignore_reciprocal'] = true;
				do_action('acf/update_value', $data_to_store, $post_to_save, $copy_field );
			}
		}
		$value = $data;
		
		// update $value and return to allow for the normal save function to run
		//$value = $total;
		
		return $value;
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is appied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field, $post_id )
	{	
		// format sub_fields
		if( $field['sub_fields'] )
		{
			// remove dummy field
			unset( $field['sub_fields']['field_clone'] );
			
			
			// loop through and save fields
			$i = -1;
			$sub_fields = array();
			
			foreach( $field['sub_fields'] as $key => $f )
			{
				$i++;
				
				
				// order
				$f['order_no'] = $i;
				$f['key'] = $key;
				
				/* modifier for reversal_choices */
				if($f['type'] == 'select'){
					// check if is array. Normal back end edit posts a textarea, but a user might use update_field from the front end
					if( is_array( $f['reversal_choices'] ))
					{
						return $f;
					}
			
					
					// vars
					$new_choices = array();
					
					
					// explode choices from each line
					if( $f['reversal_choices'] )
					{
						// stripslashes ("")
						$f['reversal_choices'] = stripslashes_deep($f['reversal_choices']);
					
						if(strpos($f['choices'], "\n") !== false)
						{
							// found multiple lines, explode it
							$f['reversal_choices'] = explode("\n", $f['reversal_choices']);
						}
						else
						{
							// no multiple lines! 
							$f['reversal_choices'] = array($f['reversal_choices']);
						}
						
						
						// key => value
						foreach($f['reversal_choices'] as $choice)
						{
							if(strpos($choice, ' : ') !== false)
							{
								$choice = explode(' : ', $choice);
								$new_choices[ trim($choice[0]) ] = trim($choice[1]);
							}
							else
							{
								$new_choices[ trim($choice) ] = trim($choice);
							}
						}
					}
					
					
					// update choices
					$f['reversal_choices'] = $new_choices;
				} /* end modifier for reversal_choices */
				
				// save
				$f = apply_filters('acf/update_field/type=' . $f['type'], $f, $post_id ); // new filter
				
				
				// add
				$sub_fields[] = $f;
			}
			
			
			// update sub fields
			$field['sub_fields'] = $sub_fields;
			
		}
		
		
		// return updated repeater field
		return $field;
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value( $value, $post_id, $field )
	{
		// vars
		$values = array();
		
		if( $value > 0 )
		{
			// loop through rows
			//for($i = 0; $i < $value; $i++)
			foreach($value as $row_key)
			{
				// loop through sub fields
				foreach( $field['sub_fields'] as $sub_field )
				{
					// update full name
					$key = $sub_field['key'];
					$sub_field['name'] = $field['name'] . '_' . $row_key . '_' . $sub_field['name'];
					
					$v = apply_filters('acf/load_value', false, $post_id, $sub_field);
					$v = apply_filters('acf/format_value', $v, $post_id, $sub_field);
					
					$values[ $row_key ][ $key ] = $v;
					
				}
				
				//upgrade for reciprocal data
				if(isset($field['sub_field_name']) and $field['sub_field_name']){
					
					$sub_field = array( 'key' => $field['key'], 'type' => 'relationship', 'name' => $field['sub_field_name']);
					$key = $field['sub_field_name'];
					$sub_field['name'] = $field['name'] . '_' . $row_key . '_' . $field['sub_field_name'];
					
					$v = apply_filters('acf/load_value', false, $post_id, $sub_field);
					$v = apply_filters('acf/format_value_for_api', $v, $post_id, $sub_field);
					
					$values[ $row_key ][ $key ] = $v;
					
				}//*/
			}
		}
		
		
		// return
		return $values;
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field )
	{
		// vars
		$values = array();
		
		
		if( $value > 0 )
		{
			// loop through rows
			//for($i = 0; $i < $value; $i++)
			foreach($value as $row_key)
			{
				// loop through sub fields
				foreach( $field['sub_fields'] as $sub_field )
				{
					// update full name
					$key = $sub_field['name'];
					$sub_field['name'] = $field['name'] . '_' . $row_key . '_' . $sub_field['name'];
					
					$v = apply_filters('acf/load_value', false, $post_id, $sub_field);
					$v = apply_filters('acf/format_value_for_api', $v, $post_id, $sub_field);
					
					$values[ $row_key ][ $key ] = $v;
				}
				
				//upgrade for reciprocal data
				if(isset($field['sub_field_name']) and $field['sub_field_name']){
					
					$sub_field = array('type' => 'relationship', 'name' => $field['sub_field_name']);
					$key = $field['sub_field_name'];
					$sub_field['name'] = $field['name'] . '_' . $row_key . '_' . $field['sub_field_name'];
					
					$v = apply_filters('acf/load_value', false, $post_id, $sub_field);
					$v = apply_filters('acf/format_value_for_api', $v, $post_id, $sub_field);
					
					$values[ $row_key ][ $key ] = $v;
					
				}
			}
		}
		
		
		// return
		return $values;
	}
	
}

new acf_field_repeater2();

?>
