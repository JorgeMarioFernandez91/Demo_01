<?php

namespace App\Console\Commands;

use App\Models\HelpArticle;
use App\Models\Tenant;
use App\Support\CurrentTenant;
use App\Support\SupportAssistant;
use Illuminate\Console\Command;
use Throwable;

class AssistantEval extends Command
{
    protected $signature = 'assistant:eval';

    protected $description = 'Score the support assistant\'s categories against the labeled samples';

    public function handle(SupportAssistant $assistant, CurrentTenant $currentTenant): int
    {
        $samples = require resource_path('evals/support-assistant.php');
        $misses = [];

        foreach ($samples as $i => $sample) {
            $tenant = Tenant::where('slug', $sample['tenant'])->firstOrFail();

            // No logged-in user here, so set the tenant explicitly from the sample.
            $got = $currentTenant->run($tenant, function () use ($assistant, $sample) {
                try {
                    return $assistant->triage($sample['subject'], $sample['body'], HelpArticle::all())['category'];
                } catch (Throwable $e) {
                    return 'error: '.$e->getMessage();
                }
            });

            if ($got !== $sample['category']) {
                $misses[] = [$i + 1, $sample['tenant'], $sample['subject'], $sample['category'], $got];
            }
        }

        $total = count($samples);
        $correct = $total - count($misses);

        $this->info(sprintf('Category accuracy: %d/%d (%.0f%%)', $correct, $total, $correct / $total * 100));

        if ($misses !== []) {
            $this->table(['#', 'Tenant', 'Subject', 'Expected', 'Got'], $misses);
        }

        return self::SUCCESS;
    }
}
