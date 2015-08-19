<?php
namespace DataCenter\Controller;

use DataCenter\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    public function phpinfo()
    {
        $this->layout = 'ajax';
    }

    public function clearCache()
    {
        $this->set(['result' => Cache::clear()]);
        $this->layout = 'simple';
    }
}
