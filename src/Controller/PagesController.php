<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Github\Client;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Displays the home page
     *
     * @return void
     */
    public function home()
    {
        $this->set([
            'title_for_layout' => ''
        ]);
    }

    /**
     * Displays PHP configuration
     *
     * @return void
     */
    public function phpinfo()
    {
        $this->set('title_for_layout', 'PHP Info');
    }

    /**
     * This simply refreshes the cached information about the latest
     * release from the Projects and Publications page.
     * AppController::__getLatestRelease() is called in
     * AppController::beforeRender(), so just removing what's cached
     * will cause the data to be re-imported automatically.
     *
     * @return void
     */
    public function refreshLatestRelease()
    {
        Cache::write('latest_release', []);
        $this->viewBuilder()->setLayout('ajax');

        // ReleasesController::__updateDataCenterHome() in Projects and Publications returns TRUE
    }

    /**
     * View for the CBER Website Panopticon
     *
     * @return void
     */
    public function overview()
    {
        $sites = $this->getSiteDetails();
        $retired = $this->getRetiredSites();
        $repositories = $this->getReposFromGitHub();

        // Filter out retired sites
        foreach ($repositories as $i => $repository) {
            if (in_array($repository['name'], $retired)) {
                unset($repositories[$i]);
                continue;
            }
        }

        $isLocalhost = $this->isLocalhost();

        $this->set([
            'titleForLayout' => 'CBER Website Panopticon',
            'repositories' => $repositories,
            'sites' => $sites,
            'isLocalhost' => $isLocalhost,
            'servers' => $isLocalhost ? ['development', 'production'] : ['production'],
            'retired' => $retired
        ]);
    }

    /**
     * Terms-of-service page
     *
     * @return void
     */
    public function terms()
    {
        $this->set('title_for_layout', 'Terms of Service');
    }

    /**
     * Returns the HTTP response for the provided URL
     *
     * @param string $url URL to check
     * @return mixed
     */
    private function getSiteStatus($url)
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
     * Shows the HTTP status code and debug status of the provided URL
     *
     * @return void
     */
    public function statuscheck()
    {
        $url = $this->request->getQuery('url');
        $result = $this->getSiteStatus($url);
        $this->set('result', [
            'status' => substr($result, 0, strpos($result, "\n")),
            'debug' => stripos($result, 'debug-kit-toolbar') !== false
        ]);
        $this->viewBuilder()->setLayout('json');
    }

    /**
     * Returns an array of repo details, indexed by the GitHub repo name
     *
     * @return array
     */
    private function getSiteDetails()
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
            'projects' => [
                'title' => 'CBER Projects and Publications',
                'production' => 'http://projects.cberdata.org',
                'development' => 'http://projects.localhost'
            ],
            'roundtable' => [
                'title' => 'BSU Roundtable (CakePHP 2)'
            ],
            'taxCalculator' => [
                'title' => 'Tax Savings Calculator',
                'production' => 'http://tax-comparison.cberdata.org',
                'development' => 'http://tax-calculator.localhost'
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
    private function getRetiredSites()
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
    private function getReposFromGitHub()
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
     * Returns whether or not the webpage is currently being viewed on a localhost server
     *
     * @return bool
     */
    private function isLocalhost()
    {
        $pos = stripos(env('SERVER_NAME'), 'localhost');
        $snLen = strlen(env('SERVER_NAME'));
        $lhLen = strlen('localhost');

        return ($pos !== false && $pos == ($snLen - $lhLen));
    }

    /**
     * Receives a message via POST and sends it to cber.slack.com
     *
     * @return void
     */
    public function slack()
    {
        if (! $this->request->is('post')) {
            return;
        }

        $msg = [
            $this->request->getData('hostname'),
            $this->request->getData('subject'),
            $this->request->getData('body')
        ];
        $data = 'payload=' . json_encode([
                'channel' => '#server',
                'text' => implode("\n", $msg),
                'icon_emoji' => ':robot_face:',
                'username' => 'CBER Web Server'
            ]);

        // You can get your webhook endpoint from your Slack settings
        $url = Configure::read('slack_webhook_url');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}
