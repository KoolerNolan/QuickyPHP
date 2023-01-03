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
 * Class Response
 */
class Response
{
    /**
     * Storage path
     *
     * @var string
     */
    private string $storagePath;

    /**
     * Is cache active?
     *
     * @var bool
     */
    private bool $useCache;

    /**
     * Expiration of cache
     * iff it is enabled
     *
     * @var int|null
     */
    private ?int $cacheExpires = null;

    /**
     * All MIME Types
     *
     * @var array|string[]
     */
    private array $mimeTypes = [
        'css' => 'text/css',
        'html' => 'text/html',
        'js' => 'text/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpg',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
        'zip' => 'application/zip',
        'mp3' => 'audio/mpeg',
        'wav' => 'audio/x-wav',
        'ico' => 'image/x-icon',
        'csv' => 'text/csv',
        'txt' => 'text/plain',
        'xml' => 'text/xml'
    ];

    /**
     * All HTTP codes
     *
     * @var array|string[]
     */
    private array $codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    /**
     * Response constructor.
     */
    public function __construct()
    {
        $config = DynamicLoader::getLoader()->getInstance(Config::class);
        $this->storagePath = getcwd() . $config->getStoragePath();
        $this->useCache = $config->isCacheActive();
        $this->cacheExpires = ($this->useCache) ? $config->getCacheExpiration() : null;
    }

    /**
     * Updates the HTTP response code
     *
     * @param int $code
     */
    public function status(int $code): void
    {
        http_response_code($code);
    }

    /**
     * Sends a 403 - Forbidden Error
     *
     * @param string $message
     */
    public function forbidden(string $message): void
    {
        $this->status(403);
        echo $message;
    }

    /**
     * Stops/Halts HTTP Response
     * e.g if an error occurred
     *
     * @param string $message
     */
    public function stop(string $message = ""): void
    {
        die($message);
    }

    /**
     * Initiates a HTTP redirection
     *
     * @param string $destination
     */
    public function redirect(string $destination): void
    {
        http_redirect($destination);
    }

    private function setCacheHeaders(): void
    {
        if (is_null($this->cacheExpires) || !$this->useCache) return;

        $expire = time() + $this->cacheExpires;
        header("Cache-Control: max-age=$expire");
        header("Expires: " . gmdate("D, d M Y H:i:s", $expire) . " GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()) . " GMT");
    }

    /**
     * Sends text/html with formatters
     *
     * @param string $text
     * @param mixed ...$formatters
     */
    public function send(string $text, ...$formatters): void
    {
        if ($this->useCache) $this->setCacheHeaders();

        printf($text, ...$formatters);
    }

    /**
     * Resolves the error message for a
     * HTTP error code (int)
     *
     * @param int $code
     * @return string
     */
    public function getErrorMessage(int $code): string
    {
        return (isset($this->codes[$code])) ? $this->codes[$code] : "Strange HTTP Error";
    }

    /**
     * Resolve MIME Type
     *
     * @param string $fileName
     * @return string
     */
    private function getMIMEType(string $fileName): string
    {
        return $this->mimeTypes[strtolower(substr($fileName, strrpos($fileName, '.') + 1))];
    }

    /**
     * Sends file-content as response
     *
     * @param string $fileName
     * @throws UnknownFileSentException
     */
    public function sendFile(string $fileName): void
    {
        if ($this->useCache) $this->setCacheHeaders();

        $basePath = $this->storagePath;
        $fullPath = "$basePath/$fileName";

        if (strpos($fullPath, $basePath) !== 0) throw new UnknownFileSentException($fileName);
        if (!file_exists($fullPath)) throw new UnknownFileSentException($fileName);

        $type = $this->getMIMEType($fileName);

        header('Content-Type: ' . $type);
        header('Content-Length: ' . filesize($fullPath));
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        readfile($fullPath);
    }

    /**
     * Renders a view as response
     *
     * @param string $viewName
     * @param array|null $variables
     * @param string|null $override
     * @throws ViewNotFoundException
     */
    public function render(string $viewName, ?array $variables = null, ?string $override = null): void
    {
        if ($this->useCache) $this->setCacheHeaders();

        View::render($viewName, $variables, $override);
    }

    /**
     * Returns response in string format
     *
     * @return string
     */
    public function toString(): string
    {
        return "Response Object (cached=" . ($this->useCache) ? "yes" : "no" . ")";
    }
}