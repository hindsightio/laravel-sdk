<?php namespace Hindsight\Utility;

use Illuminate\Console\Command;

class VerifyHindsightCommand extends Command
{
    protected $signature = 'hindsight:verify';

    protected $description = 'Diagnose your Hindsight configuration & settings.';
}
