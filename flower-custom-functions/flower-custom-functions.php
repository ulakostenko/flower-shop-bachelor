<?php
/**
 *  Основні функції плагіна для таймера та знижки.
 *
 * @package Wordpress_Custom_Plugin
 * @pluginName Flower Shop – Custom Functions
 * @description Кастомні функції для інтернет-магазину квітів.
 * @version 1.0
 * @author Костенко Юлія
 */

/**
 * Показує SVG-таймер до кінця акції.
 *
 * @param array $atts Атрибути шорткоду.
 * @return string HTML таймера або повідомлення.
 */
function custom_sale_countdown_timer( $atts ) {
	$atts = shortcode_atts(
		array(
			'end_date' => '',
		),
		$atts
	);

	if ( ! $atts['end_date'] ) {
		return '<p>Кінцева дата акції не вказана.</p>';
	}

	ob_start(); ?>
	<div id="svg-timer" data-end-date="<?php echo esc_attr( $atts['end_date'] ); ?>">
		<?php
		foreach ( array(
			'days'    => 'Дні',
			'hours'   => 'Години',
			'minutes' => 'Хвилини',
			'seconds' => 'Секунди',
		) as $unit => $label ) :
			?>
			<div class="circle-box">
				<svg width="180" height="180">
					<circle class="bg" cx="90" cy="90" r="80"/>
					<circle class="progress" id="circle-<?php echo esc_attr( $unit ); ?>" cx="90" cy="90" r="80"/>
				</svg>
				<div class="number" id="<?php echo esc_attr( $unit ); ?>">00</div>
				<div class="label"><?php echo esc_html( $label ); ?></div>
			</div>
		<?php endforeach; ?>

	</div>
	<style>
		#svg-timer {
			display: flex;
			justify-content: center;
			gap: 40px;
			margin: 40px 0;
			flex-wrap: wrap;
			transform: scale(1.3);
		}

		.circle-box {
			position: relative;
			width: 180px;
			height: 180px;
			text-align: center;
		}

		svg {
			transform: rotate(-90deg);
		}

		circle.bg {
			fill: none;
			stroke: #FCEEF5;
			stroke-width: 10;
		}

		circle.progress {
			fill: none;
			stroke: #FB5FAB;
			stroke-width: 10;
			stroke-linecap: round;
			stroke-dasharray: 502;
			stroke-dashoffset: 502;
			transition: stroke-dashoffset 0.5s ease;
		}

		.circle-box .number {
			font-weight: bold;
			font-size: 32px;
			color: #111111;
			position: absolute;
			top: 60px;
			left: 0;
			right: 0;
			font-family: inherit;
		}

		.circle-box .label {
			margin-top: 8px;
			font-size: 16px;
			color: #111111;
			font-family: inherit;
		}
	</style>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const container = document.getElementById('svg-timer');
			if (!container) return;

			const endDate = new Date(container.getAttribute('data-end-date')).getTime();

			const total = {
				days: 365,
				hours: 24,
				minutes: 60,
				seconds: 60
			};

			function updateTimer() {
				const now = new Date().getTime();
				const distance = endDate - now;

				if (distance <= 0) {
					container.innerHTML = "<p>⏰ Акція завершена!</p>";
					return;
				}

				const d = Math.floor(distance / (1000 * 60 * 60 * 24));
				const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				const s = Math.floor((distance % (1000 * 60)) / 1000);

				const values = {days: d, hours: h, minutes: m, seconds: s};

				Object.entries(values).forEach(([unit, value]) => {
					document.getElementById(unit).textContent = String(value).padStart(2, '0');
					const circle = document.getElementById('circle-' + unit);
					const progress = 502 - (502 * value / total[unit]);
					circle.style.strokeDashoffset = progress;
				});
			}

			updateTimer();

			setInterval(updateTimer, 1000);


		});
	</script>
	<?php
	return ob_get_clean();
}

/**
 * Реєстрація шорткоду для виводу таймера
 */
add_shortcode( 'sale_timer', 'custom_sale_countdown_timer' );



/**
 * Знижка при купівлі 3+ букетів.
 *
 * @param WC_Cart $cart Кошик покупця.
 * @return void
 */
function custom_bulk_discount( $cart ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	if ( $cart->get_cart_contents_count() >= 3 ) {
		$discount = $cart->subtotal * 0.10;
		$cart->add_fee( __( 'Знижка на обʼємне замовлення', 'flower-shop' ), -$discount );
	}
}

/**
 * Реєстрація знижки на кошик під час обчислення вартості
 */
add_action( 'woocommerce_cart_calculate_fees', 'custom_bulk_discount' );
