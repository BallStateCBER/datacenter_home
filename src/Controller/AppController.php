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
use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('DataCenter.Flash');

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Csrf');
    }

    /**
     * Pulls the latest release from the Projects and Publications site
     *
     * @return mixed
     */
    protected function importLatestRelease()
    {
        // Development server
        if (stripos($_SERVER['SERVER_NAME'], 'localhost') !== false) {
            $url = 'https://projects.localhost/releases/latest';
            $streamOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ]
            ];
            $results = file_get_contents($url, false, stream_context_create($streamOptions));

        // Production server
        } else {
            $url = 'https://projects.cberdata.org/releases/latest';
            $results = file_get_contents($url);
        }

        return unserialize($results);
    }

    /**
     * Returns the (cached) most recent release from the Projects and Publications site
     *
     * @return mixed
     */
    protected function getLatestRelease()
    {
        $release = Cache::read('latest_release');
        if (empty($release['cached_time']) || $release['cached_time'] < strtotime('-1 day')) {
            $release = $this->importLatestRelease();
            if (! empty($release)) {
                $release['cached_time'] = time();
                Cache::write('latest_release', $release);
            }
        }

        return $release;
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        /* Note: These defaults are just to get started quickly with development
         * and should not be used in production. You should instead set "_serialize"
         * in each action as required. */
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }

        $this->set([
            'latestRelease' => $this->getLatestRelease()
        ]);
    }
}
