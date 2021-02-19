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

<section class="navbar-light navbar-expand-md sidebar-collapse">
    <div class="row d-md-none">
        <div class="col">
            <h2 data-toggle="collapse" data-target="#twitter">
                Tweets by @BallStateCBER
            </h2>
        </div>
        <div class="col-2">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#twitter"
                    aria-controls="tags-list" aria-expanded="false" aria-label="Toggle Twitter feed">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
    <section id="twitter" class="collapse navbar-collapse">
        <h1 class="sr-only">
            <a href="https://twitter.com/BallStateCBER">@BallStateCBER</a>
        </h1>
        <a class="twitter-timeline" href="https://twitter.com/BallStateCBER?ref_src=twsrc%5Etfw">Tweets by BallStateCBER</a>
        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    </section>
</section>
