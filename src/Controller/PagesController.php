<?php
declare(strict_types=1);

namespace App\Controller;

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
}
