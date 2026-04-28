<?php

namespace App\Jobs;

use App\Ncn;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Process\Process;

class ProcessNcnPdfConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ncnId;
    protected $tmpFilePath;
    protected $collectionName;
    protected $filePrefix;
    protected $indexNumber;

    /**
     * Create a new job instance.
     *
     * @param int $ncnId
     * @param string $tmpFilePath - path to the uploaded tmp file
     * @param string $collectionName - media collection name
     * @param string $filePrefix - prefix for renamed files (e.g., 'NCN')
     * @param string $indexNumber - two-digit index number (e.g., '01', '02')
     */
    public function __construct($ncnId, $tmpFilePath, $collectionName, $filePrefix, $indexNumber)
    {
        $this->ncnId = $ncnId;
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
        $ncn = Ncn::find($this->ncnId);
        if (!$ncn) {
            error_log("ProcessNcnPdfConversion: NCN #{$this->ncnId} not found");
            return;
        }

        try {
            // Check if source file exists
            if (!file_exists($this->tmpFilePath)) {
                error_log("ProcessNcnPdfConversion: Source file not found: {$this->tmpFilePath}");
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
                error_log("ProcessNcnPdfConversion: Failed to move to queue: {$this->tmpFilePath} -> {$queueFile}");
                return;
            }

            // Rename to final name
            $renameFile = storage_path('tmp/uploads/' . $this->filePrefix . $ncn->id . '_' . $this->indexNumber . '.pdf');
            if (!rename($queueFile, $renameFile)) {
                error_log("ProcessNcnPdfConversion: Failed to rename: {$queueFile} -> {$renameFile}");
                return;
            }

            // Run Ghostscript conversion
            $outputFile = storage_path('tmp/uploads/' . 'Convert_' . $this->filePrefix . $ncn->id . '_' . $this->indexNumber . '.pdf');

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
                error_log("ProcessNcnPdfConversion: Ghostscript failed for NCN #{$ncn->id}: " . $process->getErrorOutput());
                return;
            }

            // Check if output file exists
            if (!file_exists($outputFile)) {
                error_log("ProcessNcnPdfConversion: Output file not created: {$outputFile}");
                return;
            }

            // Add converted file to media collection
            $ncn->addMedia($outputFile)->toMediaCollection($this->collectionName);

            error_log("ProcessNcnPdfConversion: Successfully processed NCN #{$ncn->id} collection {$this->collectionName}");

            // Clean up the renamed file (converted file is kept by Media Library)
            if (file_exists($renameFile)) {
                unlink($renameFile);
            }
        } catch (\Exception $e) {
            error_log("ProcessNcnPdfConversion: Exception for NCN #{$ncn->id}: " . $e->getMessage());
        }
    }
}
