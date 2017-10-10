<?php
namespace App\Panopticon;

use Cake\Core\Configure;
use Github\Client;

class Panopticon
{
    /**
     * Returns an array of repo details, indexed by the GitHub repo name
     *
     * @return array
     */
    public function getSiteDetails()
    {
        return [
            'brownfield' => [
                'title' => 'Brownfield Grant Writers\' Tool',
                'production' => 'http://brownfield.cberdata.org',
                'development' => 'http://brownfield.localhost/'
            ],
            'brownfields-updater' => [
                'title' => 'Brownfield Grant Writers\' Tool Data Importer'
            ],
            'cber-data-grabber' => [
                'title' => 'CBER Data Grabber'
            ],
            'commentaries' => [
                'title' => 'Weekly Commentaries',
                'production' => 'http://commentaries.cberdata.org',
                'development' => 'http://commentaries.localhost'
            ],
            'commentaries_cake3' => [
                'title' => 'Weekly Commentaries (CakePHP 3)'
            ],
            'communityAssetInventory' => [
                'title' => 'Community Asset Inventory',
                'production' => 'http://asset.cberdata.org',
                'development' => 'http://qop.localhost'
            ],
            'conexus' => [
                'title' => 'Conexus Indiana Report Card',
                'production' => 'http://conexus.cberdata.org',
                'development' => 'http://conexus.localhost'
            ],
            'countyProfiles' => [
                'title' => 'County Profiles',
                'production' => 'http://profiles.cberdata.org',
                'development' => 'http://profiles.localhost'
            ],
            'county-profiles-updater' => [
                'title' => 'County Profiles Updater'
            ],
            'datacenter_home' => [
                'title' => 'CBER Data Center Home (CakePHP 3)',
                'production' => 'http://cberdata.org',
                'development' => 'http://dchome3.localhost'
            ],
            'datacenter_skeleton' => [
                'title' => 'CBER Data Center Website Skeleton'
            ],
            'dataCenterHome' => [
                'title' => 'CBER Data Center Home (CakePHP 2)',
                'production' => 'http://cberdata.org',
                'development' => 'http://dchome.localhost'
            ],
            'datacenter-plugin-cakephp3' => [
                'title' => 'Data Center Plugin (CakePHP 3)'
            ],
            'economicIndicators' => [
                'title' => 'Economic Indicators',
                'production' => 'http://indicators.cberdata.org',
                'development' => 'http://indicators.localhost'
            ],
            'ice-miller-cakephp3' => [
                'title' => 'Ice Miller / EDGE Articles',
                'production' => 'http://icemiller.cberdata.org',
                'development' => 'http://icemiller3.localhost'
            ],
            'mfg-scr-crd' => [
                'title' => 'Manufacturing Scorecard'
            ],
            'muncieMusicFest2' => [
                'title' => 'Muncie MusicFest (CakePHP 3)',
                'production' => 'http://munciemusicfest.com',
                'development' => 'http://mmf.localhost'
            ],
            'muncie_events' => [
                'title' => 'Muncie Events (CakePHP 2)',
                'production' => 'http://muncieevents.com',
                'development' => 'http://muncie-events.localhost'
            ],
            'muncie_events3' => [
                'title' => 'Muncie Events (CakePHP 3)'
            ],
            'notes' => [
                'title' => 'CBER Web Development Notes'
            ],
            'projects' => [
                'title' => 'CBER Projects and Publications',
                'production' => 'http://projects.cberdata.org',
                'development' => 'http://projects.localhost'
            ],
            'roundtable' => [
                'title' => 'BSU Roundtable (CakePHP 2)'
            ],
            'taxCalculator' => [
                'title' => 'Tax Savings Calculator (CakePHP 2)',
                'production' => 'http://tax-comparison.cberdata.org',
                'development' => 'http://tax-calculator.localhost'
            ],
            'tax-calculator' => [
                'title' => 'Tax Savings Calculator (CakePHP 3)',
                'production' => 'http://tax-comparison.cberdata.org',
                'development' => 'http://tax-calculator3.localhost'
            ],
            'dataCenterPlugin' => [
                'title' => 'Data Center Plugin (CakePHP 2)'
            ],
            'dataCenterTemplate' => [
                'title' => 'Data Center Template'
            ],
            'GoogleCharts' => [
                'title' => 'Google Charts Plugin for CakePHP (fork)'
            ],
            'cri' => [
                'title' => 'Community Readiness Initiative',
                'production' => 'https://cri.cberdata.org',
                'development' => 'https://cri.localhost'
            ],
            'utilities' => [
                'title' => 'CBER Utilities'
            ],
            'whyarewehere' => [
                'title' => 'Why Are We Here?'
            ]
        ];
    }

    /**
     * Returns an array of retired sites, referenced by their GitHub repo names
     *
     * @return array
     */
    public function getRetiredSites()
    {
        return [
            'ice_miller'
        ];
    }

    /**
     * Returns an array of the BallStateCBER organization's repositories, sorted by last push
     *
     * @return array
     */
    public function getReposFromGitHub()
    {
        // Connect to GitHub API
        $client = new Client();
        $token = Configure::read('github_api_token');
        $method = Client::AUTH_HTTP_TOKEN;
        $username = 'BallStateCBER';
        $client->authenticate($token, '', $method);

        // Loop through all of BallStateCBER's repos
        /** @var \Github\Api\CurrentUser $user */
        $user = $client->api('user');
        $repositories = $user->repositories($username);
        /** @var \Github\Api\Repo $repo */
        $repo = $client->api('repo');
        foreach ($repositories as $i => $repository) {
            // Figure out what branches this repo has
            $branches = $repo->branches($username, $repository['name']);
            $hasMasterBranch = false;
            $hasDevBranch = false;
            $devSha = null;
            $extraBranches = [];
            foreach ($branches as $branch) {
                if ($branch['name'] == 'master') {
                    $hasMasterBranch = true;
                } elseif ($branch['name'] == 'development') {
                    $hasDevBranch = true;
                    $devSha = $branch['commit']['sha'];
                } else {
                    $extraBranches[$branch['name']] = $branch['commit']['sha'];
                }
                $repositories[$i]['branches'][] = $branch['name'];
            }

            // Determine which branch the master branch should be compared to
            $baseBranch = $hasDevBranch ? 'development' : null;
            if ($hasMasterBranch && ! empty($extraBranches)) {
                $freshestBranch = null;
                $updated = null;
                if ($hasDevBranch) {
                    $devCommit = $repo->commits()->show($username, $repository['name'], $devSha);
                    $freshestBranch = 'development';
                    $updated = $devCommit['commit']['committer']['date'];
                }
                foreach ($extraBranches as $branchName => $branchSha) {
                    $commit = $repo->commits()->show($username, $repository['name'], $branchSha);
                    if ($commit['commit']['committer']['date'] > $updated) {
                        $freshestBranch = $branchName;
                        $updated = $commit['commit']['committer']['date'];
                    }
                }
                $baseBranch = $freshestBranch;
            }

            // Determine how ahead/behind master is vs. most recently-updated non-master branch
            $canCompare = $hasMasterBranch && $baseBranch;
            if ($canCompare) {
                $compare = $repo->commits()->compare($username, $repository['name'], $baseBranch, 'master');
                switch ($compare['status']) {
                    case 'identical':
                        $repositories[$i]['master_status'] = '<span class="glyphicon glyphicon-ok-sign" title="Identical"></span>';
                        break;
                    case 'ahead':
                        $aheadBranch = $baseBranch ? " of $baseBranch" : '';
                        $repositories[$i]['master_status'] = '<span class="glyphicon glyphicon-circle-arrow-right" title="Ahead' . $aheadBranch . ' for some reason"></span> ';
                        $repositories[$i]['master_status'] .= $compare['ahead_by'];
                        break;
                    case 'behind':
                        $behindBranch = $baseBranch ? " $baseBranch" : '';
                        $repositories[$i]['master_status'] = '<span class="glyphicon glyphicon-circle-arrow-left" title="Behind' . $behindBranch . '"></span> ';
                        $repositories[$i]['master_status'] .= $compare['behind_by'];
                        break;
                    default:
                        $repositories[$i]['master_status'] = '<span class="glyphicon glyphicon-question-sign" title="Unexpected status"></span>';
                }
            } else {
                $repositories[$i]['master_status'] = '<span class="na">N/A</a>';
            }
        }

        // Sort by last push
        $sortedRepos = [];
        foreach ($repositories as $i => $repository) {
            $key = $repository['pushed_at'];
            if (isset($sortedRepos[$key])) {
                $key .= $i;
            }
            $sortedRepos[$key] = $repository;
        }
        krsort($sortedRepos);
        $repositories = $sortedRepos;

        return $repositories;
    }

    /**
     * Returns the HTTP response for the provided URL
     *
     * @param string $url URL to check
     * @return mixed
     */
    public function getSiteStatus($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore SSL errors
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
