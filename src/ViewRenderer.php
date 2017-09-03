<?php

namespace Vcampitelli\Slim\View;

use Slim\App;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Simple class to handle view rendering and helper plugins
 *
 * @since 0.1.0
 */
class ViewRenderer extends PhpRenderer
{
    /**
     * Application object
     *
     * @var App
     */
    protected $app = null;

    /**
     * Default layout path
     *
     * @var string
     */
    protected $layoutPath = null;

    /**
     * Request object
     *
     * @var ServerRequestInterface $request
     */
    protected $request = null;

    /**
     * Custom renderer constructor
     *
     * @param App       $app            Slim application object
     * @param string    $templatePath   Views path
     * @param string    $layoutPath     Layouts path
     */
    public function __construct(App $app, $templatePath = '', $layoutPath = '')
    {
        $this->app = $app;
        $this->layoutPath = $layoutPath;

        parent::__construct($templatePath, [] /* $attributes */);
    }

    /**
     * Render a template
     *
     * @param  ResponseInterface    $response   Response object
     * @param  string               $template   Template name (view)
     * @param  array                $data       View data (optional)
     * @param  string               $layout     Layout file (optional)
     *
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, $template, array $data = [], $layout = 'default.phtml')
    {
        // Rendering view inside body layout
        if (!empty($layout)) {
            $newResponse = $this->renderWithLayout($response, $template, $data, $layout);
            if ($newResponse !== false) {
                return $newResponse;
            }
        }

        $data += $this->getDefaultAttributes();
        return parent::render($response, $this->templatePath . $template, $data);
    }

    /**
     * Render body view
     *
     * @param  ResponseInterface    $response   Response object
     * @param  string               $template   Template name (view)
     * @param  array                $data       View data (optional)
     * @param  string               $layout     Layout file (optional)
     *
     * @return string               Rendered HTML
     */
    protected function renderWithLayout(ResponseInterface $response, $template, array $data, $layout)
    {
        // If there's body layout
        $bodyTemplate = $this->layoutPath . $layout;
        if (!is_file($bodyTemplate)) {
            return false;
        }

        $defaultData = $this->getDefaultAttributes();

        // Renders body content
        $body = $this->fetch($this->templatePath . $template, $data + $defaultData);

        // Injecting view components into layout body
        $data = $this->getBodyAttributes() + $defaultData;
        if ($this->request) {
            $data['request'] = $this->request;
        }
        $data['body'] = $body;
        return parent::render($response, $bodyTemplate, $data);
    }

    /**
     * Returns default view attributes
     *
     * @return array
     */
    protected function getDefaultAttributes()
    {
        return [
            'router' => $this->app->getContainer()->get('router')
        ];
    }

    /**
     * Return body layout attributes
     *
     * @return array
     */
    protected function getBodyAttributes()
    {
        return [];
    }

    /**
     * Renders a template and returns the result as a string
     *
     * @throws Exception If there's something wrong while including the scripts
     *
     * @param  string   $template   Template name (view)
     * @param  array    $data       View data (optional)
     *
     * @return string
     */
    public function fetch($template, array $data = [])
    {
        try {
            ob_start();
            $this->protectedIncludeScope($template, $data);
            $output = ob_get_clean();
        } catch (\Throwable $e) { // PHP 7+
            ob_end_clean();
            throw $e;
        } catch (\Exception $e) { // PHP < 7
            ob_end_clean();
            throw $e;
        }

        return $output;
    }

    /**
     * Sets current request
     *
     * @param  ServerRequestInterface $request Request object
     *
     * @return self
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }
}
