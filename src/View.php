<?php

namespace Vcampitelli\Slim\View;

use Slim\App;
use Psr\Http\Message\ResponseInterface;

/**
 * Simple class to handle view rendering and helper plugins
 *
 * @since 0.1.0
 */
class View extends ViewRenderer
{
    /**
     * Layout view
     *
     * @var string
     */
    protected $title = null;

    /**
     * Style helper
     *
     * @var Helper\HelperStyle
     */
    protected $helperStyle = null;

    /**
     * Script helper
     *
     * @var Helper\HelperScript
     */
    protected $helperScript = null;

    /**
     * Custom renderer constructor
     *
     * @param App       $app            Slim application object
     * @param string    $templatePath   Views path
     * @param string    $layoutPath     Layouts path
     */
    public function __construct(App $app, $templatePath = '', $layoutPath = '')
    {
        $this->helperStyle = new Helper\HelperStyle();
        $this->helperScript = new Helper\HelperScript();

        parent::__construct($app, $templatePath, $layoutPath);
    }

    /**
     * Sets layout title
     *
     * @param  string $value Layout title
     *
     * @return self
     */
    public function setTitle($value)
    {
        $this->title = (string) $value;
        return $this;
    }

    /**
     * Return body layout attributes
     *
     * @return array
     */
    protected function getBodyAttributes()
    {
        return parent::getBodyAttributes() + [
            'title'      => $this->title,
            'stylesheet' => $this->helperStyle,
            'script'     => $this->helperScript
        ];
    }

    /**
     * Returns style helper
     *
     * @return Helper\HelperStyle
     */
    public function style()
    {
        return $this->helperStyle;
    }

    /**
     * Returns script helper
     *
     * @return Helper\HelperScript
     */
    public function script()
    {
        return $this->helperScript;
    }
}
