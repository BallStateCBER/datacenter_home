<?php
/**
 * @var \stdClass $latestRelease
 */

$releaseIsValid = ($latestRelease->title ?? false)
    && ($latestRelease->released ?? false)
    && ($latestRelease->url ?? false);

if ($releaseIsValid) {
    $linkContent = sprintf(
        '<span class="title">%s</span><span class="released">Published %s</span>',
        $latestRelease->title,
        date('F j, Y', strtotime($latestRelease->released))
    );
    if ($latestRelease->graphic ?? false) {
        $linkContent .= sprintf(
            '<br /><img src="%s" alt="Accompanying image for release" />',
            $latestRelease->graphic
        );
    }
}
?>

<?php if ($releaseIsValid): ?>
    <section id="latest-release">
        <h1>
            Latest Release
        </h1>
        <?= $this->Html->link(
            $linkContent,
            $latestRelease->url,
            ['escape' => false]
        ) ?>
        <br />
        <a href="https://projects.cberdata.org">
            Browse Projects and Publications
            <i class="fas fa-arrow-circle-right"></i>
        </a>
    </section>
<?php endif; ?>

<section id="twitter">
    <h1 class="sr-only">
        <a href="https://twitter.com/BallStateCBER">@BallStateCBER</a>
    </h1>
    <a class="twitter-timeline"  href="https://twitter.com/BallStateCBER"  data-widget-id="351709426740252672">Tweets by @BallStateCBER</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</section>
