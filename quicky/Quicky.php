<?php
/**
 * QuickyPHP - A handmade php micro-framework
 *
 * @author David Dewes <hello@david-dewes.de>
 *
 * Copyright - David Dewes (c) 2022
 */

declare(strict_types=1);

/**
 * Class Quicky
 *
 * @method static get(string $pattern, callable $callback)
 * @method static post(string $pattern, callable $callback)
 * @method static render(string $viewName, ?array $params = null)
 */
class Quicky
{
    private static ?Quicky $instance = null;

    /**
     * Quicky constructor.
     */
    private function __construct()
    {
        DynamicLoader::getLoader()->registerInstance(Quicky::class, $this);
    }

    /**
     * Creates or returns an instance
     *
     * @return Quicky|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Quicky();
        }
        return self::$instance;
    }

    /**
     * Run application
     *
     * @throws UnknownRouteException
     */
    public function run()
    {
        $router = DynamicLoader::getLoader()->getInstance(Router::class);
        if ($router instanceof Router) $router(new Request(), new Response());
    }

    /**
     * Stop application
     *
     * @param int $code
     */
    public function stop(int $code = 0)
    {
        exit($code);
    }

    /**
     * Handle static function calls.
     * They will be dispatched to their corresponding
     * dispatching classes.
     *
     * @param $name
     * @param $arguments
     * @throws UnknownCallException
     * @throws ReflectionException
     */
    public static function __callStatic($name, $arguments): void
    {
        Dispatcher::dispatch($name, $arguments);
    }
}