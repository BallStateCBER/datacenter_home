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
                Master
                <br />Branch
            </th>
            <th>
                Last
                <br />
                Push
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
                    <?= $repo['master_status'] ?>
                </td>
                <td>
                    <?php
                        $timeAgo = $this->Time->timeAgoInWords($repo['pushed_at'], ['end' => '+10 year']);
                        $time_ago_split = explode(', ', $timeAgo);
                        $timeAgo = $time_ago_split[0];
                        $timeAgo = str_replace(' ago', '', $timeAgo);
                        list($number, $unit) = explode(' ', $timeAgo);
                        echo $number . substr($unit, 0, 1);
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
