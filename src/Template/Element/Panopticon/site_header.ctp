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
