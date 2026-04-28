<?php

namespace App\Jobs;

use App\Ncr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class ProcessNcrPdfConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ncrId;
    protected $tmpFilePath;
    protected $collectionName;
    protected $filePrefix;
    protected $indexNumber;

    /**
     * Create a new job instance.
     *
     * @param int $ncrId
     * @param string $tmpFilePath - path to the uploaded tmp file
     * @param string $collectionName - media collection name
     * @param string $filePrefix - prefix for renamed files (e.g., 'NCR')
     * @param string $indexNumber - two-digit index number (e.g., '01', '02')
     */
    public function __construct($ncrId, $tmpFilePath, $collectionName, $filePrefix, $indexNumber)
    {
        $this->ncrId = $ncrId;
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
        $ncr = Ncr::find($this->ncrId);
        if (!$ncr) {
            error_log("ProcessNcrPdfConversion: NCR #{$this->ncrId} not found");
            return;
        }

        try {
            // Check if source file exists
            if (!file_exists($this->tmpFilePath)) {
                error_log("ProcessNcrPdfConversion: Source file not found: {$this->tmpFilePath}");
                return;
            }

            // Ensure tmp/queue directory exists for processing
            $queueDir = storage_path('tmp/queue');
            if (!file_exists($queueDir)) {
                mkdir($queueDir, 0755, true);
            }

            // Move file to queue directory (prevents cleanup from deleting it)
            $queueFile = storage_path('tmp/queue/' . uniqid() . '_' . basename($this->tmpFilePath));
            if (!rename($this->tmpFilePath, $queueFile)) {
                error_log("ProcessNcrPdfConversion: Failed to move to queue: {$this->tmpFilePath} -> {$queueFile}");
                return;
            }

            // Rename to final name
            $renameFile = storage_path('tmp/uploads/' . $this->filePrefix . $ncr->id . '_' . $this->indexNumber . '.pdf');
            if (!rename($queueFile, $renameFile)) {
                error_log("ProcessNcrPdfConversion: Failed to rename: {$queueFile} -> {$renameFile}");
                return;
            }

            // Run Ghostscript conversion
            $outputFile = storage_path('tmp/uploads/' . 'Convert_' . $this->filePrefix . $ncr->id . '_' . $this->indexNumber . '.pdf');

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
                error_log("ProcessNcrPdfConversion: Ghostscript failed for NCR #{$ncr->id}: " . $process->getErrorOutput());
                return;
            }

            // Check if output file exists
            if (!file_exists($outputFile)) {
                error_log("ProcessNcrPdfConversion: Output file not created: {$outputFile}");
                return;
            }

            // Add converted file to media collection
            $ncr->addMedia($outputFile)->toMediaCollection($this->collectionName);

            error_log("ProcessNcrPdfConversion: Successfully processed NCR #{$ncr->id} collection {$this->collectionName}");

            // Clean up the renamed file (converted file is kept by Media Library)
            if (file_exists($renameFile)) {
                unlink($renameFile);
            }
        } catch (\Exception $e) {
            error_log("ProcessNcrPdfConversion: Exception for NCR #{$ncr->id}: " . $e->getMessage());
        }
    }
}
