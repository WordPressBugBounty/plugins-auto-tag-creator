<h1><?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 echo esc_html__('Tag Creation Setting','ec-auto-tag-creator'); ?></h1>
		<div class="wrap"><br/>
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
					<td>
					</td>
					<td>
						<input type="submit" id="submit_settings" class="button-primary button button-hero" onclick="return auto_tag_save_settings();" value="<?php echo esc_html__('Save Settings','ec-auto-tag-creator');?>">
						<img class="loader_image" src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) ); ?>images/loader.gif">
						<h3><strong><?php echo esc_html__('If you want to have same  functionality for other post type as well, please','ec-auto-tag-creator'); ?> <a href="http://ecreateinfotech.com/contact-us/" target="_blank"><?php echo esc_html__('contact us','ec-auto-tag-creator'); ?></a> <?php echo esc_html__('for any type of custom work.','ec-auto-tag-creator'); ?></strong></h3>
					</td>
				</tr>
			</tbody>
			</table>
		</form>
		<br/>
		</div>