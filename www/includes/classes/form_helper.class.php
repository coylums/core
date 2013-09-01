<?php

	class form_helper
	{
	
		static function form_hidden()
		{
			
			
			
		}

		static function form_input()
		{
			
			
			
		}
		
		static function form_password()
		{
			
			
			
		}
		
		static function form_textarea()
		{
			
			
			
		}
		
		static function form_dropdown($_name, $_options = array(), $_selected = '', $_class = '', $_default = '')
		{
		
			$id = (strpos($_name, '[]') === false ) ?  'id="' . $_name . '"' : ''; 
		
			echo '<select ' . $id . ' name="' . $_name . '" class="' . $_class . '">';
			
			echo '<option value="' . $_default . '">' . $_default . '</option>';
			
			foreach($_options as $_value => $_option)
			{
			
				if($_selected == $_value)
				{
					
					echo '<option value="' . $_value . '" selected="selected">' . $_option . '</option>';
					
				}
				else
				{
					
					echo '<option value="' . $_value . '">' . $_option . '</option>';
					
				}
			
			}
			
			echo '</select>';
			
		}
		
		static function form_multiselect()
		{
			
			
			
		}
		
		static function form_checkbox()
		{
			
			
			
		}
		
		static function form_radio()
		{
			
			
			
		}
		
		static function form_label()
		{
			
			
			
		}
		
		static function set_select()
		{
			
			
			
		}
		
		static function set_checkbox($_bool)
		{
			
			echo ($_bool) ? 'checked="checked"' : '';
			
		}

		static function set_radio()
		{
			
			
			
		}

	}

?>