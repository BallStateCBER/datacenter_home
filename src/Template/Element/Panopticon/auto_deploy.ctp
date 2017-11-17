<table class="table">
    <thead>
        <tr>
            <th>
                Website / Software
            </th>
            <th>
                Auto
                <br />
                Deployed
            </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($repositories as $repo): ?>
            <tr>
                <td>
                    <?= $this->element('Panopticon/site_header', compact('repo')) ?>
                </td>
                <?php if (isset($sites[$repo['name']]['production'])): ?>
                    <td class="check-auto-deploy" data-site="<?= $repo['name'] ?>">

                    </td>
                <?php else: ?>
                    <td>
                        <span class="na">N/A</span>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
