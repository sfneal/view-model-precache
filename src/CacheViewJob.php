<?php


namespace Sfneal\ViewModels\Precache;


use Sfneal\Queueables\AbstractJob;
use Sfneal\ViewModels\AbstractViewModel;

class CacheViewJob extends AbstractJob
{
    /**
     * @var int Number of seconds to delay dispatchment by
     */
    public $delay = 30;

    /**
     * @var string Queue to use
     */
    public $queue = 'cache';

    /**
     * @var AbstractViewModel
     */
    private $viewModel;

    /**
     * @var string
     */
    private $route_name;

    /**
     * @var array
     */
    private $route_data;

    /**
     * ClientGetRequestJob constructor.
     *
     * @param $viewModel
     * @param string $route_name
     * @param array|null $route_data
     */
    public function __construct($viewModel, string $route_name, array $route_data = null)
    {
        $this->viewModel = $viewModel;
        $this->route_name = $route_name;
        $this->route_data = $route_data;
    }

    /**
     * Send a GuzzleHttp get request (intended for pre-caching a views)
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->viewModel
            ->setRedisKey(route($this->route_name, $this->route_data))
            ->invalidateCache()
            ->render();
    }
}
