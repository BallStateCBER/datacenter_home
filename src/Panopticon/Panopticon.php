<?php
declare(strict_types=1);

namespace App\Panopticon;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use Github\Client;

class Panopticon
{
    /**
     * Panopticon constructor
     */
    public function __construct()
    {
        Configure::load('repos');
    }

    /**
     * Returns an array of repo details, indexed by the GitHub repo name
     *
     * @return array
     */
    public function getSiteDetails()
    {
        return Configure::read('repos');
    }

    /**
     * Returns an array of retired or otherwise ignorable GitHub repo names
     *
     * These sites won't be included in the panopticon list
     *
     * @return array
     */
    public function getIgnoredRepos()
    {
        return Configure::read('reposIgnored');
    }

    /**
     * Returns an authenticated GitHub API client object
     *
     * @return \Github\Client
     */
    private function getGithubApiClient(): Client
    {
        $client = new Client();
        $token = Configure::read('githubApiToken');
        $method = Client::AUTH_ACCESS_TOKEN;
        $client->authenticate($token, '', $method);

        return $client;
    }

    /**
     * Returns an array of the branch names for the specified repo
     *
     * @param \Github\Client $client GitHub API client
     * @param string $orgName Organization name
     * @param string $repoName Repo name
     * @return array|\ArrayAccess<int, string>
     */
    private function getBranches(Client $client, string $orgName, string $repoName)
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
    private function sortReposByPush(array $repos): array
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
    public function getReposFromGitHub(): array
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
     * @param \Github\Client $client GitHub API client
     * @param string|null $baseBranch Branch to compare master branch to
     * @param string $orgName GitHub organization name
     * @param string $repoName Repo name
     * @return string
     */
    private function getMasterBranchStatus(
        Client $client,
        ?string $baseBranch,
        string $orgName,
        string $repoName
    ): string {
        if (!$baseBranch) {
            return '<span class="na">N/A</a>';
        }

        /** @var \Github\Api\Repo $apiRepo */
        $apiRepo = $client->api('repo');
        $compare = $apiRepo->commits()->compare($orgName, $repoName, $baseBranch, 'master');
        switch ($compare['status']) {
            case 'identical':
                return $this->getIcon('fa-check-circle', 'Identical');
            case 'ahead':
                return $this->getIcon('fa-arrow-circle-right', "Ahead of $baseBranch for some reason")
                    . ' ' . $compare['ahead_by'];
            case 'behind':
                return $this->getIcon('fa-arrow-circle-left', "Behind $baseBranch")
                        . ' ' . $compare['behind_by'];
            default:
                return $this->getIcon('fa-question-sign', 'Unexpected status');
        }
    }

    /**
     * Returns an icon tag for the specified glyphicon
     *
     * @param string $class Full class will be "fas $class"
     * @param string $title For title attribute
     * @return string
     */
    private function getIcon(string $class, $title = ''): string
    {
        return sprintf('<i class="fas %s" title="%s"></i>', $class, $title);
    }

    /**
     * Returns the HTTP response for the provided URL
     *
     * @param string $url URL to check
     * @return string|bool
     */
    public function getSiteStatus(string $url)
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
    public function isAutoDeployed(string $siteName): bool
    {
        $url = 'https://deploy.cberdata.org/check.php?site=' . $siteName;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ignore SSL errors
        $result = curl_exec($ch);
        curl_close($ch);

        return $result !== false;
    }
}
