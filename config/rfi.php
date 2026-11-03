<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authorized Signatory Cutoff
    |--------------------------------------------------------------------------
    |
    | RFIs with id >= this value use the per-document Authorized Signatory
    | selection (rfi_authorized_signatory). Older RFIs keep using the legacy
    | hard-coded issuer/signature logic in createReportRFI.
    |
    */
    'signatory_cutoff_id' => 4474,
];
