<h1 class="page_title">
	<?= $titleForLayout ?>
</h1>

<?php $this->Html->css('overview', ['block' => 'css']); ?>

<?php if (empty($repositories)): ?>
	<p>
		No <a href="https://github.com/BallStateCBER">BallStateCBER GitHub repositories</a> found.
	</p>
<?php else: ?>
<div>
    <ul class="nav nav-tabs" role="tablist">
        <?php foreach (['status', 'git', 'issues', 'auto-deploy'] as $n => $tab): ?>
            <li role="presentation" <?php if ($n === 0): ?>class="active"<?php endif; ?>>
                <a href="#<?= $tab ?>" aria-controls="<?= $tab ?>" role="tab" data-toggle="tab">
                    <?= ucwords($tab) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="status">
            <?= $this->element('Panopticon/status') ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="git">
            <?= $this->element('Panopticon/git') ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="issues">
            <?= $this->element('Panopticon/issues') ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="auto-deploy">
            <?= $this->element('Panopticon/auto_deploy') ?>
        </div>
    </div>
</div>
<?php /*
<table class="table">
		<thead>
			<tr>
				<th>
					Website / Software
				</th>
				<th>
					Open
					<br />
					issues
				</th>
				<th>
					Master
					<br />Branch
				</th>
				<th>
					Last
					<br />
					Push
				</th>
                <th>
                    Auto
                    <br />
                    Deployed
                </th>
                <?php foreach ($environments as $environment): ?>
                    <th>
                        Status
                        <br />
                        (<?= $environment ?>)
                    </th>
                <?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($repositories as $repo): ?>
				<tr>
					<td>
						<?= isset($sites[$repo['name']]['title']) ? $sites[$repo['name']]['title'] : $repo['name'] ?>
						<br />
						<ul class="links">
							<li>
								<a href="<?= $repo['html_url'] ?>">
									Repo
								</a>
							</li>
							<?php foreach ($environments as $environment): ?>
								<?php if (isset($sites[$repo['name']][$environment])): ?>
									<li>
										<a href="<?= $sites[$repo['name']][$environment] ?>">
											<?= ucwords($environment) ?>
										</a>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</td>
					<td>
						<a href="<?= $repo['html_url'] ?>/issues" class="issues" data-repo="<?= $repo['name'] ?>">
							<?= $repo['open_issues'] ?>
						</a>
					</td>
					<td>
						<?= $repo['master_status'] ?>
					</td>
					<td>
						<?php
							$timeAgo = $this->Time->timeAgoInWords($repo['pushed_at'], [
								'end' => '+10 year'
							]);
							$time_ago_split = explode(', ', $timeAgo);
							$timeAgo = $time_ago_split[0];
                            $timeAgo = str_replace(' ago', '', $timeAgo);
                            list($number, $unit) = explode(' ', $timeAgo);
                            echo $number . substr($unit, 0, 1);
						?>
					</td>
                    <?php if (isset($sites[$repo['name']]['production'])): ?>
                        <td class="check-auto-deploy" data-site="<?= $repo['name'] ?>">

                        </td>
                    <?php else: ?>
                        <td>
                            <span class="na">N/A</span>
                        </td>
                    <?php endif; ?>
					<?php foreach ($environments as $environment): ?>
						<?php
							$url = isset($sites[$repo['name']][$environment])
                                ? $sites[$repo['name']][$environment]
                                : null;
						?>
						<?php if ($url): ?>
							<td class="check_status" data-url="<?= $url ?>" data-server="<?= $environment ?>">

							</td>
						<?php else: ?>
							<td>
								<span class="na">N/A</span>
							</td>
						<?php endif; ?>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
*/ ?>

<?php endif; ?>

<?php $this->Html->script('overview', ['block' => 'scriptBottom']); ?>
<?php $this->append('buffered'); ?>
    dataCenterOverview.init();
<?php $this->end(); ?>
