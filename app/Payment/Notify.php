<?php
declare(strict_types=1);

namespace App\Payment;


use GuzzleHttp\Client;

class Notify
{
    private $headers = [
        'Content-Type' => 'application/json'
    ];

    private $options = [
        'timeout'  => 3.0
    ];


    /**
     * 设置自定义的请求头部
     * @param array $headers
     */
    public function setHttpHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    public function getHttpClient(): Client
    {
        return new Client($this->options);
    }

    /**
     * 设置请求参数
     * @param array $options
     */
    public function setGuzzleOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * 发送通知
     * @param string $url
     * @param array $data
     * @param string|null $secret
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $url, array $data, string $secret = null): bool
    {
        if (!isset($data['sign']) && $secret) {
            $data['sign'] = Signature::make($data, $secret);
        }

        return $this->doRequest($url, 'POST', $data);
    }

    /**
     * 发起请求
     * @param string $url
     * @param string $method
     * @param array $data
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function doRequest(string $url, string $method, array $data): bool
    {
        $client = $this->getHttpClient();

        try {
            $response = $client->request($method, $url, [
                'headers' => $this->headers,
                'json' => $data
            ]);

            if ($response && $response->getStatusCode() === 200) {
                return true;
            }
        }
        catch (\Exception $exception) {
            return false;
        }

        return false;
    }
}