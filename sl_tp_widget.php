<?php
/*

	Plugin Name: SL TP Widget
	Plugin URI: http://gamedefender.com/wp/index.php/tag/sl_tp_widget/
	Description: Second Life TP Widget
	Author: Alexander Sapountzis
	Version: 1.2
	Author URI: http://www.gamedefender.com/

	Changelog
	
	0.1: Creates hyperlink on sidebar with slurl
	1.1: Add 2.8 compatibility
	1.2: Add idiot checks

	To Do:
	* Validate Slurl fields
	
	
	Future Revisions:
		- Themse/Templates
		- Image Upload
		- track clickthroughs
	
*/




class SLTPWidget extends WP_Widget{
	
	/** constructor **/
	function SLTPWidget(){
		$widget_ops = array(
			'classname' => 'widget_sl_tp',
			'description' => 'Adds a slurl to your sidebar'
			);
		$this->WP_Widget('sl_tp_widget', 'SecondLife TP Widget', $widget_ops);
	}
	
	/** @see WP_Widget::widget */
	function widget($args, $instance){
		extract($args, EXTR_SKIP);
	
		$title = $this->get_title($instance);
		$label = $this->get_label($instance);
		$popup = $this->get_popup($instance);	
		$slurl = $this->get_slurl($instance);
						
		if( !empty($slurl) ) {
			
			echo $before_widget;
			
			echo $before_title . $title . $after_title;
				
			echo '<ul id = "slurl">';
			echo " <li><a href='$slurl' $popup >$label</a></li>";
			echo '</ul>';

			echo $after_widget;
		}
		
	}
	
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['location'] = strip_tags($new_instance['location']);
		$instance['x'] = strip_tags($new_instance['x']);
		$instance['y'] = strip_tags($new_instance['y']);
		$instance['z'] = strip_tags($new_instance['z']);
		$instance['label'] = strip_tags($new_instance['label']);
		$instance['new_window'] = strip_tags($new_instance['new_window']);
		
		
		return $instance;
	}
	
	function form($instance){
		$instance = wp_parse_args(
				(array) $instance, array (
					'title' => '',
					'location' => '',
					'x' => '',
					'y' => '',
					'z' => '',
					'label' => '',
					'new_window' => ''
					)
			);
			
			$title = strip_tags($instance['title']);
			$location = strip_tags($instance['location']);
			$x = strip_tags($instance['x']);
			$y = strip_tags($instance['y']);
			$z = strip_tags($instance['z']);
			$label = strip_tags($instance['label']);
			$new_window = strip_tags($instance['new_window']);
			$slurl = $this->get_slurl($instance);

		?>
			<p>
			 <label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
			 <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo attribute_escape($title); ?>" /><hr />
			 <label for="<?php echo $this->get_field_id('location'); ?>">Location: </label><br />
			 <input class="widefat" type="text" id="<?php echo $this->get_field_id('location'); ?>" name="<?php echo $this->get_field_name('location'); ?>" value="<?php echo attribute_escape($location); ?>" /><br />
			 <input type="text" maxlength="3" size="3" id="<?php echo $this->get_field_id('x'); ?>" name="<?php echo $this->get_field_name('x'); ?>" value="<?php echo attribute_escape($x); ?>" />
			 <input type="text" maxlength="3" size="3" id="<?php echo $this->get_field_id('y'); ?>" name="<?php echo $this->get_field_name('y'); ?>" value="<?php echo attribute_escape($y); ?>" />
			 <input type="text" maxlength="3" size="3" id="<?php echo $this->get_field_id('z'); ?>" name="<?php echo $this->get_field_name('z'); ?>" value="<?php echo attribute_escape($z); ?>" />
			 <hr />
			 <label for="<?php echo $this->get_field_id('label'); ?>">Label: </label><br />
			 <input class="widefat" type="text" id="<?php echo $this->get_field_id('label'); ?>" name="<?php echo $this->get_field_name('label'); ?>" value="<?php echo attribute_escape($label); ?>" /><br />
			</p>
			<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('new_window'); ?>" name="<?php echo $this->get_field_name('new_window'); ?>" <? if(attribute_escape($new_window)) echo "checked"; ?> />
			<label for="<?php echo $this->get_field_id('new_window'); ?>">Open in New Window?</label>
			</p>
			<hr />
			<p>
			 <?php
			 	if(!empty($slurl)) {	?>
			<? if( empty($label) ) $label = "Click here to go to Second Life"; ?>
					<label>Preview Link (opens in new window)</label><br />
					<a href="<?php echo attribute_escape($slurl); ?>"><?php echo attribute_escape($label); ?></a>
			<? } else { ?>
					<label style="color:red;">Some fields are missing/invalid:</label>
					<ul style="color:red;">
					<?php
					
					 if(empty($location) ) echo "<li>Location</li>" ; 
					 if(empty($x) ) echo "<li>X coordinate</li>" ; 
					 if(empty($y) ) echo "<li>y coordinate</li>" ; 
					 if(empty($z) ) echo "<li>z coordinate</li>" ; 	
					
					
					?>
					</ul>
					<label style="color:red;">Widget will not appear until this is fixed</label>
			<? } ?>
			</p>
		<?php
	}

	function get_slurl($instance){
		$location = empty($instance['location']) ? false : $instance['location'];
		$x = empty($instance['x']) ? false : $instance['x'];
		$y = empty($instance['y']) ? false : $instance['y'];
		$z = empty($instance['z']) ? false : $instance['z'];
		
		if(!$location || !$x || !$y || !$z)
			return false;
		else
			return "http://slurl.com/secondlife/$location/$x/$y/$z";
	}
	
	function get_popup($instance){
		$popup = empty($instance['new_window']) ? false : "target='_blank'";
		
		return $popup;
	}

	function get_title($instance){
		$title = empty($instance['title'])	? "Second Life Location" : $instance['title'];
		return $title;
	}

	function get_label($instance){
		$label = empty($instance['label'])	? "Click here for my slurl" : $instance['label'];
		return $label;
	}
	
} // class SLTPWidget


function SLTPWidgetInit() {
	register_widget('SLTPWidget');
}

add_action('widgets_init', 'SLTPWidgetInit');


?>