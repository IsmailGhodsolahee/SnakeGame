<?php
/*
Plugin Name: Snake Game
Description: Snake Game
Version: 1.0
Author: IsmailGhodsollahee
*/

function snake_game_shortcode() {
    wp_enqueue_style('snake-game-css', plugin_dir_url(__FILE__) . 'snake-game.css');
    wp_enqueue_script('snake-game-js', plugin_dir_url(__FILE__) . 'snake-game.js', array(), null, true);

    return '
    <div class="snake-game-container">
        <canvas id="snake-game" width="400" height="400"></canvas>
    </div>';
}
add_shortcode('snake_game', 'snake_game_shortcode');

function snake_game_settings_menu() {
    add_menu_page(
        'تنظیمات بازی اسنک', 
        'بازی اسنک', 
        'manage_options', 
        'snake-game-settings', 
        'snake_game_settings_page', 
        'dashicons-games', 
        100
    );
}
add_action('admin_menu', 'snake_game_settings_menu');

function snake_game_assets() {
  
    wp_enqueue_style('snake-game-css', plugin_dir_url(__FILE__) . 'snake-game.css');

    
    wp_enqueue_script('snake-game-js', plugin_dir_url(__FILE__) . 'snake-game.js', array(), null, true);

  
    wp_localize_script('snake-game-js', 'snakeGameSettings', array(
        'snakeColor' => get_option('snake_game_snake_color', '#00ff00'), 
        'foodColor' => get_option('snake_game_food_color', '#ff0000'),   
        'gridSize' => get_option('snake_game_grid_size', 20),            
        'matrixSize' => get_option('snake_game_matrix_size', 10)         
    ));
}
add_action('wp_enqueue_scripts', 'snake_game_assets');

function snake_game_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

   
    if (isset($_POST['snake_game_settings_nonce'])) {
        check_admin_referer('snake_game_settings_action', 'snake_game_settings_nonce');

      
        update_option('snake_game_speed', intval($_POST['snake_game_speed']));
        update_option('snake_game_grid_size', intval($_POST['snake_game_grid_size']));
        update_option('snake_game_snake_color', sanitize_hex_color($_POST['snake_game_snake_color']));
        update_option('snake_game_food_color', sanitize_hex_color($_POST['snake_game_food_color']));
        update_option('snake_game_matrix_size', intval($_POST['snake_game_matrix_size'])); 

        echo '<div class="notice notice-success"><p>تنظیمات با موفقیت ذخیره شدند.</p></div>';
    }

   
    $speed = get_option('snake_game_speed', 100);
    $grid_size = get_option('snake_game_grid_size', 20);
    $snake_color = get_option('snake_game_snake_color', '#00ff00');
    $food_color = get_option('snake_game_food_color', '#ff0000');
    $matrix_size = get_option('snake_game_matrix_size', 10); 
    ?>
    <div class="wrap">
        <h1>تنظیمات بازی اسنک</h1>
        <form method="post" action="">
            <?php wp_nonce_field('snake_game_settings_action', 'snake_game_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="snake_game_speed">سرعت بازی (میلی‌ثانیه)</label></th>
                    <td>
                        <input type="number" name="snake_game_speed" id="snake_game_speed" value="<?php echo esc_attr($speed); ?>" min="50" max="500" step="10">
                        <p class="description">سرعت حرکت مار (هرچه کمتر، سریع‌تر).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="snake_game_grid_size">اندازه سلول (پیکسل)</label></th>
                    <td>
                        <input type="number" name="snake_game_grid_size" id="snake_game_grid_size" value="<?php echo esc_attr($grid_size); ?>" min="10" max="50" step="1">
                        <p class="description">اندازه هر سلول در صفحه بازی.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="snake_game_matrix_size">اندازه ماتریس (تعداد سلول‌ها)</label></th>
                    <td>
                        <input type="number" name="snake_game_matrix_size" id="snake_game_matrix_size" value="<?php echo esc_attr($matrix_size); ?>" min="5" max="20" step="1">
                        <p class="description">تعداد سلول‌ها در هر ردیف و ستون (مثلاً ۱۰ برای ماتریس ۱۰x۱۰).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="snake_game_snake_color">رنگ مار</label></th>
                    <td>
                        <input type="color" name="snake_game_snake_color" id="snake_game_snake_color" value="<?php echo esc_attr($snake_color); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="snake_game_food_color">رنگ غذا</label></th>
                    <td>
                        <input type="color" name="snake_game_food_color" id="snake_game_food_color" value="<?php echo esc_attr($food_color); ?>">
                    </td>
                </tr>
            </table>
            <?php submit_button('ذخیره تغییرات'); ?>
        </form>
    </div>
    <?php
}
