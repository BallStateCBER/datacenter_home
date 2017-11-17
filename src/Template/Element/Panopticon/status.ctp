<table class="table">
    <thead>
        <tr>
            <th>
                Website / Software
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
                    <?= $this->element('Panopticon/site_header', compact('repo')) ?>
                </td>
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
