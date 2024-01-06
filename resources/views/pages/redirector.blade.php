<?php

use function Livewire\Volt\{mount};

mount(function () {
    $version = config('doczilla.versions.default');
    $page = config('doczilla.docs.landing');

    $route = route('docs.show', [
        'version' => $version,
        'page' => $page,
    ]);

    $this->redirect($route, navigate: true);
});

?>
