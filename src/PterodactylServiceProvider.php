<?php

namespace RexlManu\Pterodactyl;

use Illuminate\Support\ServiceProvider;
use RexlManu\Pterodactyl\Sections\ApplicationSection;
use RexlManu\Pterodactyl\Sections\ClientSection;

class PterodactylServiceProvider extends ServiceProvider
{

  public function boot()
  {
    if ($this->app->runningInConsole()) {
      $this->publishes([
        __DIR__ . '/../config/config.php' => config_path('pterodactyl.php'),
      ], 'config');
    }
  }

  public function register()
  {
    $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'pterodactyl');
    $this->app->bind('pterodactyl.application', function () {
      return new ApplicationSection();
    });
  }

}