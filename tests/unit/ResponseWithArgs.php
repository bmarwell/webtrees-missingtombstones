<?php

namespace bmarwell\WebtreesModules\MissingTombstones;

use \Psr\Http\Message\ResponseInterface;

interface ResponseWithArgs extends ResponseInterface {

  /** @var string */
  public $viewName;

  /** @var array */
  public $viewArgs;

  public function __construct(string $viewName = '', array $viewArgs = [])
  {
    $this->viewName = $viewName;
    $this->viewArgs = $viewArgs;
  }
}