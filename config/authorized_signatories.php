<?php

/*
|--------------------------------------------------------------------------
| Authorized Signatories — Reference Data
|--------------------------------------------------------------------------
|
| REFERENCE ONLY — this file is not loaded by any application code.
| It records, per construction contract, which Authorized Signatories
| (and their job titles / signature settings) should be created via
| the Construction Contract edit page.
|
| Data was extracted from the legacy hard-coded issuer/signature blocks
| in RfaController::createReportRFA (contracts C2-1 ... C4-7).
|
| Signatory-level fields:
|   - name      : printed exactly as shown on the PDF, parentheses included
|   - signature : file name inside public/png-asset/ (null = no signature)
|                 — one signature image per person, shared by all job titles
|   - size      : [signature_size_h, signature_size_w]
|
| Jobtitle-level fields (position depends on the job title layout):
|   - jobtitle  : the issuer job title line printed under the name
|   - pos       : [signature_position_top, signature_position_left]
|   - jt_lf     : jobtitle_position_lf
|   - issue     : [issue_position_lf, issue_position_lf_sub]
|
|   - active    : suggested initial state — only "current" signers are
|                 marked active; historical signers are kept here for
|                 reference and may be added as inactive or skipped
|
| NOTE: for signers whose legacy code used different signature files per
| job title (e.g. Gao Liang: CAN_signature_4 vs _5), the file recorded
| here is the one of the *current/default* job title. If a different file
| is genuinely needed per job title, create a separate signatory entry.
|
*/

return [

    'C2-1' => [
        [
            'name' => '( Sitthichai Pimsawat )', 'name_rfi' => 'Sitthichai Pimsawat', 'signature' => 'Signature_CEC.png', 'size' => [40, 40], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'ผู้จัดการโครงการ', 'pos' => [415, 275], 'jt_lf' => 275, 'issue' => [255, 483], 'active' => true, 'default' => true],
            ],
        ],
    ],

    'C3-1' => [
        [
            'name' => '(นายศรายุธ ทองยศ)', 'signature' => 'ITDCREC_signature_1.png', 'size' => [55, 55], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'ผู้รับมอบอำนาจกระทำการแทน (Authorized Representatives) ITD-CREC No.10 Joint Venture', 'pos' => [400, 220], 'jt_lf' => 165, 'issue' => [215, 485], 'active' => true, 'default' => true],
            ],
        ],
        [
            'name' => '(Mr.Yao Dechen)', 'signature' => 'ITDCREC_signature_2-4.png', 'size' => [60, 60], 'active' => true, 'default' => false,
            'jobtitles' => [
                ['jobtitle' => 'ผู้รับมอบอำนาจกระทำการแทน (Authorized Representatives) ITD-CREC No.10 Joint Venture', 'pos' => [415, 290], 'jt_lf' => 165, 'issue' => [215, 485], 'active' => true, 'default' => true],
            ],
        ],
        [
            'name' => '(Mr.Yu Dongxin)', 'signature' => 'ITDCREC_signature_2.png', 'size' => [80, 80], 'active' => false, 'default' => false,
            'jobtitles' => [
                ['jobtitle' => 'ผู้รับมอบอำนาจกระทำการแทน (Authorized Representatives) ITD-CREC No.10 Joint Venture', 'pos' => [415, 290], 'jt_lf' => 165, 'issue' => [215, 485], 'active' => false, 'default' => false],
            ],
        ],
        [
            'name' => '(Mr.Luan Zhiqiang)', 'signature' => 'ITDCREC_signature_2-2.png', 'size' => [40, 40], 'active' => false, 'default' => false,
            'jobtitles' => [
                ['jobtitle' => 'ผู้รับมอบอำนาจกระทำการแทน (Authorized Representatives) ITD-CREC No.10 Joint Venture', 'pos' => [415, 290], 'jt_lf' => 165, 'issue' => [215, 485], 'active' => false, 'default' => false],
            ],
        ],
        [
            'name' => '(Mr.Zhang Cong)', 'signature' => 'ITDCREC_signature_2-3.png', 'size' => [40, 40], 'active' => false, 'default' => false,
            'jobtitles' => [
                ['jobtitle' => 'ผู้รับมอบอำนาจกระทำการแทน (Authorized Representatives) ITD-CREC No.10 Joint Venture', 'pos' => [415, 290], 'jt_lf' => 165, 'issue' => [215, 485], 'active' => false, 'default' => false],
            ],
        ],
    ],

    'C3-2' => [
        [
            'name' => '( ธนนท์ ดอกลัดดา )', 'name_rfi' => 'ธนนท์ ดอกลัดดา', 'signature' => 'NWR_signature.png', 'size' => [55, 55], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'ผู้จัดการโครงการ', 'pos' => [400, 280], 'jt_lf' => 275, 'issue' => [260, 485], 'active' => true, 'default' => true],
            ],
        ],
    ],

    'C3-3' => [
        [
            'name' => '( กิตติพัฒน์ พฤกษ์ธนัทพงศ์ )', 'name_rfi' => 'กิตติพัฒน์ พฤกษ์ธนัทพงศ์', 'signature' => 'TEI_signature.png', 'size' => [60, 60], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'รองผู้จัดการโครงการ', 'pos' => [405, 280], 'jt_lf' => 275, 'issue' => [247, 480], 'active' => true, 'default' => true],
            ],
        ],
    ],

    'C3-4' => [
        [
            'name' => '( สุดเขต ไวทยาคม )', 'name_rfi' => 'สุดเขต ไวทยาคม', 'signature' => 'ITD-3_signature.png', 'size' => [100, 100], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'ผู้อำนวยการโครงการ', 'pos' => [410, 250], 'jt_lf' => 275, 'issue' => [265, 480], 'active' => true, 'default' => true],
            ],
        ],
        [
            'name' => '( มฆา  อัศวราชันย์ )', 'name_rfi' => 'มฆา  อัศวราชันย์', 'signature' => 'ITD_signature.png', 'size' => null, 'active' => false, 'default' => false,
            'jobtitles' => [
                ['jobtitle' => 'ผู้อำนวยการโครงการ', 'pos' => [400, 280], 'jt_lf' => 275, 'issue' => [260, 489], 'active' => false, 'default' => false],
            ],
        ],
    ],

    'C3-5' => [
        [
            'name' => '( นายนรุตม์ชัย  สัมมาวิจิตร )', 'name_rfi' => 'Narutchai Summawijitra', 'signature' => 'SPTK_signature_4.png', 'size' => [60, 60], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'ผู้อำนวยการโครงการ', 'pos' => [414, 265], 'jt_lf' => 275, 'issue' => [245, 480], 'active' => true, 'default' => true],
            ],
        ],
        [
            'name' => '( MR.ZHANG DAPENG )', 'name_rfi' => 'MR.ZHANG DAPENG', 'signature' => 'SPTK_signature.png', 'size' => [60, 60], 'active' => false, 'default' => false,
            'jobtitles' => [
                ['jobtitle' => 'ผู้จัดการโครงการ', 'pos' => [415, 265], 'jt_lf' => 275, 'issue' => [245, 480], 'active' => false, 'default' => false],
            ],
        ],
    ],

    'C4-3' => [
        [
            'name' => '( Gao Liang )', 'name_rfi' => 'Gao Liang', 'signature' => 'CAN_signature_4.png', 'size' => [60, 60], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'For Project Manager', 'pos' => [415, 260], 'jt_lf' => 255, 'issue' => [260, 489], 'active' => true, 'default' => true],
                ['jobtitle' => 'Acting Project Manager', 'pos' => [400, 260], 'jt_lf' => 255, 'issue' => [260, 489], 'active' => false, 'default' => false],
                ['jobtitle' => 'Construction Manager', 'pos' => [415, 260], 'jt_lf' => 255, 'issue' => [260, 489], 'active' => false, 'default' => false],
                ['jobtitle' => 'Project Manager', 'pos' => [415, 260], 'jt_lf' => 255, 'issue' => [260, 489], 'active' => false, 'default' => false],
            ],
        ],
        [
            'name' => '( Zhou Jiayi )', 'name_rfi' => 'Zhou Jiayi', 'signature' => 'CAN_signature_2.png', 'size' => [60, 60], 'active' => false, 'default' => false,
            'jobtitles' => [
                ['jobtitle' => 'Project Manager', 'pos' => [400, 260], 'jt_lf' => 265, 'issue' => [260, 489], 'active' => false, 'default' => false],
                ['jobtitle' => 'Project Director', 'pos' => [400, 260], 'jt_lf' => 265, 'issue' => [260, 489], 'active' => false, 'default' => false],
            ],
        ],
        [
            'name' => '( Dong Weihong )', 'name_rfi' => 'Dong Weihong', 'signature' => 'CAN_signature.png', 'size' => [60, 60], 'active' => false, 'default' => false,
            'jobtitles' => [
                ['jobtitle' => 'For Project manager CAN', 'pos' => [400, 260], 'jt_lf' => 255, 'issue' => [260, 489], 'active' => false, 'default' => false],
            ],
        ],
        [
            'name' => '( Wang Kan )', 'name_rfi' => 'Wang Kan', 'signature' => 'CAN_signature_3.png', 'size' => [60, 60], 'active' => false, 'default' => false,
            'jobtitles' => [
                ['jobtitle' => 'For Project Manager', 'pos' => [400, 260], 'jt_lf' => 255, 'issue' => [260, 489], 'active' => false, 'default' => false],
            ],
        ],
    ],

    'C4-4' => [
        [
            'name' => '( พิเชฐ  ภาพปัญญาพร )', 'name_rfi' => 'พิเชฐ  ภาพปัญญาพร', 'signature' => 'ITD2_signature.png', 'size' => [60, 60], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'ผู้อำนวยการโครงการ', 'pos' => [405, 280], 'jt_lf' => 275, 'issue' => [260, 489], 'active' => true, 'default' => true],
            ],
        ],
    ],

    'C4-2' => [
        [
            'name' => '( นายธีรพงษ์ วิธีเจริญ )', 'name_rfi' => 'นายธีรพงษ์ วิธีเจริญ', 'signature' => null, 'size' => [50, 50], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'ผู้รับมอบอำนาจ', 'pos' => [415, 278], 'jt_lf' => 275, 'issue' => [255, 489], 'active' => true, 'default' => true],
            ],
        ],
    ],

    'C4-6' => [
        [
            'name' => '( นายธีรพงษ์ วิธีเจริญ )', 'name_rfi' => 'นายธีรพงษ์ วิธีเจริญ', 'signature' => null, 'size' => [50, 50], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'ผู้รับมอบอำนาจ', 'pos' => [415, 278], 'jt_lf' => 275, 'issue' => [255, 489], 'active' => true, 'default' => true],
            ],
        ],
    ],

    'C4-7' => [
        [
            'name' => '( สุทิน สังข์หิรัญ )', 'name_rfi' => 'สุทิน สังข์หิรัญ', 'signature' => 'CIVIL_signature.png', 'size' => [50, 50], 'active' => true, 'default' => true,
            'jobtitles' => [
                ['jobtitle' => 'ผู้จัดการโครงการ', 'pos' => [407, 278], 'jt_lf' => 275, 'issue' => [265, 423], 'active' => true, 'default' => true],
            ],
        ],
    ],

];
