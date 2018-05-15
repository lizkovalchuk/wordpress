<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
 wp_enqueue_style( 'twentyseventeen-css',
 get_template_directory_uri() . '/style.css' );
}

add_action('widgets_init', 'twentyseventeenchild_widgets_init');

// implements hook_widget_init()

function twentyseventeenchild_widgets_init(){
    register_sidebar(

// each new registered area goes into an array

        array(
            'name' => 'Content footer',
            'id' => '',
            'before_widget' => '<div>',
            'after-widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after-title' => '</h2>',
        )
    );
    register_widget('RecentWork_Widget');
}

class RecentWork_Widget extends WP_Widget{
    function __construct(){
        parent::__construct(
            'recentwork_widget',
            'Most Recent Work',
            array(
                'description' => __('Display most recent work', 'text_domain')
            )
        );
    }
    // implement widget method.
    public function widget($args, $instance){
        echo $args['before_widget'];
        if(!empty($instance['title'])){
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        $param = array(
            'post_per_page' => 1,
            'offset' => 0,
            'category_name' => 'work',
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'post',
            'post_status' => 'publish'
        );
        $work = get_posts($param);
        foreach ($work as $w){
            setup_postdata($w);
            echo '<h3>' . $w->post_title . '</h3>';
        }
        wp_reset_postdata();
        echo $args['after_widget'];
    }

    // implement form method

    public function form($instance){
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'text_domain');
        echo '<p><label for="' . esc_attr($this->get_field_id('title')) . '">';
        echo esc_attr_e('Title:', 'text_domain') . '</label>';
        echo '<input class="widefat" id="' . esc_attr($this->get_field_id('title')) . ' " name="' . esc_attr(
            $this->get_field_name('title')) . ' "type="text" value="' . esc_attr($title) . '"></p>';
        }

        // implement update method

    public function update($new_instance, $old_instance){
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}