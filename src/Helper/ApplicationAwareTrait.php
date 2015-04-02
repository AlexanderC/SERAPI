<?php
/**
 * Created by PhpStorm.
 * User: AlexanderC <alexanderc@pycoding.biz>
 * Date: 4/2/15
 * Time: 11:43
 */

namespace SERAPI\Helper;

use Silex\Application;


/**
 * Class ApplicationAwareTrait
 */
trait ApplicationAwareTrait
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @param Application $application
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
        return $this;
    }
}