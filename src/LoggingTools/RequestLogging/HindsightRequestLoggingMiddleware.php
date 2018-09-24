<?php namespace Hindsight\LoggingTools\RequestLogging;

use Decahedron\StickyLogging\StickyContext;
use Illuminate\Log\Logger;
use Ramsey\Uuid\Uuid;

class HindsightRequestLoggingMiddleware
{
    /**
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, \Closure $next)
    {
        /** @var Logger $log */
        $log = app('log');

        $requestId = Uuid::uuid4()->toString();

        try {
            $requestId = $this->addPreflightStickies($request, $requestId);
        } catch (\Exception $e) {
            // silence sticky exceptions
        }

        $log->debug('Request initiated', array_merge([
            'request' => array_filter([
                'body' => $request->except(config('hindsight.redact.fields', [])),
            ])
        ], ['code' => 'hindsight.request-started']));

        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        $data = $response->getContent();
        if ($jsonData = json_decode($data, JSON_OBJECT_AS_ARRAY)) {
            $data = $jsonData;
            if (config('hindsight.attach_request_id_to_response')) {
                $data['meta'] = array_merge($data['meta'] ?? [], ['request_id' => $requestId]);
                $response->setContent(json_encode($data));
            }
        }

        $log->debug('Request finished, sending response', [
            'response' => [
                'status' => $response->getStatusCode(),
                'body' => $data,
                'headers' => $this->filterHeaders($response->headers->all()),
            ],
            'code' => 'hindsight.request-finished',
        ]);

        return $response;
    }

    /**
     * @param $headers
     * @return array
     */
    protected function filterHeaders($headers)
    {
        return array_filter($headers, function ($header) {
            return ! in_array(strtolower($header), array_map('strtolower', config('hindsight.redact.headers', [])));
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param $request
     * @param $requestId
     * @return string
     */
    protected function addPreflightStickies($request, $requestId): string
    {
        StickyContext::stack('hindsight')->add('actor_id',
            function () {
                return \Auth::id();
            });
        StickyContext::stack('hindsight')->add('environment',
            function () {
                return \App::environment();
            });
        StickyContext::stack('hindsight')->add('request',
            [
                'id'      => $requestId,
                'ip'      => $request->getClientIp(),
                'method'  => $request->method(),
                'url'     => $request->url(),
                'headers' => $this->filterHeaders($request->headers->all()),
            ]);
        return $requestId;
    }

}
