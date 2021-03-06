<?php
if (!empty($topic['Forum']['Parent']['slug'])) {
    $this->Breadcrumb->add($topic['Forum']['Parent']['title'], array('controller' => 'stations', 'action' => 'view', $topic['Forum']['Parent']['slug']));
}

$this->Breadcrumb->add($topic['Forum']['title'], array('controller' => 'stations', 'action' => 'view', $topic['Forum']['slug']));
$this->Breadcrumb->add($topic['Topic']['title'], array('controller' => 'topics', 'action' => 'view', $topic['Topic']['slug']));
$this->Breadcrumb->add(__d('forum', 'Edit Topic'), array('controller' => 'topics', 'action' => 'edit', $topic['Topic']['slug'])); ?>

<div class="panel-head">
    <h3><?php echo __d('forum', 'Edit Topic'); ?></h3>
</div>
<div class="panel">
<?php
echo $this->Form->create('Topic');
?>
<div class="col-md-12">
<?php
echo $this->Form->input('title', array('div' => 'input-group', 'class' => 'form-control', 'label' => array('class' => 'input-group-addon', 'text' => __d('forum', 'Title'))));
echo '<br />';
if ($this->Forum->isMod($topic['Forum']['id'])) {
	echo $this->Form->input('forum_id', array('div' => 'input-group', 'class' => 'form-control', 'label' => array('class' => 'input-group-addon', 'text' => __d('forum', 'Forum')), 'options' => $forums, 'empty' => '-- ' . __d('forum', 'Select a Forum') . ' --'));
	echo '<br />';
	echo $this->Form->input('status', array('div' => 'input-group', 'class' => 'form-control', 'label' => array('class' => 'input-group-addon', 'text' => __d('forum', 'Status')), 'options' => $this->Utility->enum('Forum.Topic', 'status')));
	echo '<br />';
	echo $this->Form->input('type', array('div' => 'input-group', 'class' => 'form-control', 'options' => $this->Utility->enum('Forum.Topic', 'type'), 'label' => array('class' => 'input-group-addon', 'text' => __d('forum', 'Type'))));
	echo '<br />';
} else {
	echo $this->Form->input('forum_id', array('type' => 'hidden'));
}

if (!empty($topic['Poll']['id'])) { ?>

	<div class="input poll">
		<?php
		echo $this->Form->label('Poll.id', __d('forum', 'Poll Options'));
		echo $this->Form->input('Poll.id', array('type' => 'hidden')); ?>

		<div class="form-poll">
			<table>
				<?php foreach ($topic['Poll']['PollOption'] as $row => $option) { ?>
					<tr>
						<td>
							<?php echo $this->Form->input('Poll.PollOption.' . $row . '.id', array('type' => 'hidden')); ?>
							<?php echo $this->Form->input('Poll.PollOption.' . $row . '.option', array('div' => false, 'label' => false)); ?>
						</td>
						<td>
							<?php echo $this->Form->input('Poll.PollOption.' . $row . '.delete', array('type' => 'checkbox', 'div' => false, 'label' => false, 'value' => 0)); ?>
							<?php echo __d('forum', 'Delete?'); ?>
						</td>
					</tr>
				<?php } ?>
			</table>
		</div>
	</div>
	<?php echo $this->Form->input('Poll.expires', array(
		'label' => __d('forum', 'Expiration Date'),
		'after' => '<span class="input-help">' . __d('forum', 'How many days till expiration? Leave blank to last forever.') . '</span>',
		'class' => 'numeric',
		'type' => 'text'
	));
}?>
</div>
<div class="col-md-12 text-center">
<?php
if ($topic['Forum']['excerpts']) {
	$chars = isset($this->request->data['Topic']['excerpt']) ? strlen($this->request->data['Topic']['excerpt']) : 0;
	$maxLength = $settings['excerptLength'];

	echo $this->Form->input('excerpt', array(
		'div' => 'input-group',
		'class' => 'form-control',
		'label' =>  array('class' => 'input-group-addon', 'text' => __d('forum', 'Excerpt')),
		'type' => 'textarea',
		'rows' => 5,
		'onkeyup' => 'Forum.charsRemaining(this, ' . $maxLength . ');',
		'after' => '<span class="input-group-addon">' . __d('forum', '%s characters remaining', '<span id="TopicExcerptCharsRemaining">' . ($maxLength - $chars) . '</span>') . '</span>',
	));
	echo '<br><div class="text-xs-center">';
}

echo $this->Form->input('FirstPost.id', array('type' => 'hidden'));
echo $this->Form->input('FirstPost.content', array(
	'label' => false,
	'type' => 'textarea',
	'rows' => 15,
	'class' => 'form-control'
));
echo '<br />';
echo $this->element('decoda', array('id' => 'FirstPostContent'));
echo $this->Form->submit(__d('forum', 'Edit Topic'), array('class' => 'btn btn-primary text-xs-right'));
echo $this->Form->end();
echo '</div>'; 
echo '<br />'; ?>
</div>
</div>