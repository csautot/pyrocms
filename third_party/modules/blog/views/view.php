<h2><?php echo $entry->title; ?></h2>
<?php if($entry->category->slug): ?>
	<p><?php echo lang('news_posted_label'); ?> <?php echo anchor('blog/category/'.$entry->category->slug, $entry->category->title); ?> <?php echo lang('news_date_at'); ?> <?php echo date('M d, Y', $entry->created_on); ?></p>
<?php else: ?>
	<p><?php echo lang('news_posted_label_alt'); ?> <?php echo date('M d, Y', $entry->created_on); ?></p>
<?php endif; ?>

<?php echo stripslashes($entry->body); ?>
<?php
if( $this->settings->item('enable_social_bookmarks'))
{
	echo $this->load->view('fragments/social_bookmarking/toolbar', array('bookmark' => array('title' => $entry->title)));
}
?>
<?php echo display_comments($entry->id); ?>