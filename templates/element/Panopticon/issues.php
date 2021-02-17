<?php
/** @var array $repositories */
?>

<table class="table panopticon-table">
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
        </tr>
    </thead>
    <tbody>
        <?php foreach ($repositories as $repo): ?>
            <tr>
                <td>
                    <?= $this->element('Panopticon/site_header', compact('repo')) ?>
                </td>
                <td>
                    <a href="<?= $repo['html_url'] ?>/issues" class="issues" data-repo="<?= $repo['name'] ?>">
                        <?= $repo['open_issues'] ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
