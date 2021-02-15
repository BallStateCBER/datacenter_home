<?php
/**
 * @var \App\View\AppView $this
 */

$this->extend('DataCenter.default');

// If you have a /templates/elements/sidebar.php file
$this->assign('sidebar', $this->element('sidebar'));
?>

<?php $this->start('site_title'); ?>
    <h1>
        <a href="/">
            <img src="/img/banner.png" alt="Collect | Analyze | Display" />
        </a>
    </h1>
<?php $this->end(); ?>

<?= $this->fetch('content') ?>
