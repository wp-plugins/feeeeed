<?php
/**
 * feed2html.php
 * -*- Encoding: utf8n -*-
 */
function feed2html($model) {
    header("Content-type: text/html; charset=utf-8");
    ?>
    
    <h2><a href="<?php bloginfo_rss('url') ?>"><?php bloginfo_rss('name'); wp_title_rss(); ?></a></h2>
    <h3 class="description"><?php bloginfo_rss("description") ?></h3>
    <ul>
      <?php while( have_posts()) : the_post(); ?>
        <?php $date = date($model->text_date_format, strtotime(get_post_time('Y-m-d H:i:s', true))); ?>
        <li>
          <h3 class="title"><a href="<?php the_permalink_rss(); ?>"><?php the_title_rss() ?></a></h3>
          <p class="description"><?php the_excerpt_rss() ?></p>
          <p class="author"><?php echo _e('Author:','feeeeed');?> <?php the_author() ?>
            <span class ="pubDate"><?php echo _e('Date: ','feeeeed');?><?php echo $date; ?></span>
            <span class="comments"><a href="<?php comments_link(); ?>">comments</a></span>
          </p>
	</li>
      <?php endwhile; ?>
    </ul>
  <?php
}
?>
