<?php
global $katex_options;
$katex_options = get_option('katex_settings');

function katex_init() {
	global $katex_options;

	if (is_array($katex_options)) {
		if (isset($katex_options['katex_jsdelivr_setting']) && $katex_options['katex_jsdelivr_setting'] == 1) {
			wp_register_style('katex', '//cdn.jsdelivr.net/katex/0.1.1/katex.min.css', false, null);
			wp_register_script('katex', '//cdn.jsdelivr.net/katex/0.1.1/katex.min.js', false, array('jquery'));
		} else {
			wp_register_style('katex', plugins_url('assets/katex.min.css', dirname(__FILE__)), false, '0.1.1');
			wp_register_script('katex', plugins_url('assets/katex.min.js', dirname(__FILE__)), array('jquery'), '0.1.1', true);
		}
	} else {
		wp_register_style('katex', plugins_url('assets/katex.min.css', dirname(__FILE__)), false, '0.1.1');
		wp_register_script('katex', plugins_url('assets/katex.min.js', dirname(__FILE__)), array('jquery'), '0.1.1', true);
	}
}
add_action('init', 'katex_init');

function katex_handler($atts, $content = null){
	wp_enqueue_script('katex');
	wp_enqueue_style('katex');
	$break = null;
	if (strpos($content, '\\displaystyle') === 0) {
		return '<p><script type="text/katex">' . html_entity_decode($content) . '</script></p>';
	} else {
		return '<script type="text/katex">' . html_entity_decode($content) . '</script>';
	}
}
add_shortcode('latex', 'katex_handler');

function katex_rubber() {
	?>
	<script>
		(function($) {
			$("script[type='text/katex']").each(function (i) {
				try {
					katex.render(this.text, $("<span></span>").insertAfter(this)[0]);
				} catch (e) {
					$("<span style='color:red'>" + e + "</span>").insertAfter(this);
				}
			});
		})(jQuery);
	</script>
	<?php
}
add_action('wp_footer', 'katex_rubber', 100);