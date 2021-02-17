<?php
declare(strict_types=1);

namespace App\Controller;

use App\Panopticon\Panopticon;

/**
 * PagesController
 *
 * This controller will render views from templates/Pages/
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
        $this->set(['pageTitle' => '']);
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
     * View for the CBER Website Panopticon
     *
     * @return void
     */
    public function panopticon()
    {
        $panopticon = new Panopticon();
        $sites = $panopticon->getSiteDetails();
        $ignored = $panopticon->getIgnoredRepos();
        $repositories = $panopticon->getReposFromGitHub();

        // Filter out retired sites
        foreach ($repositories as $i => $repository) {
            if (in_array($repository['name'], $ignored)) {
                unset($repositories[$i]);
                continue;
            }
        }

        $isLocalhost = $this->isLocalhost();

        $this->set([
            'environments' => ['production', 'staging'] + ($isLocalhost ? ['development'] : []),
            'isLocalhost' => $isLocalhost,
            'pageTitle' => 'CBER Website Panopticon',
            'repositories' => $repositories,
            'sites' => $sites,
        ]);
    }

    /**
     * Shows the HTTP status code and debug status of the provided URL
     *
     * @return void
     */
    public function checkStatus()
    {
        $url = $this->request->getQuery('url');
        $result = (new Panopticon())->getSiteStatus($url);
        $this->set([
            '_serialize' => ['result'],
            'result' => [
                'status' => $result ? substr($result, 0, strpos($result, "\n")) : 'Error',
                'debug' => $result ? stripos($result, 'debug-kit-toolbar') !== false : false,
            ],
        ]);
    }
}
