<?php

namespace Iocaste\Microservice\Foundation\Test;

/**
 * Trait TestableApi
 */
trait TestableApi
{
    /**
     * @param $relativePath
     *
     * @return string
     */
    public function getUrl($relativePath): string
    {
        return 'http://localhost:8000' . $relativePath;
    }

    /**
     * @param $code
     *
     * @return $this
     */
    public function seeApiCode($code)
    {
        return $this->seeJsonContains(['code' => $code]);
    }

    /**
     * @return $this
     */
    public function seeSuccessfulOperation()
    {
        return $this->seeJsonContains(['type' => 'success']);
    }

    /**
     * @return $this
     */
    public function seeValidationError()
    {
        $content = $this->response->getContent();

        $this->assertContains(422, $content);
        $this->assertContains('"errors":{', $content);

        return $this;
    }

    /**
     * @param $errorCode
     *
     * @return $this
     */
    public function seeApiError($errorCode)
    {
        $content = $this->response->getContent();

        $this->assertContains($errorCode, $content);
        $this->seeJsonContains(['type' => 'error']);

        return $this;
    }

    /**
     * @param $entity
     * @return $this
     */
    public function seeJsonKey($entity)
    {
        $content = $this->response->getContent();

        $this->assertContains('"'.$entity.'":', $content);

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function seeJsonValue($value)
    {
        $content = $this->response->getContent();

        $this->assertContains('"'.$value.'"', $content);

        return $this;
    }

    /**
     * @param $entity
     * @return $this
     */
    public function seeJsonArray($entity)
    {
        $content = $this->response->getContent();

        $this->assertContains('"'.$entity.'":[', $content);

        return $this;
    }

    /**
     * @param $entity
     * @return $this
     */
    public function seeJsonObject($entity)
    {
        $content = $this->response->getContent();

        $this->assertContains('"'.$entity.'":{', $content);

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function seeJsonKeyValue($key, $value)
    {
        $content = $this->response->getContent();

        $this->assertContains('"'.$key.'":'.$value, $content);

        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function seeJsonKeyValueString($key, $value)
    {
        $content = $this->response->getContent();

        $this->assertContains('"'.$key.'":"'.$value.'"', $content);

        return $this;
    }
}
