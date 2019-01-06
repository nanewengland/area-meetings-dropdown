<?php
/*
Plugin Name: Area Meetings Dropdown
Author: Patrick J NERNA
Description: Creates dropdown list of areas. Simply add [meetingsbyarea] shortcode to your page or use widget.
Version: 1.0
Install: Drop this directory into the "wp-content/plugins/" directory and activate it.
*/

/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}

function meetingsbyarea_func( $atts ){
    echo '<div class="meetingsbyarea" id="meetingsbyarea-dropdown">';
    static $first_dropdown = true;

    $title = 'Area Meetings Dropdown' ;

    $cat_args = array(
    'orderby'      => 'name',
    'show_option_none' => __( 'Select Meetings By Area' ),
    'child_of' => '13',
    );

    echo sprintf( '<form action="%s" method="get">', esc_url( home_url() ) );
    $dropdown_id = ( $first_dropdown ) ? 'cat' : "{$this->id_base}-dropdown-{$this->number}";
    $first_dropdown = false;

    echo '<label class="screen-reader-text" for="' . esc_attr( $dropdown_id ) . '">' . $title . '</label>';

    $cat_args['id'] = $dropdown_id;


    wp_dropdown_pages( apply_filters( 'widget_pages_dropdown_args', $cat_args, $instance ) );

    echo '</form>';
    echo '</div>';
    ?>

    <script type='text/javascript'>
        /* <![CDATA[ */
        (function() {
            var dropdown = document.getElementById( "<?php echo esc_js( $dropdown_id ); ?>" );
            function onCatChange() {
                if ( dropdown.options[ dropdown.selectedIndex ].value > 0 ) {
                    dropdown.parentNode.submit();
                }
            }
            dropdown.onchange = onCatChange;
        })();
        /* ]]> */
    </script>

    <?php

}


// create [meetingsbyarea] shortcode
add_shortcode( 'meetingsbyarea', 'meetingsbyarea_func' );

// Register and load the widget
function amd_load_widget() {
    register_widget( 'amd_widget' );
}
add_action( 'widgets_init', 'amd_load_widget' );

// Creating the widget 
class amd_widget extends WP_Widget {

	/**
	 * Sets up a new Area Meetings Dropdown widget instance.
	 *
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'amd_widget',
			'description' => __( 'Widget to display dropdown list of area meetings.', 'amd_widget_domain' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'amd_widget', __( 'Area Meetings Dropdown', 'amd_widget_domain' ), $widget_ops );
	}

	/**
	 * Outputs the content for the current Area Meetings Dropdown widget instance.
	 *
	 *
	 * @staticvar bool $first_dropdown
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Area Meetings Dropdown widget instance.
	 */
	public function widget( $args, $instance ) {
		static $first_dropdown = true;

		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Area Meetings Dropdown' );

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget'];

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		$cat_args = array(
			'orderby'      => 'name',
			'show_option_none' => __( 'Select Area' ),
			'child_of' => '13',
		);   // this creates a dropdown of all pages that are a child of page id 13, this can be changed to any page id
		
			echo sprintf( '<form action="%s" method="get">', esc_url( home_url() ) );
			$dropdown_id = ( $first_dropdown ) ? 'cat' : "{$this->id_base}-dropdown-{$this->number}";
			$first_dropdown = false;

			echo '<label class="screen-reader-text" for="' . esc_attr( $dropdown_id ) . '">' . $title . '</label>';

			$cat_args['id'] = $dropdown_id;
			/**
			 * Filters the arguments for the Area Meetings Dropdown widget drop-down.
			 *
			 * @see wp_dropdown_pages()
			 *
			 * @param array $cat_args An array of Area Meetings Dropdown widget drop-down arguments.
			 * @param array $instance Array of settings for the current widget.
			 */
			wp_dropdown_pages( apply_filters( 'widget_pages_dropdown_args', $cat_args, $instance ) );

			echo '</form>';
			?>

<script type='text/javascript'>
/* <![CDATA[ */
(function() {
	var dropdown = document.getElementById( "<?php echo esc_js( $dropdown_id ); ?>" );
	function onCatChange() {
		if ( dropdown.options[ dropdown.selectedIndex ].value > 0 ) {
			dropdown.parentNode.submit();
		}
	}
	dropdown.onchange = onCatChange;
})();
/* ]]> */
</script>

<?php

		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Area Meetings Dropdown widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Outputs the settings form for the Area Meetings Dropdown widget.
	 *
	 */
	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = sanitize_text_field( $instance['title'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /><br /></p>
		<?php
	}

}

/** END Area Meetings Dropdown List Widget **/


?>