<?php if (empty($repositories)): ?>
    <p>
        No <a href="https://github.com/BallStateCBER">BallStateCBER GitHub repositories</a> found.
    </p>
<?php else: ?>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <?php foreach (['status', 'git', 'issues', 'auto-deploy'] as $n => $tab): ?>
                <li role="presentation" class="nav-item">
                    <a href="#<?= $tab ?>" aria-controls="<?= $tab ?>" role="tab" data-toggle="tab"
                       class="nav-link <?= ($n === 0) ? 'active' : '' ?>">
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
<?php endif; ?>

<?php $this->Html->script('panopticon', ['block' => 'script']); ?>
<?php $this->append('buffered'); ?>
    const panopticon = new Panopticon();
<?php $this->end(); ?>
