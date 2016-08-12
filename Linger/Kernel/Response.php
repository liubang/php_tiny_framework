<?php
/*
 |------------------------------------------------------------------
 | linger.iliubang.cn
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 16/3/25 上午12:31
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */

namespace Linger\Kernel;

class Response
{
    /**
     * @var int
     */
    private $statusCode = 200;

    /**
     * @var string
     */
    private $protocolVersion = '1.1';

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var bool
     */
    private $sent = false;

    /**
     * send the response
     *
     * @param bool $override
     * @return $this
     * @throws \HttpResponseException
     */
    public function send($override = false)
    {
        if ($this->sent && ! $override) {
            throw new \HttpResponseException('Response has already been sent');
        }

        // send our response data
        $this->sendHeaders();
        $this->sendBody();

        // mark as sent
        $this->sent = true;

        if (\function_exists('fastcgi_finish_request')) {
            \fastcgi_finish_request();
        }
        return $this;
    }

    /**
     * set or get response status code
     *
     * @param null|string $code
     * @return $this
     */
    public function code($code = null)
    {
        if (null !== $code) {
            $this->statusCode = $code;
            return $this;
        }
        return $this->statusCode;
    }

    /**
     * generates an HTTP compatible status header line string
     * creates the string based off of the response's properties
     *
     * @return string
     */
    protected function httpStatusLine()
    {
        return \sprintf('HTTP/%s %s', $this->protocolVersion, $this->statusCode);
    }

    /**
     * send our HTTP headers
     *
     * @param bool $override
     * @return $this
     */
    public function sendHeaders($override = false)
    {
        if (\headers_sent() && ! $override) {
            return $this;
        }

        // send our HTTP status line
        \header($this->httpStatusLine());

        // Iterate through our Headers data collection and send each header
        foreach ($this->headers as $key => $value) {
            \header($key . ': ' . $value, false);
        }

        return $this;
    }

    /**
     * send our body's contents
     *
     * @return $this
     */
    public function sendBody()
    {
        echo (string)$this->body;

        return $this;
    }

    /**
     * send an object as json or jsonp by providing the padding prefix
     *
     * @param mixed       $obj
     * @param null|string $jsonp_prefix
     * @return $this
     * @throws \HttpResponseException
     */
    public function json($obj, $jsonp_prefix = null)
    {
        $this->body('');
        $this->noCache();
        $json = \json_encode($obj);
        if (null !== $jsonp_prefix) {
            $this->header('Content-Type', 'text/javascript');
            $this->body("$jsonp_prefix($json)");
        } else {
            $this->header("Content-Type", 'application/json');
            $this->body($json);
        }

        $this->send();

        return $this;
    }

    /**
     * send a file
     *
     * @param string      $path
     * @param null|string $filename
     * @param null|string $mimetype
     * @return $this
     * @throws \HttpResponseException
     */
    public function file($path, $filename = null, $mimetype = null)
    {
        $this->body('');
        $this->noCache();

        if (null === $filename) {
            $filename = \basename($path);
        }
        if (null === $mimetype) {
            $mimetype = \finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
        }

        $this->header('Content-Type', $mimetype);
        $this->header('Content-length', \filesize($path));
        $this->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        $this->send();
        \readfile($path);
        return $this;
    }

    /**
     * @param null|String $body
     * @return $this|string
     */
    public function body($body = null)
    {
        if (null !== $body) {
            $this->body = (string)$body;
            return $this;
        }
        return $this->body;
    }

    /**
     * to set the header
     *
     * @param String $key
     * @param String $value
     * @return $this
     */
    public function header($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * tell the browser not to cache the response.
     *
     * @return $this
     */
    public function noCache()
    {
        $this->header('Pragma', 'no-cache');
        $this->header('Cache-Control', 'no-store, no-cache');
        return $this;
    }
}