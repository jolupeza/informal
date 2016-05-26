<?php
/***********************************************************************************************/
/* Widget that displays calendar with events */
/***********************************************************************************************/

class Informal_Events_Calendar_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'informal_events_calendar_w',
            'Custom Widget: Calendario de Eventos',
            array('description' => __('Mostrar Calendario de Eventos', THEMEDOMAIN))
        );
    }

    public function form($instance) {
            $defaults = array(
                'title' => '',
                'category' => '',
                'orientation' => ''
            );

            $instance = wp_parse_args((array) $instance, $defaults);

            ?>
            <!-- The Title -->
            <p>
                <label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Título:', THEMEDOMAIN); ?></label>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
            </p>

            <!-- Category -->
            <?php
                $args = array(
                        'taxonomy' => 'category',
                        'exclude'  => '1'
                );
                $categories = get_categories($args);
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('category') ?>"><?php _e('Categoría:', THEMEDOMAIN); ?></label>
                <select id="<?php echo $this->get_field_id('category') ?>" name="<?php echo $this->get_field_name('category'); ?>">
            <?php
                    foreach ($categories as $category) {
                ?>
                        <option value="<?php echo $category->cat_ID; ?>" <?php selected($instance['category'], $category->cat_ID); ?>><?php echo $category->name; ?></option>
                <?php
                    }
                ?>
        </select>
            </p>

            <!-- Orientation -->
            <p>
                <label for="<?php echo $this->get_field_id('orientation') ?>"><?php _e('Orientación:', THEMEDOMAIN); ?></label>
                <select id="<?php echo $this->get_field_id('orientation') ?>" name="<?php echo $this->get_field_name('orientation'); ?>">
                    <option value="1" <?php selected($instance['orientation'], 1); ?>>Vertical</option>
                    <option value="2" <?php selected($instance['orientation'], 2); ?>>Horizontal</option>
                </select>
            </p>
            <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        // The Title
        $instance['title'] = strip_tags($new_instance['title']);

        // Category
        $instance['category']    = $new_instance['category'];
        $instance['orientation'] = $new_instance['orientation'];

        return $instance;
    }

    public function widget($args, $instance)
    {
        extract($args);

        // Get the title and prepare it for display
        $title = apply_filters('widget_title', $instance['title']);

        $category = (int)$instance['category'];
        $orientation = (int)$instance['orientation'];

        $classOrientation = ($orientation === 2) ? 'Calendar-horizontal' : '';

        $date = date('Y-m-d');

        $thisCat = get_category($category);

        echo $before_widget;

        if ($title) {
                echo $before_title . '<span class="Widget-titleBg">' . $title . '</span>' . $after_title;
        }

?>
        <aside class="WidgetCalendar <?php echo $classOrientation; ?>">
            <div class="Calendar text-center">
                <div id="js-dtp-events"></div>
            </div><!-- end Calendar -->
<?php
            $args = array(
                'cat'            => $category,
                'post_type'      => 'post',
                'posts_per_page' => -1,
                'meta_key'       => 'mb_date',
                'meta_type'      => 'DATE',
                'meta_query'     => array(
                    array(
                        'key'     => 'mb_date',
                        'value'   => array(date('Y-m-01', strtotime($date)), date('Y-m-t', strtotime($date))),
                        'type'    => 'DATE',
                        'compare' => 'BETWEEN'
                    )
                ),
                'order'          => 'ASC',
                'orderby'        => 'meta_value_date',
            );
            $the_query = new WP_Query($args);

                echo '<div class="WidgetCalendar-events">';
                echo '<h5>' . $thisCat->name . '</h5>';
?>
                <div class="select-style">
                    <select class="Calendar-filter" name="filter-event" id="filter-event" data-category="<?php echo $category; ?>">
                        <option value="0">Filtrar por</option>
                        <option value="1">Destacados</option>
                        <option value="2">Fin de semana</option>
                    </select>
                </div>
<?php
                echo '<div class="Main-content-loader text-center hidden" id="js-loading-events"><img src="' . IMAGES . '/loading.gif" /></div>';

                echo '<div class="WidgetCalendar-wrapper" data-category="' . $category . '">';

            if($the_query->have_posts()) {
                while($the_query->have_posts()) {
                    $the_query->the_post();
                    $id = get_the_id();
                    $values = get_post_custom($id);
                    $dateEvent = (isset($values['mb_date'])) ? esc_attr($values['mb_date'][0]) : '';

                    if(!empty($dateEvent)) :
?>
                        <div class="WidgetCalendar-event">
                                <div class="WidgetCalendar-event-day">
                                        <span class="WidgetCalendar-dayWeek"><?php echo date_i18n('D', strtotime($dateEvent)); ?></span>
                                        <span class="WidgetCalendar-day"><?php echo date_i18n('d', strtotime($dateEvent)); ?></span>
                                </div><!-- end WidgetCalendar-event-day -->
                                <div class="WidgetCalendar-event-info">
                                        <h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                                        <?php the_excerpt(); ?>
                                </div><!-- end WidgetCalendar-event-info -->
                        </div><!-- end WidgetCalendar-event -->
<?php
                    endif;
                }
            }
                    echo '</div>';
                    echo '</div>';

            wp_reset_postdata();
            echo '</aside><!-- end WidgetCalendar -->';

            echo $after_widget;
    }
}

register_widget('Informal_Events_Calendar_Widget');

?>