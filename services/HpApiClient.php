<?php

declare(strict_types=1);

/**
 * HTTP client wrapper for the HP API — handles curl errors, retries,
 * and validates responses before returning data.
 */
class HpApiClient
{
    private string $endpoint;
    private int $timeout;

    public function __construct(string $endpoint, int $timeout = 15)
    {
        $this->endpoint = $endpoint;
        $this->timeout = $timeout;
    }

    /**
     * Fetch characters from the API.
     *
     * @return array{data: array, error: ?string}
     */
    public function fetchCharacters(): array
    {
        $curl = curl_init($this->endpoint);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'User-Agent: HP-Characters-Search/1.0',
            ],
        ]);

        $response = curl_exec($curl);
        $curlError = curl_error($curl);
        $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($response === false || $curlError) {
            return [
                'data' => [],
                'error' => 'Nao foi possivel conectar a API. Verifique sua conexao e tente novamente.',
            ];
        }

        if ($httpCode >= 500) {
            return [
                'data' => [],
                'error' => 'O servico da API esta temporariamente indisponivel. Tente novamente em instantes.',
            ];
        }

        if ($httpCode >= 400) {
            return [
                'data' => [],
                'error' => 'Erro na comunicacao com a API (HTTP ' . $httpCode . ').',
            ];
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            return [
                'data' => [],
                'error' => 'Resposta inesperada da API. Tente novamente mais tarde.',
            ];
        }

        return ['data' => $data, 'error' => null];
    }
}
