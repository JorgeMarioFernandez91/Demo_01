<?php

namespace App\Console\Commands;

use App\Support\TicketCategorizer;
use Illuminate\Console\Command;
use Throwable;

class SupportEval extends Command
{
    /**
     * @var string
     */
    protected $signature = 'support:eval';

    /**
     * @var string
     */
    protected $description = 'Tag the sample tickets with Claude and report category accuracy';

    /**
     * Sample tickets are plain fixtures, not tenant records, so no tenant is set here.
     */
    public function handle(TicketCategorizer $categorizer): int
    {
        if (blank(config('services.anthropic.key'))) {
            $this->error('ANTHROPIC_API_KEY is not set.');

            return self::FAILURE;
        }

        $samples = require database_path('eval/ticket_categories.php');
        $misses = [];

        $this->info('Tagging '.count($samples).' sample tickets with '.config('services.anthropic.model').'...');

        foreach ($samples as $index => $sample) {
            try {
                $got = $categorizer->categorize($sample['subject'], $sample['body']);
            } catch (Throwable $e) {
                $got = 'error: '.$e->getMessage();
            }

            $correct = $got === $sample['expected'];
            $this->output->write($correct ? '.' : 'F');

            if (! $correct) {
                $misses[] = [$index + 1, $sample['subject'], $sample['expected'], $got];
            }
        }

        $total = count($samples);
        $right = $total - count($misses);

        $this->newLine(2);
        $this->line(sprintf('Accuracy: %d/%d (%d%%)', $right, $total, round($right / $total * 100)));

        if ($misses !== []) {
            $this->newLine();
            $this->line('Wrong:');
            $this->table(['#', 'Subject', 'Expected', 'Got'], $misses);
        }

        return self::SUCCESS;
    }
}
