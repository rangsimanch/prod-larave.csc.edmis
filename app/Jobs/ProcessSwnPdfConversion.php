<?php

namespace App\Jobs;

use App\Swn;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class ProcessSwnPdfConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $swnId;
    protected $tmpFilePath;
    protected $collectionName;
    protected $filePrefix;
    protected $indexNumber;

    /**
     * Create a new job instance.
     *
     * @param int $swnId
     * @param string $tmpFilePath - path to the uploaded tmp file
     * @param string $collectionName - media collection name
     * @param string $filePrefix - prefix for renamed files (e.g., 'SWN', 'Reply_SWN')
     * @param string $indexNumber - two-digit index number (e.g., '01', '02')
     */
    public function __construct($swnId, $tmpFilePath, $collectionName, $filePrefix, $indexNumber)
    {
        $this->swnId = $swnId;
        $this->tmpFilePath = $tmpFilePath;
        $this->collectionName = $collectionName;
        $this->filePrefix = $filePrefix;
        $this->indexNumber = $indexNumber;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $swn = Swn::find($this->swnId);
        if (!$swn) {
            \Log::error("ProcessSwnPdfConversion: SWN #{$this->swnId} not found");
            return;
        }

        try {
            // Rename the file
            $renameFile = storage_path('tmp/uploads/' . $this->filePrefix . $swn->id . '_' . $this->indexNumber . '.pdf');
            if (!rename($this->tmpFilePath, $renameFile)) {
                \Log::error("ProcessSwnPdfConversion: Failed to rename file from {$this->tmpFilePath} to {$renameFile}");
                return;
            }

            // Run Ghostscript conversion
            $outputFile = storage_path('tmp/uploads/' . 'Convert_' . $this->filePrefix . $swn->id . '_' . $this->indexNumber . '.pdf');

            $process = new Process([
                'gs',
                '-sDEVICE=pdfwrite',
                '-dCompatibilityLevel=1.4',
                '-dPDFSETTINGS=/ebook',
                '-dNOPAUSE',
                '-dQUIET',
                '-dBATCH',
                "-sOutputFile={$outputFile}",
                $renameFile
            ]);

            $process->run();

            if (!$process->isSuccessful()) {
                \Log::error("ProcessSwnPdfConversion: Ghostscript failed for SWN #{$swn->id} collection {$this->collectionName}: " . $process->getErrorOutput());
                return;
            }

            // Add converted file to media collection
            $swn->addMedia($outputFile)->toMediaCollection($this->collectionName);

            // Clean up the renamed file (converted file is kept by Media Library)
            if (file_exists($renameFile)) {
                unlink($renameFile);
            }

            \Log::info("ProcessSwnPdfConversion: Successfully processed SWN #{$swn->id} collection {$this->collectionName}");
        } catch (\Exception $e) {
            \Log::error("ProcessSwnPdfConversion: Error processing SWN #{$swn->id} collection {$this->collectionName}: " . $e->getMessage());
        }
    }
}
