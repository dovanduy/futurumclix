<ul>
	<?php foreach($ads as $ad): ?>
	<li>
		<h6><?=$this->Html->link($ad['FeaturedAd']['title'], array('plugin' => '', 'controller' => 'featuredAds', 'action' => 'view', $ad['FeaturedAd']['id']), array('target' => 'blank'))?></h6>
		<p>
			<?=$this->Html->link($ad['FeaturedAd']['description'], array('plugin' => '', 'controller' => 'featuredAds', 'action' => 'view', $ad['FeaturedAd']['id']), array('target' => 'blank'))?>
		</p>
	</li>
	<?php endforeach; ?>
</ul>
