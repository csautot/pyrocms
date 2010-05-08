<h2><?php echo lang('news_articles_title');?></h2>
<div class="float-left width-two-thirds">
	<?php if (!empty($entries)): ?>
		<?php foreach ($entries as $entry): ?>
			<h3><?php echo  anchor('blog/' . $entry->slug, $entry->title); ?></h3>
			<p><?php echo nl2br($entry->intro) ?> <?php echo anchor('blog/' . $entry->slug, lang('news_read_more_label'))?></p>
			<p>
				<em><?php echo lang('news_posted_label');?>: <?php echo date('M d, Y', $entry->created_on); ?></em>&nbsp;
				<?php if($entry->category_slug): ?>
					<em><?php echo lang('news_category_label');?>: <?php echo anchor('blog/category/'.$entry->category_slug, $entry->category_title);?></em>
				<?php endif; ?>
			</p>
			<hr/>
		<?php endforeach; ?>

		<?php echo $pagination['links']; ?>

	<?php else: ?>
		<p><?php echo lang('news_currently_no_articles');?></p>
	<?php endif; ?>
</div>

<div class="float-right width-quater">
	{widget_area('blog_sidebar')}
</div>