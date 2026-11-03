<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authorized Signatory Cutoff
    |--------------------------------------------------------------------------
    |
    | RFAs with id >= this value use the per-document Authorized Signatory
    | selection (rfa_authorized_signatory). Older RFAs keep using the legacy
    | hard-coded issuer/signature logic in RfaController::createReportRFA.
    |
    */
    'signatory_cutoff_id' => 152156,
];
