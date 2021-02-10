<?php
/**
 * @var \App\View\AppView $this
 */

$this->extend('DataCenter.default');

// If you have a /templates/elements/sidebar.php file
$this->assign('sidebar', $this->element('sidebar'));
?>

<?php $this->start('site_title'); ?>
    <h1 class="text">
        <a href="/">
            Collect | Analyze | Display
        </a>
    </h1>
<?php $this->end(); ?>

<div id="content">
    <?= $this->fetch('content') ?>
</div>
