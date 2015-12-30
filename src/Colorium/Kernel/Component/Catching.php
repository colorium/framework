<?php

namespace Colorium\Kernel\Component;

use Colorium\Kernel\Component;
use Colorium\Http\Request;
use Colorium\Http\Response;
use Colorium\Http\Error;
use Colorium\Event;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Catching extends Component
{

    /** @var Event\Subject */
    protected $events = [];

    /** @var LoggerInterface */
    protected $logger;


    /**
     * Init catching component using logger
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger;
        $this->events = new Event\Store;
    }


    /**
     * Setup catching
     */
    public function setup()
    {
        $this->app->logger = &$this->logger;
        $this->app->events = &$this->events;
    }


    /**
     * Handle request/response
     *
     * @param Request $request
     * @param Response $response
     * @param callable $process
     * @return Response
     *
     * @throws Error
     * @throws \Exception
     */
    public function handle(Request $request, Response $response, callable $process)
    {
        // event listener wrapper
        $forward = function($resource) use($request) {
            return $this->app->forward($resource, $request);
        };

        // try processing
        try {
            return $process($request, $response);
        }
        // catch http error
        catch(Error $e) {
            $code = $e->getCode();
            $this->logger->info($code . ': ' . $e->getMessage());
            $response = $this->events->fire($code, [], $forward);
            if(!$response) {
                throw $e;
            }

            return $response;
        }
        // catch unexpected error
        catch(\Exception $e) {
            $this->logger->error($e->getMessage());
            $exception = get_class($e);
            $response = $this->events->fire($exception, [], $forward);
            if(!$response) {
                throw $e;
            }

            return $response;
        }
    }

}