<h1><?php echo $article->title; ?></h1>

<p><?php echo anchor('blog/' . $article->slug, NULL, 'target="_blank"'); ?></p>

<iframe src="<?php echo site_url('blog/' . $article->slug); ?>" width="100%" height="515"></iframe>