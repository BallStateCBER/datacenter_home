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

use App\Panopticon\Panopticon;
use Cake\Cache\Cache;
use Cake\Core\Configure;

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
        $hiddenServerVars = [
            'SECURITY_SALT',
            'FULL_BASE_URL',
            'DB_DATABASE',
            'DB_USERNAME',
            'DB_PASSWORD',
            'COOKIE_ENCRYPTION_KEY',
            'GITHUB_API_TOKEN',
            'GOOGLE_ANALYTICS_ID',
            'SLACK_WEBHOOK_URL'
        ];
        foreach ($hiddenServerVars as $var) {
            if (array_key_exists($var, $_SERVER)) {
                unset($_SERVER[$var]);
            }
        }
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
            'titleForLayout' => 'CBER Website Panopticon',
            'repositories' => $repositories,
            'sites' => $sites,
            'isLocalhost' => $isLocalhost,
            'environments' => ['production', 'staging'] + ($isLocalhost ? ['development'] : []),
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
     * Shows the HTTP status code and debug status of the provided URL
     *
     * @return void
     */
    public function statuscheck()
    {
        $url = $this->request->getQuery('url');
        $panopticon = new Panopticon();
        $result = $panopticon->getSiteStatus($url);
        $this->set('result', [
            'status' => substr($result, 0, strpos($result, "\n")),
            'debug' => stripos($result, 'debug-kit-toolbar') !== false
        ]);
        $this->viewBuilder()->setLayout('json');
    }

    /**
     * Checks with deploy.cberdata.org to see whether the specified site gets auto-deployed
     *
     * @return void
     */
    public function autoDeployCheck()
    {
        $siteName = $this->request->getQuery('site');
        $panopticon = new Panopticon();
        $result = $panopticon->isAutoDeployed($siteName);
        $this->set('result', $result);
        $this->viewBuilder()->setLayout('json');
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
