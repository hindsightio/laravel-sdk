<?php

namespace Hindsight\LoggingTools;

use Decahedron\StickyLogging\StickyContext;

class AttachEnvironmentDetails
{
    public function __invoke()
    {
        StickyContext::stack('hindsight')->add('actor_id',
            function () {
                return \Auth::id();
            });
        StickyContext::stack('hindsight')->add('environment',
            function () {
                return \App::environment();
            });
    }
}
