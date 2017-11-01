<h1 class="page_title">
	<?= $titleForLayout ?>
</h1>

<?php $this->Html->css('overview', ['block' => 'css']); ?>

<?php if (empty($repositories)): ?>
	<p>
		No <a href="https://github.com/BallStateCBER">BallStateCBER GitHub repositories</a> found.
	</p>
<?php else: ?>
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
				<?php if ($isLocalhost): ?>
					<th>
						Status
						<br />
						(Dev)
					</th>
					<th>
						Status
						<br />
						(Production)
					</th>
				<?php else: ?>
					<th>
						Status
					</th>
				<?php endif; ?>
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
							<?php foreach (['development', 'production'] as $server): ?>
								<?php if (isset($sites[$repo['name']][$server])): ?>
									<li>
										<a href="<?= $sites[$repo['name']][$server] ?>">
											<?= ucwords($server) ?>
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
					<?php foreach ($servers as $server): ?>
						<?php
							$url = isset($sites[$repo['name']][$server]) ? $sites[$repo['name']][$server] : null;
						?>
						<?php if ($url): ?>
							<td class="check_status" data-url="<?= $url ?>" data-server="<?= $server ?>">

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
<?php endif; ?>

<?php $this->Html->script('overview', ['block' => 'scriptBottom']); ?>
<?php $this->append('buffered'); ?>
    dataCenterOverview.init();
<?php $this->end(); ?>
