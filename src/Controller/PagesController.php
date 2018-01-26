<?php
namespace DataCenter\Controller;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use DataCenter\Controller\AppController;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow();
    }

    /**
     * Clears the cache
     *
     * @return void
     */
    public function clearCache()
    {
        $this->set(['result' => Cache::clear()]);
        $this->viewBuilder()->layout('simple');
    }
}
