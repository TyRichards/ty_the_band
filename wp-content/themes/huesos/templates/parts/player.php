<?php
/**
 * Underscore.js templates for displaying the audio player bar across the top of
 * the site when it's enabled.
 *
 * @package Huesos
 * @since 1.0.0
 */
?>

<script type="text/html" id="tmpl-huesos-player">
	<div class="controls">
		<button class="previous"><?php esc_html_e( 'Previous Track', 'huesos' ); ?></button>
		<button class="play-pause"><?php esc_html_e( 'Play', 'huesos' ); ?></button>
		<button class="next"><?php esc_html_e( 'Next Track', 'huesos' ); ?></button>

		<div class="current-track-details">
			<span class="artist">{{ data.artist }}</span>
			<span class="title">{{ data.title }}</span>
		</div>

		<div class="volume-bar">
			<div class="volume-bar-current"></div>
		</div>

		<div class="progress-bar">
			<div class="play-bar"></div>
		</div>
	</div>
</script>

<script type="text/html" id="tmpl-huesos-player-track">
	<span class="track-status track-cell"></span>

	<span class="track-details track-cell">
		<span class="track-title">{{ data.title }}</span>
		<span class="track-artist">{{ data.artist }}</span>
	</span>

	<span class="track-length track-cell">{{ data.length }}</span>

	<span class="track-remove track-cell">
		<button class="remove js-remove"><?php esc_html_e( 'Remove Track', 'huesos' ); ?></button>
	</span>
</script>
