<?php

namespace App\Console\Commands;

use App\Support\TicketClassifier;
use Illuminate\Console\Command;
use Throwable;

class SupportEval extends Command
{
    protected $signature = 'support:eval';

    protected $description = 'Categorize the sample tickets with Claude and print the accuracy';

    /**
     * The samples are a fixture file, not tenant records, so no tenant is needed here.
     */
    public function handle(TicketClassifier $classifier): int
    {
        if (blank(config('services.anthropic.key'))) {
            $this->error('ANTHROPIC_API_KEY is not set.');

            return self::FAILURE;
        }

        $samples = require resource_path('eval/ticket-categories.php');
        $misses = [];

        foreach ($samples as $i => $sample) {
            try {
                $got = $classifier->classify($sample['subject'], $sample['body']);
            } catch (Throwable $e) {
                $got = 'error: '.$e->getMessage();
            }

            if ($got !== $sample['expected']) {
                $misses[] = [$i + 1, $sample['subject'], $sample['expected'], $got];
            }
        }

        $total = count($samples);
        $correct = $total - count($misses);

        $this->line(sprintf('Accuracy: %d/%d (%.1f%%)', $correct, $total, $correct / $total * 100));

        if ($misses !== []) {
            $this->table(['#', 'Subject', 'Expected', 'Got'], $misses);
        }

        return self::SUCCESS;
    }
}
