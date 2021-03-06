<?=$this->element('userSidebar')?>
<div class="page">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 padding30-sides">
				<?=$this->element('userBreadcrumbs')?>
				<?=$this->Notice->show()?>
				<div class="panel">
					<div class="padding30-col">
						<div class="col-md-12">
							<h5><?=__('Advertisement Panel')?></h5>
						</div>
						<?=
							$this->element('adsPanelBoxes', array(
								'add' => array('controller' => 'ads', 'action' => 'add'),
								'buy' => array('controller' => 'ads_category_packages', 'action' => 'buy'),
								'assign' => array('controller' => 'ads', 'action' => 'assign'),
							))
						?>
						<div class="row padding30-col">
							<div class="col-md-12">
								<h5><?=$title?></h5>
								<?=$this->UserForm->create('Ad')?>
									<?php if($saveField == 'TargettedLocations' && Module::active('AccurateLocationDatabase')): ?>
										<?=$this->Locations->selector($options)?>
									<?php else: ?>
										<div class="col-md-8 col-md-offset-2 margin30-top">
											<fieldset class="form-group">
												<div class="input-group">
													<div class="input-group-addon"><?=__('Choose')?></div>
													<?=
														$this->UserForm->input($saveField, array(
															'type' => 'select',
															'class' => 'fancy form-control',
															'multiple' => 'multiple',
															'options' => $options,
															'style' => 'height: 300px;',
															'selected' => $selected, 
														))
														?>
												</div>
											</fieldset>
										</div>
									<?php endif; ?>
									<div class="col-md-8 col-md-offset-2 margin30-top">
										<div class="row">
											<div class="col-sm-12 text-xs-right">
												<button class="btn btn-primary"><?=__('Save')?></button>
											</div>
										</div>
									</div>
								</div>
								<?=$this->UserForm->end()?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
