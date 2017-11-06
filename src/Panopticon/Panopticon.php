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
            'deploy' => [
                'title' => 'CBER Deploy-bot',
                'production' => 'http://deploy.cberdata.org',
                'development' => 'http://deploy.localhost'
            ],
            'economicIndicators' => [
                'title' => 'Economic Indicators',
                'production' => 'http://indicators.cberdata.org',
                'development' => 'http://indicators.localhost'
            ],
            'honest-pledge' => [
                'title' => 'Honest Muncie Pledge',
                'production' => 'http://pledge.honestmuncie.org',
                'development' => 'http://honestpledge.localhost'
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
            'tax-calculator' => [
                'title' => 'Tax Savings Calculator',
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
            // CakePHP 2 versions of sites that have been upgraded to CakePHP 3
            'ice_miller',
            'taxCalculator'
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
        $orgName = 'BallStateCBER';
        $client->authenticate($token, '', $method);

        // Loop through all of BallStateCBER's repos
        /** @var \Github\Api\Organization $org */
        $org = $client->api('organization');
        $repos = $org->repositories($orgName);
        /** @var \Github\Api\Repo $apiRepo */
        $apiRepo = $client->api('repo');
        foreach ($repos as $i => $repo) {
            // Figure out what branches this repo has
            $branches = $apiRepo->branches($orgName, $repo['name']);
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
                $repos[$i]['branches'][] = $branch['name'];
            }

            // Determine which branch the master branch should be compared to
            $baseBranch = $hasDevBranch ? 'development' : null;
            if ($hasMasterBranch && ! empty($extraBranches)) {
                $freshestBranch = null;
                $updated = null;
                if ($hasDevBranch) {
                    $devCommit = $apiRepo->commits()->show($orgName, $repo['name'], $devSha);
                    $freshestBranch = 'development';
                    $updated = $devCommit['commit']['committer']['date'];
                }
                foreach ($extraBranches as $branchName => $branchSha) {
                    $commit = $apiRepo->commits()->show($orgName, $repo['name'], $branchSha);
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
                $compare = $apiRepo->commits()->compare($orgName, $repo['name'], $baseBranch, 'master');
                switch ($compare['status']) {
                    case 'identical':
                        $status = $this->getGlyphicon('ok-sign', 'Identical');
                        break;
                    case 'ahead':
                        $aheadBranch = $baseBranch ? " of $baseBranch" : '';
                        $title = 'Ahead' . $aheadBranch . ' for some reason';
                        $status = $this->getGlyphicon('circle-arrow-right', $title);
                        $status .= ' ' . $compare['ahead_by'];
                        break;
                    case 'behind':
                        $behindBranch = $baseBranch ? " $baseBranch" : '';
                        $status = $this->getGlyphicon('circle-arrow-left', 'Behind' . $behindBranch);
                        $status .= ' ' . $compare['behind_by'];
                        break;
                    default:
                        $status = $this->getGlyphicon('question-sign', 'Unexpected status');
                }
            } else {
                $status = '<span class="na">N/A</a>';
            }
            $repos[$i]['master_status'] = $status;
        }

        // Sort by last push
        $sortedRepos = [];
        foreach ($repos as $i => $repo) {
            $key = $repo['pushed_at'];
            if (isset($sortedRepos[$key])) {
                $key .= $i;
            }
            $sortedRepos[$key] = $repo;
        }
        krsort($sortedRepos);
        $repos = $sortedRepos;

        return $repos;
    }

    /**
     * Returns a <span> tag for the specified glyphicon
     *
     * @param string $class Full class will be "glyphicon glyphicon-$class"
     * @param string $title For title attribute
     * @return string
     */
    private function getGlyphicon($class, $title = '')
    {
        return '<span class="glyphicon glyphicon-' . $class . '" title="' . $title . '"></span>';
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

    /**
     * Returns whether or not the specified site is auto-deployed
     *
     * @param string $siteName GitHub repo name, technically
     * @return bool
     */
    public function isAutoDeployed($siteName)
    {
        $url = 'https://deploy.cberdata.org/check.php?site=' . $siteName;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore SSL errors
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
