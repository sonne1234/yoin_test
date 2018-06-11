<?php

namespace App\Infrastructure\Client;

use App\Domain\Condo\Condo;
use App\Domain\Condo\CondoPaymentData;
use App\Domain\Resident\Resident;
use App\Domain\Transaction\Transaction;
use App\Infrastructure\Client\Exception\InvalidDataReceivedException;
use App\Infrastructure\Client\Exception\PagamobilException;
use App\Infrastructure\Client\Exception\RemoteApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class PagamobilClient
{
    /**
     * @var Client
     */
    private $client;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(string $endpoint, string $username, string $password, string $publicKey, LoggerInterface $logger)
    {
        $this->logger = $logger;
        // Hack to pass key throuh .env variable
        $publicKey = str_replace('\n', "\n", $publicKey);

        $key = openssl_get_publickey($publicKey);
        openssl_public_encrypt($password, $encryptedPassword, $key);
        $encryptedPassword = base64_encode($encryptedPassword);

        $config = [
            'auth' => [$username, $encryptedPassword],
            'base_uri' => $endpoint,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        $this->client = new Client($config);
    }

    /**
     * @return array
     */
    public function getBankList(): array
    {
        $response = $this->getApiResponse('GET', 'banks');
        if (!isset($response['accounts'])) {
            $this->logger->critical('Pagamobile error [createAccount]: '.$response);
            throw new InvalidDataReceivedException();
        }

        return $response['accounts'];
    }

    public function createResident(Resident $resident): string
    {
        $response = $this->getApiResponse(
            'POST',
            'clients',
            [
                'json' => [
                    'name' => $resident->getFirstName(),
                    'lastname' => $resident->getLastName(),
                    'email' => $resident->getEmail(),
                    'phone' => $resident->toArray(false, true)['cellPhone'],
                    'state' => $resident->getUnit()->getCondo()->getBillingData()['state'],
                    'city' => $resident->getUnit()->getCondo()->getBillingData()['city'],
                    'identifier' => $resident->getId(),
                ],
            ]
        );

        if (!isset($response['client_id']) || !$response['client_id']) {
            $this->logger->critical('Pagamobile error [createAccount]: '.$response);
            throw new InvalidDataReceivedException();
        }

        return $response['client_id'];
    }

    public function createAccount(Condo $condo): string
    {
        $condoPaymentData = $condo->getPaymentData();

        $data = [
            'account' => $condo->getId(),
            'bank_id' => $condoPaymentData['bankId'],
            'name' => $condoPaymentData['businessOwnerName'],
            'type' => $condoPaymentData['type'],
            'vmc_fee' => $condoPaymentData['vmcBankFee'],
            'vmc_extra_fee' => $condoPaymentData['vmcPlatformFee'],
            'amex_fee' => $condoPaymentData['amexBankFee'],
            'amex_extra_fee' => $condoPaymentData['amexPlatformFee'],
        ];

        if (CondoPaymentData::TYPE_AFFILIATION == $condoPaymentData['type']) {
            $data['affiliation_number'] = $condoPaymentData['affiliationNumber'];
        } else {
            $data['clabe'] = $condoPaymentData['clabe'];
            $data['account_number'] = $condoPaymentData['accountNumber'];
        }
        $response = $this->getApiResponse(
            'POST',
            'accounts',
            [
                'json' => $data,
            ]
        );

        if (!isset($response['account_id']) || !$response['account_id']) {
            $this->logger->critical('Pagamobile error [createAccount]: '.$response);
            throw new InvalidDataReceivedException();
        }

        return $response['account_id'];
    }

    public function updateAccount(Condo $condo): void
    {
        $condoPaymentData = $condo->getPaymentData();
        $this->getApiResponse(
            'PUT',
            'accounts/'.$condo->getPaymentAccountId(),
            [
                'json' => [
                    'account_id' => $condo->getPaymentAccountId(),
                    'vmc_fee' => $condoPaymentData['vmcBankFee'],
                    'vmc_extra_fee' => $condoPaymentData['vmcPlatformFee'],
                    'amex_fee' => $condoPaymentData['amexBankFee'],
                    'amex_extra_fee' => $condoPaymentData['amexPlatformFee'],
                ],
            ]
        );

        $this->logger->critical('Pagamobile error [url]: '.'accounts/'.$condo->getPaymentAccountId().' '.json_encode($condoPaymentData));
        $this->logger->critical(json_encode(['json' => [
                'account_id' => $condo->getPaymentAccountId(),
                'vmc_fee' => $condoPaymentData['vmcBankFee'],
                'vmc_extra_fee' => $condoPaymentData['vmcPlatformFee'],
                'amex_fee' => $condoPaymentData['amexBankFee'],
                'amex_extra_fee' => $condoPaymentData['amexPlatformFee'],
            ]]));
    }

    public function refundTransaction(Transaction $transaction): bool
    {
        try {
            $this->getApiResponse(
                'GET',
                'yoinpayments/'.$transaction->getTransactionId().'/reimburse'
            );
        } catch (\Throwable $e) {
            $this->logger->critical('Pagamobile error: '.$e->getMessage());

            return false;
        }

        return true;
    }

    private function getApiResponse($method, $uri, $options = []): array
    {
        try {
            /**
             * @var ResponseInterface
             */
            $apiResponse = $this->client->request($method, $uri, $options);

            $response = json_decode($apiResponse->getBody()->getContents(), true);
        } catch (RequestException $exception) {
            $this->logger->critical('Pagamobile error [RequestException]: '.$exception->getMessage());
            $this->logger->critical(json_encode(['method' => $method, 'uri' => $uri, 'options' => $options]));
            if (!$exception->getResponse()) {
                throw new RemoteApiException($exception->getMessage());
            } else {
                $code = (json_decode($exception->getResponse()->getBody()->getContents()))->code;
                throw new PagamobilException($code);
            }
        } catch (GuzzleException $exception) {
            $this->logger->critical('Pagamobile error [GuzzleException]: '.$exception->getMessage());
            throw new RemoteApiException($exception->getMessage());
        }

        return $response;
    }
}
