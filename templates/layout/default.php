<?php
/**
 * @var \App\View\AppView $this
 * @var bool $hideSidebar
 */

$this->extend('DataCenter.default');

if (!($hideSidebar ?? false)) {
    $this->assign('sidebar', $this->element('sidebar'));
}
?>

<?php $this->start('site_title'); ?>
    <h1>
        <a href="/">
            <img src="/img/banner.png" alt="Collect | Analyze | Display" />
        </a>
    </h1>
<?php $this->end(); ?>

<?= $this->fetch('content') ?>
