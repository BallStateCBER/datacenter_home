<?php
namespace App\Panopticon;

use Cake\Core\Configure;
use Cake\Utility\Hash;
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
                'production' => 'https://brownfield.cberdata.org',
                'development' => 'https://brownfield.localhost/'
            ],
            'brownfields-updater' => [
                'title' => 'Brownfield Grant Writers\' Tool Data Importer'
            ],
            'cber-data-grabber' => [
                'title' => 'CBER Data Grabber'
            ],
            'commentaries' => [
                'title' => 'Weekly Commentaries',
                'production' => 'https://commentaries.cberdata.org',
                'development' => 'https://commentaries.localhost'
            ],
            'commentaries-cake3' => [
                'title' => 'Weekly Commentaries (CakePHP 3)',
                'production' => 'https://cake3.commentaries.cberdata.org'
            ],
            'community-asset-inventory' => [
                'title' => 'Community Asset Inventory (CakePHP 2)',
                'development' => 'https://qop.localhost'
            ],
            'community-asset-inventory-cakephp3' => [
                'title' => 'Community Asset Inventory (CakePHP 3)',
                'production' => 'https://cair.cberdata.org',
                'staging' => 'https://staging.cair.cberdata.org',
                'development' => 'https://asset.localhost'
            ],
            'conexus' => [
                'title' => 'Conexus Indiana Report Card',
                'production' => 'https://conexus.cberdata.org',
                'development' => 'https://conexus.localhost'
            ],
            'county-profiles' => [
                'title' => 'County Profiles',
                'production' => 'https://profiles.cberdata.org',
                'development' => 'https://profiles.localhost'
            ],
            'county-profiles-updater' => [
                'title' => 'County Profiles Updater'
            ],
            'datacenter-home' => [
                'title' => 'CBER Data Center Home (CakePHP 3)',
                'production' => 'https://cberdata.org',
                'staging' => 'https://staging.home.cberdata.org',
                'development' => 'https://dchome3.localhost'
            ],
            'datacenter-skeleton' => [
                'title' => 'CBER Data Center Website Skeleton'
            ],
            'datacenter-plugin-cakephp3' => [
                'title' => 'Data Center Plugin (CakePHP 3)'
            ],
            'deploy' => [
                'title' => 'CBER Deploy-bot',
                'production' => 'https://deploy.cberdata.org',
                'development' => 'https://deploy.localhost'
            ],
            'economic-indicators' => [
                'title' => 'Economic Indicators',
                'production' => 'https://indicators.cberdata.org',
                'development' => 'https://indicators.localhost'
            ],
            'honest-pledge' => [
                'title' => 'Honest Muncie Pledge',
                'production' => 'https://pledge.honestmuncie.org',
                'development' => 'https://honestpledge.localhost'
            ],
            'ice-miller-cakephp3' => [
                'title' => 'Ice Miller / EDGE Articles',
                'production' => 'https://icemiller.cberdata.org',
                'development' => 'https://icemiller3.localhost'
            ],
            'mfg-scr-crd' => [
                'title' => 'Manufacturing Scorecard'
            ],
            'muncie-musicfest2' => [
                'title' => 'Muncie MusicFest (CakePHP 3)',
                'production' => 'https://munciemusicfest.com',
                'development' => 'https://mmf.localhost'
            ],
            'muncie-events' => [
                'title' => 'Muncie Events (CakePHP 2)',
                'production' => 'https://muncieevents.com',
                'staging' => 'https://staging.muncieevents.com',
                'development' => 'https://muncie-events.localhost'
            ],
            'muncie-events3' => [
                'title' => 'Muncie Events (CakePHP 3)',
                'production' => 'https://cake3.muncieevents.com',
                'staging' => 'https://staging.cake3.muncieevents.com',
                'development' => 'https://muncieevents3.localhost'
            ],
            'muncie-events-api' => [
                'title' => 'Muncie Events API',
                'production' => 'https://api.muncieevents.com',
                'staging' => 'https://staging.api.muncieevents.com',
                'development' => 'https://meapi.localhost'
            ],
            'notes' => [
                'title' => 'CBER Web Development Notes'
            ],
            'projects' => [
                'title' => 'CBER Projects and Publications (CakePHP 2)',
                'production' => 'https://projects.cberdata.org',
                'development' => 'https://projects.localhost'
            ],
            'projects-cakephp3' => [
                'title' => 'CBER Projects and Publications (CakePHP 3)',
                'production' => 'https://projects3.cberdata.org',
                'staging' => 'https://staging.projects3.cberdata.org',
                'development' => 'https://projects3.localhost'
            ],
            'roundtable' => [
                'title' => 'BSU Roundtable (CakePHP 2)'
            ],
            'tax-calculator' => [
                'title' => 'Tax Savings Calculator',
                'production' => 'https://tax-comparison.cberdata.org',
                'staging' => 'https://staging.tax-comparison.cberdata.org',
                'development' => 'https://tax-calculator3.localhost'
            ],
            'datacenter-plugin' => [
                'title' => 'Data Center Plugin (CakePHP 2)'
            ],
            'data-center-template' => [
                'title' => 'Data Center Template'
            ],
            'GoogleCharts' => [
                'title' => 'Google Charts Plugin for CakePHP (fork)'
            ],
            'cri' => [
                'title' => 'Community Readiness Initiative',
                'production' => 'https://cri.cberdata.org',
                'staging' => 'https://staging.cri.cberdata.org',
                'development' => 'https://cri.localhost'
            ],
            'utilities' => [
                'title' => 'CBER Utilities'
            ],
            'student-work' => [
                'title' => 'Student Work Tracker',
                'production' => 'https://studentwork.cberdata.org',
                'staging' => 'https://staging.studentwork.cberdata.org'
            ],
            'school-rankings' => [
                'title' => 'School Rankings',
                'production' => 'https://school.cberdata.org',
            ],
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
            'datacenter-home-cakephp2',
            'ice-miller-cakephp2',
            'tax-calculator-cakephp2'
        ];
    }

    /**
     * Returns an authenticated GitHub API client object
     *
     * @return Client
     */
    private function getGithubApiClient()
    {
        $client = new Client();
        $token = Configure::read('github_api_token');
        $method = Client::AUTH_HTTP_TOKEN;
        $client->authenticate($token, '', $method);

        return $client;
    }

    /**
     * Returns an array of the branch names for the specified repo
     *
     * @param Client $client GitHub API client
     * @param string $orgName Organization name
     * @param string $repoName Repo name
     * @return array
     */
    private function getBranches($client, $orgName, $repoName)
    {
        /** @var \Github\Api\Repo $apiRepo */
        $apiRepo = $client->api('repo');
        $branches = $apiRepo->branches($orgName, $repoName);

        return Hash::extract($branches, '{n}.name');
    }

    /**
     * Returns the $repos array sorted by last push, descending
     *
     * @param array $repos Repositories
     * @return array
     */
    private function sortReposByPush($repos)
    {
        $sortedRepos = [];
        foreach ($repos as $i => $repo) {
            $key = $repo['pushed_at'];
            if (isset($sortedRepos[$key])) {
                $key .= $i;
            }
            $sortedRepos[$key] = $repo;
        }
        krsort($sortedRepos);

        return $sortedRepos;
    }

    /**
     * Returns an array of the BallStateCBER organization's repositories, sorted by last push
     *
     * @return array
     */
    public function getReposFromGitHub()
    {
        // Get all repos
        $client = $this->getGithubApiClient();
        /** @var \Github\Api\Organization $org */
        $org = $client->api('organization');
        $orgName = 'BallStateCBER';
        $pageNum = 1;
        $repos = [];
        do {
            $page = $org->repositories($orgName, 'all', $pageNum);
            $repos = array_merge($repos, $page);
            $pageNum++;
        } while (!empty($page));
        $repos = $this->sortReposByPush($repos);

        // Add branches and master_status to each repo
        foreach ($repos as $i => $repo) {
            $repos[$i]['branches'] = $this->getBranches($client, $orgName, $repo['name']);
            $hasDevBranch = array_search('development', $repos[$i]['branches']) !== false;
            $baseBranch = $hasDevBranch ? 'development' : null;
            $repos[$i]['master_status'] = $this->getMasterBranchStatus($client, $baseBranch, $orgName, $repo['name']);
        }

        return $repos;
    }

    /**
     * Returns an HTML string representing the master branch's status relative to $baseBranch (e.g. behind by X commits)
     *
     * @param Client $client GitHub API client
     * @param string $baseBranch Branch to compare master branch to
     * @param string $orgName GitHub organization name
     * @param string $repoName Repo name
     * @return string
     */
    private function getMasterBranchStatus($client, $baseBranch, $orgName, $repoName)
    {
        if (!$baseBranch) {
            return '<span class="na">N/A</a>';
        }

        /** @var \Github\Api\Repo $apiRepo */
        $apiRepo = $client->api('repo');
        $compare = $apiRepo->commits()->compare($orgName, $repoName, $baseBranch, 'master');
        switch ($compare['status']) {
            case 'identical':
                return $this->getGlyphicon('ok-sign', 'Identical');
            case 'ahead':
                $aheadBranch = $baseBranch ? " of $baseBranch" : '';
                $title = 'Ahead' . $aheadBranch . ' for some reason';

                return $this->getGlyphicon('circle-arrow-right', $title) . ' ' . $compare['ahead_by'];
            case 'behind':
                $behindBranch = $baseBranch ? " $baseBranch" : '';

                return
                    $this->getGlyphicon('circle-arrow-left', 'Behind' . $behindBranch) .
                    ' ' . $compare['behind_by'];
            default:
                return $this->getGlyphicon('question-sign', 'Unexpected status');
        }
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
