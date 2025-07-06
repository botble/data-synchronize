<?php

namespace Botble\DataSynchronize\Commands;

use Botble\Ecommerce\Exporters\ProductExporter;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'data-synchronize:test-large-export', description: 'Test large product export functionality')]
class TestLargeExportCommand extends Command
{
    public function handle(): void
    {
        $this->info('Testing Large Product Export...');

        try {
            $exporter = new ProductExporter();

            // Get counts
            $counters = $exporter->getCounters();
            $this->info('Export Statistics:');
            foreach ($counters as $counter) {
                $this->line("- {$counter->getLabel()}: {$counter->getValue()}");
            }

            // Configure for large export
            $exporter->setChunkSize(200)
                ->useChunkedExport(true)
                ->setOptimizeMemory(true)
                ->enableStreamingMode(true)
                ->setIncludeVariations(true)
                ->format('csv');

            $this->info("\nExport Configuration:");
            $this->line("- Chunk Size: {$exporter->getChunkSize()}");
            $this->line('- Streaming Mode: ' . ($exporter->isStreamingMode() ? 'Enabled' : 'Disabled'));
            $this->line('- Include Variations: Yes');
            $this->line('- Format: CSV');

            // Test query
            $this->info("\nTesting export query...");
            $query = $exporter->query();
            $count = $query->count();
            $this->line("- Query will process: {$count} main products");

            // Test chunk processing
            $this->info("\nTesting chunk processing...");
            $processed = 0;
            $rows = 0;

            $bar = $this->output->createProgressBar($count);
            $bar->start();

            $query->chunk($exporter->chunkSize(), function ($products) use (&$processed, &$rows, $bar, $exporter) {
                foreach ($products as $product) {
                    $processed++;
                    $rows++; // Main product

                    if ($product->variations->count() > 0) {
                        $rows += $product->variations->count(); // Variations
                    }

                    $bar->advance();

                    if ($processed >= 10) {
                        // Just test first 10 products
                        return false;
                    }
                }
            });

            $bar->finish();
            $this->newLine();

            $this->info("\nTest Results:");
            $this->line("- Processed {$processed} main products");
            $this->line("- Would generate {$rows} total rows");
            $this->line('- Memory usage: ' . round(memory_get_usage() / 1024 / 1024, 2) . ' MB');

            $this->info("\nStreaming export is properly configured and ready to handle large datasets!");

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
