<?php
/*
 |------------------------------------------------------------------
 | PhpStorm
 |------------------------------------------------------------------
 | @author    : liubang 
 | @date      : 2016/4/15 12:22
 | @copyright : (c) iliubang.cn
 | @license   : MIT (http://opensource.org/licenses/MIT)
 |------------------------------------------------------------------
 */
namespace Linger\Core;

class ResponseCookie
{
    /**
     * the name of cookie
     *
     * @var string
     */
    private $name;

    /**
     * the value of the cookie
     *
     * @var string
     */
    private $value;

    /**
     * the date/time that the cookie should expire
     * represented by a Unix timestamp
     *
     * @var int
     */
    private $expire;

    /**
     * the path on the server that the cookie will be available on
     *
     * @var string
     */
    private $path;

    /**
     * the domain that the cookie is available to
     *
     * @var string
     */
    private $domain;

    /**
     * whether the cookie should only be transferred over an HTTPS connection or not
     *
     * @var boolean
     */
    private $secure;

    /**
     * @var boolean
     */
    private $httpOnly;

    /**
     * ResponseCookie constructor.
     *
     * @param string $name
     * @param string $value
     * @param int    $expire
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     */
    public function __construct(
        $name,
        $value = null,
        $expire = null,
        $path = null,
        $domain = null,
        $secure = false,
        $httpOnly = false
    ) {
        $this->setName($name);
        $this->setValue($value);
        $this->setExpire($expire);
        $this->setPath($path);
        $this->setDomain($domain);
        $this->setSecure($secure);
        $this->setHttpOnly($httpOnly);
    }

    public function getHttpOnly()
    {
        return $this->httpOnly;
    }

    public function setHttpOnly($httpOnly)
    {
        $this->httpOnly = (boolean)$httpOnly;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        if (null !== $value) {
            $this->value = (string)$value;
        } else {
            $this->value = $value;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * @param int|null $expire
     * @return $this
     */
    public function setExpire($expire)
    {
        if (null !== $expire) {
            $this->expire = (int)$expire;
        } else {
            $this->expire = $expire;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return $this
     */
    public function setPath($path)
    {
        if (null !== $path) {
            $this->path = (string)$path;
        } else {
            $this->path = $path;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string|null $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        if (null !== $domain) {
            $this->domain = (string)$domain;
        } else {
            $this->domain = $domain;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * @param $secure
     * @return $this
     */
    public function setSecure($secure)
    {
        $this->secure = (boolean)$secure;
        return $this;
    }
}