<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap ec-atc-wrap">
	<h1><?php echo esc_html__('Tag Creation Setting','ec-auto-tag-creator'); ?></h1>

	<div class="ec-atc-layout">
	<div class="ec-atc-main">

	<div class="ec-atc-card">
		<form action="" id="tag_setting_from" method="post">
			<table class="form-table">
			<tbody>
				<tr>
					<th>
					<label><?php echo esc_html__('Enable for Post','ec-auto-tag-creator'); ?></label>
					</th>
					<td>
						<input <?php if( $settings['enable_post_title']=='1') { checked( true ); } ?> type="checkbox" value="1" name="enable_post_title" id="enable_post_title"/>
						<label for="enable_post_title"><?php echo esc_html__('Create tag by post title','ec-auto-tag-creator'); ?></label>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input <?php if( $settings['enable_post_category']=='1') { checked( true ); } ?> type="checkbox" value="1" name="enable_post_category" id="enable_post_category"/>
						<label for="enable_post_category"><?php echo esc_html__('Create tag by post category','ec-auto-tag-creator'); ?></label>
					</td>
				</tr>
				<tr>
					<th>
					<label><?php echo esc_html__('Enable for Product','ec-auto-tag-creator'); ?></label>
					</th>
					<td>
						<input <?php if( $settings['enable_product_title']=='1') { checked( true ); } ?> type="checkbox" value="1" name="enable_product_title" id="enable_product_title"/>
						<label for="enable_product_title"><?php echo esc_html__('Create tag by product title','ec-auto-tag-creator'); ?></label>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input <?php if( $settings['enable_product_category']=='1') { checked( true ); } ?> type="checkbox" value="1" name="enable_product_category" id="enable_product_category"/>
						<label for="enable_product_category"><?php echo esc_html__('Create tag by product category','ec-auto-tag-creator'); ?></label>
					</td>
				</tr>
				<tr>
					<th>
					<label for="overwrite_tag"><?php echo esc_html__('Overwrite Previous Tag','ec-auto-tag-creator'); ?></label>
					</th>
					<td>
						<input <?php if( $settings['overwrite_tag']=='1') { checked( true ); } ?> type="checkbox" value="1" name="overwrite_tag" id="overwrite_tag"/>
						<p><?php echo esc_html__('If this is enable all your previous tags will be removed and new tags based on your selection will be created.','ec-auto-tag-creator'); ?></p>
					</td>
				</tr>
				<tr>
					<th>
					<label for="minimum_word"><?php echo esc_html__('Mimimum word length to create tag','ec-auto-tag-creator'); ?></label>
					</th>
					<td>
						<input type="number" min="0" value="<?php echo esc_attr( esc_html($settings['minimum_word']) ); ?>" name="minimum_word" id="minimum_word"/>
						<p><?php echo esc_html__('Zero or blank will ignore this setting.','ec-auto-tag-creator'); ?></p>
					</td>
				</tr>
				<tr>
					<th>
					<label for="ignore_words"><?php echo esc_html__('Ignore words list','ec-auto-tag-creator'); ?></label>
					</th>
					<td>
						<textarea cols="120" rows="8" name="ignore_words" id="ignore_words"><?php echo esc_html($settings['ignore_words']); ?></textarea>
						<p><?php echo esc_html__('Please add comma separated value.','ec-auto-tag-creator'); ?></p>
					</td>
				</tr>
				<tr>
					<th>
					<label for="use_stop_words"><?php echo esc_html__('Ignore common words','ec-auto-tag-creator'); ?></label>
					</th>
					<td>
						<input <?php checked( $settings['use_stop_words'], '1' ); ?> type="checkbox" value="1" name="use_stop_words" id="use_stop_words"/>
						<label for="use_stop_words"><?php echo esc_html__('Skip common English words like "the", "and", "for", "with"','ec-auto-tag-creator'); ?></label>
						<p><?php echo esc_html__('Works together with your own ignore words list above.','ec-auto-tag-creator'); ?></p>
					</td>
				</tr>
				<tr>
					<th>
					<label for="tag_case"><?php echo esc_html__('Tag letter case','ec-auto-tag-creator'); ?></label>
					</th>
					<td>
						<select name="tag_case" id="tag_case">
							<option value="original" <?php selected( $settings['tag_case'], 'original' ); ?>><?php echo esc_html__('As written in title or category','ec-auto-tag-creator'); ?></option>
							<option value="lower" <?php selected( $settings['tag_case'], 'lower' ); ?>><?php echo esc_html__('lowercase (running shoes)','ec-auto-tag-creator'); ?></option>
							<option value="title" <?php selected( $settings['tag_case'], 'title' ); ?>><?php echo esc_html__('Title Case (Running Shoes)','ec-auto-tag-creator'); ?></option>
						</select>
						<p><?php echo esc_html__('If a tag with the same name already exists, WordPress uses the existing tag as it is.','ec-auto-tag-creator'); ?></p>
					</td>
				</tr>
				<tr>
					<th>
					<label for="max_tags"><?php echo esc_html__('Maximum tags per item','ec-auto-tag-creator'); ?></label>
					</th>
					<td>
						<input type="number" min="0" value="<?php echo esc_attr( $settings['max_tags'] ); ?>" name="max_tags" id="max_tags"/>
						<p><?php echo esc_html__('Maximum number of new tags created for each post or product. Existing tags are not removed. Zero or blank means no limit.','ec-auto-tag-creator'); ?></p>
					</td>
				</tr>
			</tbody>
			</table>
			<div class="ec-atc-submit">
				<input type="submit" id="submit_settings" class="button button-primary" onclick="return auto_tag_save_settings();" value="<?php echo esc_attr__('Save Settings','ec-auto-tag-creator');?>">
				<img class="loader_image" src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) ); ?>images/loader.gif" alt="">
			</div>
		</form>
	</div>

	<div class="ec-atc-card">
		<h2 class="ec-atc-card-title"><span class="dashicons dashicons-update"></span><?php echo esc_html__('Tag Existing Content','ec-auto-tag-creator'); ?></h2>
		<p class="ec-atc-card-desc"><?php echo esc_html__('Generate tags for items that are already published, using the saved settings above. Save your settings first.','ec-auto-tag-creator'); ?></p>
		<?php foreach ( $bulk_post_types as $bulk_post_type => $bulk_label ) : ?>
		<div class="ec-atc-bulk-row">
			<div class="ec-atc-bulk-label">
				<strong><?php echo esc_html( $bulk_label ); ?></strong>
				<div class="ec-auto-tag-progress" hidden><span></span></div>
				<span class="ec-auto-tag-bulk-status"></span>
			</div>
			<button type="button" class="button button-secondary ec-auto-tag-bulk" data-post-type="<?php echo esc_attr( $bulk_post_type ); ?>" data-title="<?php /* translators: %s: post type label, e.g. Posts. */ echo esc_attr( sprintf( __( 'Generate tags for all %s?', 'ec-auto-tag-creator' ), $bulk_label ) ); ?>">
				<?php
				/* translators: %s: post type label, e.g. Posts. */
				echo esc_html( sprintf( __( 'Generate tags for all %s', 'ec-auto-tag-creator' ), $bulk_label ) );
				?>
			</button>
		</div>
		<?php endforeach; ?>
	</div>

	</div>

	<div class="ec-atc-sidebar">
	<div class="ec-atc-promo">
		<span class="dashicons dashicons-admin-tools ec-atc-promo-icon"></span>
		<div class="ec-atc-promo-text">
			<h3><?php echo esc_html__('Need auto tagging for other post types?','ec-auto-tag-creator'); ?></h3>
			<p><?php echo esc_html__('We can add the same functionality for your custom post types, or build any other custom WordPress work you need.','ec-auto-tag-creator'); ?></p>
		</div>
		<a class="button ec-atc-promo-button" href="https://www.ecreateinfotech.com/contact/?utm_source=wp-plugin&amp;utm_medium=settings&amp;utm_campaign=auto-tag-creator" target="_blank" rel="noopener noreferrer"><?php echo esc_html__('Contact Us','ec-auto-tag-creator'); ?></a>
	</div>
	<?php $ec_atc_more = Ec_Auto_Tag_Creator_More_Plugins::get( 'auto-tag-creator' ); ?>
	<div class="ec-atc-card ec-atc-more">
		<h3><?php echo esc_html__( 'More free plugins by Ecreate', 'ec-auto-tag-creator' ); ?></h3>
		<?php if ( $ec_atc_more ) : ?>
			<ul>
				<?php foreach ( $ec_atc_more as $ec_atc_plugin ) : ?>
					<li>
						<a href="<?php echo esc_url( Ec_Auto_Tag_Creator_More_Plugins::plugin_url( $ec_atc_plugin['slug'] ) ); ?>"><?php echo esc_html( $ec_atc_plugin['name'] ); ?></a>
						<?php if ( '' !== $ec_atc_plugin['description'] ) : ?>
							<br/><span><?php echo esc_html( $ec_atc_plugin['description'] ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<p><a href="<?php echo esc_url( Ec_Auto_Tag_Creator_More_Plugins::all_plugins_url() ); ?>"><?php echo esc_html__( 'See all our plugins', 'ec-auto-tag-creator' ); ?></a></p>
	</div>
	</div>
	</div>

	<div class="ec-atc-modal" id="ec-atc-bulk-modal" hidden>
		<div class="ec-atc-modal-backdrop" data-close></div>
		<div class="ec-atc-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="ec-atc-modal-title" aria-describedby="ec-atc-modal-desc">
			<button type="button" class="ec-atc-modal-x" data-close aria-label="<?php echo esc_attr__('Close','ec-auto-tag-creator'); ?>"><span class="dashicons dashicons-no-alt"></span></button>
			<span class="dashicons dashicons-tag ec-atc-modal-icon"></span>
			<h2 id="ec-atc-modal-title"></h2>
			<p id="ec-atc-modal-desc"><?php echo esc_html__('Tags will be added to every published item of this type using your saved settings. Large sites may take a few minutes, please keep this page open until it finishes.','ec-auto-tag-creator'); ?></p>
			<div class="ec-atc-modal-warning" <?php echo $settings['overwrite_tag'] == '1' ? '' : 'hidden'; ?>>
				<span class="dashicons dashicons-warning"></span>
				<span><?php echo esc_html__('"Overwrite Previous Tag" is enabled, so existing tags on these items will be replaced.','ec-auto-tag-creator'); ?></span>
			</div>
			<div class="ec-atc-modal-actions">
				<button type="button" class="button" data-close><?php echo esc_html__('Cancel','ec-auto-tag-creator'); ?></button>
				<button type="button" class="button button-primary" id="ec-atc-modal-confirm"><?php echo esc_html__('Generate Tags','ec-auto-tag-creator'); ?></button>
			</div>
		</div>
	</div>
</div>
