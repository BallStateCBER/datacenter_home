<?php
    $showSite = function ($site) {
        $sites = [
            'Economic Indicators' => [
                'url' => 'https://indicators.cberdata.org/',
                'img' => '/img/sites/Indicators.jpg',
                'about' => 'Regularly updated data sets from primary sources for the U.S., Indiana, and Indiana\'s metro areas.',
            ],
            'Indiana Community Asset Inventory and Rankings' => [
                'url' => 'https://cair.cberdata.org/',
                'img' => '/img/sites/CAIR.jpg',
                'about' => 'An objective, data-focused assessment of the factors that influence the quality of life and the economic conditions within each county.',
            ],
            'Manufacturing and Logistics Report' => [
                'url' => 'https://mfgscorecard.cberdata.org/',
                'img' => '/img/sites/Conexus.png',
                'about' => 'This report grades state performance on a number of factors that affect the health of the manufacturing industry.',
            ],
            'Cost-of-Living Index Calculator' => [
                'url' => 'http://coli.org/calculator/in/muncie/calculator.asp',
                'img' => '/img/sites/Coli.jpg',
                'about' => 'The Council for Community and Economic Research (C2ER) compiles average pricing data to compare average living costs in cities across the U.S.',
            ],
            'Weekly Commentary' => [
                'url' => 'https://commentaries.cberdata.org/',
                'img' => '/img/sites/Commentary.jpg',
                'about' => 'Ball State economist Michael J. Hicks, Ph.D. delivers his personal perspective on current issues in business, government, and society.',
            ],
        ];
        $retval = '<img src="' . $sites[$site]['img'] . '" alt="' . $sites[$site]['url']. '" />';
        $retval = '<a href="' . $sites[$site]['url'] . '">' . $retval . '</a>';
        $retval .= $sites[$site]['about'];

        return '<div class="site col-lg pb-4">' . $retval . '</div>';
    };
?>

<p>
    The CBER Data Center makes data collection simple, visual, and easily accessible.  We've
    collected the latest data sets from trusted primary sources, including
    <a href="https://www.bls.gov">BLS</a>,
    <a href="https://www.bea.gov">BEA</a>, and the
    <a href="https://www.census.gov">U.S. Census Bureau</a>. Our economic web tools transform
    overwhelming walls of numbers into a format that is organized, attractive, and useful
    for people ranging from grant writers and economic developers to community leaders and informed citizens.
</p>

<section class="websites">
    <h1 class="sr-only">
        Websites
    </h1>
    <div class="row">
        <?= $showSite('Economic Indicators') ?>
        <?= $showSite('Indiana Community Asset Inventory and Rankings') ?>
    </div>
    <div class="row">
        <?= $showSite('Manufacturing and Logistics Report') ?>
        <?= $showSite('Cost-of-Living Index Calculator') ?>
    </div>
    <div class="row">
        <?= $showSite('Weekly Commentary') ?>
        <div class="col-lg pb-4">

        </div>
    </div>
</section>
