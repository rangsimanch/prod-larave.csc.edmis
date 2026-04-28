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
            error_log("ProcessSwnPdfConversion: SWN #{$this->swnId} not found");
            return;
        }

        try {
            // Check if source file exists
            if (!file_exists($this->tmpFilePath)) {
                error_log("ProcessSwnPdfConversion: Source file not found: {$this->tmpFilePath}");
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
                error_log("ProcessSwnPdfConversion: Failed to move to queue: {$this->tmpFilePath} -> {$queueFile}");
                return;
            }

            // Rename to final name
            $renameFile = storage_path('tmp/uploads/' . $this->filePrefix . $swn->id . '_' . $this->indexNumber . '.pdf');
            if (!rename($queueFile, $renameFile)) {
                error_log("ProcessSwnPdfConversion: Failed to rename: {$queueFile} -> {$renameFile}");
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
                error_log("ProcessSwnPdfConversion: Ghostscript failed for SWN #{$swn->id}: " . $process->getErrorOutput());
                return;
            }

            // Check if output file exists
            if (!file_exists($outputFile)) {
                error_log("ProcessSwnPdfConversion: Output file not created: {$outputFile}");
                return;
            }

            // Add converted file to media collection
            $swn->addMedia($outputFile)->toMediaCollection($this->collectionName);

            error_log("ProcessSwnPdfConversion: Successfully processed SWN #{$swn->id} collection {$this->collectionName}");

            // Clean up the renamed file (converted file is kept by Media Library)
            if (file_exists($renameFile)) {
                unlink($renameFile);
            }
        } catch (\Exception $e) {
            error_log("ProcessSwnPdfConversion: Exception for SWN #{$swn->id}: " . $e->getMessage());
        }
    }
}
