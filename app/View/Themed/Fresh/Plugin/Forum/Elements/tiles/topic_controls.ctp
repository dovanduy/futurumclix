<?php if ($user) {
	$isMod = $this->Forum->isMod($topic['Forum']['id']); ?>
<div class="action-buttons">
	<?php if ($settings['enableTopicSubscriptions']) {
		if (empty($subscription)) {
			echo $this->Html->link(__d('forum', 'Subscribe'), array('controller' => 'topics', 'action' => 'subscribe', $topic['Topic']['id']), array('class' => 'uk-button uk-button-secondary uk-button-small', 'onclick' => 'return Forum.subscribe(this);'));
		} else {
			echo $this->Html->link(__d('forum', 'Unsubscribe'), array('controller' => 'topics', 'action' => 'unsubscribe', $subscription['Subscription']['id']), array('class' => 'uk-button uk-button-secondary uk-button-small', 'onclick' => 'return Forum.unsubscribe(this);'));
		}
		}
		
		if ($isMod) {
		echo $this->Html->link(__d('forum', 'Moderate'), array('controller' => 'topics', 'action' => 'moderate', $topic['Topic']['slug']), array('class' => 'uk-button uk-button-secondary uk-button-small'));
		}
		
		if ($this->Forum->hasAccess('Forum.Topic', 'create', $topic['Forum']['accessPost']) || $isMod) {
		echo $this->Html->link(__d('forum', 'Create Topic'), array('controller' => 'topics', 'action' => 'add', $topic['Forum']['slug']), array('class' => 'uk-button uk-button-secondary uk-button-small'));
		}
		
		if ($this->Forum->hasAccess('Forum.Poll', 'create', $topic['Forum']['accessPoll']) || $isMod) {
		echo $this->Html->link(__d('forum', 'Create Poll'), array('controller' => 'topics', 'action' => 'add', $topic['Forum']['slug'], 'poll'), array('class' => 'uk-button uk-button-secondary uk-button-small'));
		}
		
		if ($this->Forum->hasAccess('Forum.Post', 'create', $topic['Forum']['accessReply']) || $isMod) {
		if ($topic['Topic']['status']) {
			echo $this->Html->link(__d('forum', 'Post Reply'), array('controller' => 'posts', 'action' => 'add', $topic['Topic']['slug']), array('class' => 'uk-button uk-button-secondary uk-button-small'));
		} else {
			echo $this->Html->link(__d('forum', 'Closed'), 'javascript:;', array('class' => 'uk-button uk-button-secondary uk-button-small'));
		}
		} ?>
</div>
<?php } ?>
