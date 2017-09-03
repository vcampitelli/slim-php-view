# PHP View Renderer for Slim
This is slightly enhanced version from [https://github.com/slimphp/PHP-View](slimphp/PHP-View). These extra features were added:
- [x] Body template
- [ ] Helper classes (for easily management of stylesheets and JavaScripts)

## Installing
Via composer: `composer require vcampitelli/slim-php-view`

## Basic configuration
1. Create a directory to store your layout views (for instance, `layouts/`)
  * There, create a `default.phtml` for your default body template and use a `$body` variable to display the inner layout. Something like this:
```html
<!DOCTYPE html>
<html>
    <body>
        <?php echo $body; ?>
    </body>
</html>
```
2. Add the previous directory to `src/settings.php` renderer config array:
```php
// Renderer settings
'renderer' => [
    'template_path' => __DIR__ . '/../templates/',
    'layout_path' => __DIR__ . '/../layouts/'
],
```
3. Replace default renderer at `src/dependencies.php` with the following:
```php
// view
$container['renderer'] = function ($c) use ($app) {
    $settings = $c->get('settings')['renderer'];
    return new Vcampitelli\Slim\View\View(
        $app,
        $settings['template_path'],
        $settings['layout_path']
    );
};
```
4. Create your routes at `src/routes.php` using `$this->renderer->render()` method
```php
$app->get('/page', function ($request, $response, $args) {
    // Render custom view
    return $this->renderer->render(
        $response,      // ResponseInterface object
        'page.phtml',   // Inner content view name
        $args,          // Custom data (array)
        'default.phtml' // Template name (optional - default: default.phtml)
    );
});
```
