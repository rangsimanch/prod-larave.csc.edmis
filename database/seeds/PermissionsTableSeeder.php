<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => '1',
                'title' => 'user_management_access',
            ],
            [
                'id'    => '2',
                'title' => 'permission_create',
            ],
            [
                'id'    => '3',
                'title' => 'permission_edit',
            ],
            [
                'id'    => '4',
                'title' => 'permission_show',
            ],
            [
                'id'    => '5',
                'title' => 'permission_delete',
            ],
            [
                'id'    => '6',
                'title' => 'permission_access',
            ],
            [
                'id'    => '7',
                'title' => 'role_create',
            ],
            [
                'id'    => '8',
                'title' => 'role_edit',
            ],
            [
                'id'    => '9',
                'title' => 'role_show',
            ],
            [
                'id'    => '10',
                'title' => 'role_delete',
            ],
            [
                'id'    => '11',
                'title' => 'role_access',
            ],
            [
                'id'    => '12',
                'title' => 'user_create',
            ],
            [
                'id'    => '13',
                'title' => 'user_edit',
            ],
            [
                'id'    => '14',
                'title' => 'user_show',
            ],
            [
                'id'    => '15',
                'title' => 'user_delete',
            ],
            [
                'id'    => '16',
                'title' => 'user_access',
            ],
            [
                'id'    => '17',
                'title' => 'jobtitle_create',
            ],
            [
                'id'    => '18',
                'title' => 'jobtitle_edit',
            ],
            [
                'id'    => '19',
                'title' => 'jobtitle_show',
            ],
            [
                'id'    => '20',
                'title' => 'jobtitle_delete',
            ],
            [
                'id'    => '21',
                'title' => 'jobtitle_access',
            ],
            [
                'id'    => '22',
                'title' => 'audit_log_show',
            ],
            [
                'id'    => '23',
                'title' => 'audit_log_access',
            ],
            [
                'id'    => '24',
                'title' => 'team_create',
            ],
            [
                'id'    => '25',
                'title' => 'team_edit',
            ],
            [
                'id'    => '26',
                'title' => 'team_show',
            ],
            [
                'id'    => '27',
                'title' => 'team_delete',
            ],
            [
                'id'    => '28',
                'title' => 'team_access',
            ],
            [
                'id'    => '29',
                'title' => 'user_alert_create',
            ],
            [
                'id'    => '30',
                'title' => 'user_alert_show',
            ],
            [
                'id'    => '31',
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => '32',
                'title' => 'user_alert_access',
            ],
            [
                'id'    => '33',
                'title' => 'department_create',
            ],
            [
                'id'    => '34',
                'title' => 'department_edit',
            ],
            [
                'id'    => '35',
                'title' => 'department_show',
            ],
            [
                'id'    => '36',
                'title' => 'department_delete',
            ],
            [
                'id'    => '37',
                'title' => 'department_access',
            ],
            [
                'id'    => '38',
                'title' => 'activity_calendar_report_access',
            ],
            [
                'id'    => '39',
                'title' => 'request_for_approval_access',
            ],
            [
                'id'    => '40',
                'title' => 'rfa_create',
            ],
            [
                'id'    => '41',
                'title' => 'rfa_edit',
            ],
            [
                'id'    => '42',
                'title' => 'rfa_show',
            ],
            [
                'id'    => '43',
                'title' => 'rfa_delete',
            ],
            [
                'id'    => '44',
                'title' => 'rfa_access',
            ],
            [
                'id'    => '45',
                'title' => 'rfatype_create',
            ],
            [
                'id'    => '46',
                'title' => 'rfatype_edit',
            ],
            [
                'id'    => '47',
                'title' => 'rfatype_show',
            ],
            [
                'id'    => '48',
                'title' => 'rfatype_delete',
            ],
            [
                'id'    => '49',
                'title' => 'rfatype_access',
            ],
            [
                'id'    => '50',
                'title' => 'rfa_comment_status_create',
            ],
            [
                'id'    => '51',
                'title' => 'rfa_comment_status_edit',
            ],
            [
                'id'    => '52',
                'title' => 'rfa_comment_status_show',
            ],
            [
                'id'    => '53',
                'title' => 'rfa_comment_status_delete',
            ],
            [
                'id'    => '54',
                'title' => 'rfa_comment_status_access',
            ],
            [
                'id'    => '55',
                'title' => 'rfa_document_status_create',
            ],
            [
                'id'    => '56',
                'title' => 'rfa_document_status_edit',
            ],
            [
                'id'    => '57',
                'title' => 'rfa_document_status_show',
            ],
            [
                'id'    => '58',
                'title' => 'rfa_document_status_delete',
            ],
            [
                'id'    => '59',
                'title' => 'rfa_document_status_access',
            ],
            [
                'id'    => '60',
                'title' => 'task_management_access',
            ],
            [
                'id'    => '61',
                'title' => 'task_status_create',
            ],
            [
                'id'    => '62',
                'title' => 'task_status_edit',
            ],
            [
                'id'    => '63',
                'title' => 'task_status_show',
            ],
            [
                'id'    => '64',
                'title' => 'task_status_delete',
            ],
            [
                'id'    => '65',
                'title' => 'task_status_access',
            ],
            [
                'id'    => '66',
                'title' => 'task_tag_create',
            ],
            [
                'id'    => '67',
                'title' => 'task_tag_edit',
            ],
            [
                'id'    => '68',
                'title' => 'task_tag_show',
            ],
            [
                'id'    => '69',
                'title' => 'task_tag_delete',
            ],
            [
                'id'    => '70',
                'title' => 'task_tag_access',
            ],
            [
                'id'    => '71',
                'title' => 'task_create',
            ],
            [
                'id'    => '72',
                'title' => 'task_edit',
            ],
            [
                'id'    => '73',
                'title' => 'task_show',
            ],
            [
                'id'    => '74',
                'title' => 'task_delete',
            ],
            [
                'id'    => '75',
                'title' => 'task_access',
            ],
            [
                'id'    => '76',
                'title' => 'tasks_calendar_access',
            ],
            [
                'id'    => '77',
                'title' => 'rfa_calendar_access',
            ],
            [
                'id'    => '78',
                'title' => 'file_manager_create',
            ],
            [
                'id'    => '79',
                'title' => 'file_manager_edit',
            ],
            [
                'id'    => '80',
                'title' => 'file_manager_show',
            ],
            [
                'id'    => '81',
                'title' => 'file_manager_delete',
            ],
            [
                'id'    => '82',
                'title' => 'file_manager_access',
            ],
            [
                'id'    => '83',
                'title' => 'construction_contract_create',
            ],
            [
                'id'    => '84',
                'title' => 'construction_contract_edit',
            ],
            [
                'id'    => '85',
                'title' => 'construction_contract_show',
            ],
            [
                'id'    => '86',
                'title' => 'construction_contract_delete',
            ],
            [
                'id'    => '87',
                'title' => 'construction_contract_access',
            ],

            [
                'id'    => '88',
                'title' => 'wbs_level_three_create',
            ],
            [
                'id'    => '89',
                'title' => 'wbs_level_three_edit',
            ],
            [
                'id'    => '90',
                'title' => 'wbs_level_three_show',
            ],
            [
                'id'    => '91',
                'title' => 'wbs_level_three_delete',
            ],
            [
                'id'    => '92',
                'title' => 'wbs_level_three_access',
            ],
            [
                'id'    => '93',
                'title' => 'wbslevelfour_create',
            ],
            [
                'id'    => '94',
                'title' => 'wbslevelfour_edit',
            ],
            [
                'id'    => '95',
                'title' => 'wbslevelfour_show',
            ],
            [
                'id'    => '96',
                'title' => 'wbslevelfour_delete',
            ],
            [
                'id'    => '97',
                'title' => 'wbslevelfour_access',
            ],
            [
                'id'    => '98',
                'title' => 'bo_q_create',
            ],
            [
                'id'    => '99',
                'title' => 'bo_q_edit',
            ],
            [
                'id'    => '100',
                'title' => 'bo_q_show',
            ],
            [
                'id'    => '101',
                'title' => 'bo_q_delete',
            ],
            [
                'id'    => '102',
                'title' => 'bo_q_access',
            ],
            [
                'id'    => '103',
                'title' => 'technical_document_access',
            ],
            [
                'id'    => '104',
                'title' => 'works_code_create',
            ],
            [
                'id'    => '105',
                'title' => 'works_code_edit',
            ],
            [
                'id'    => '106',
                'title' => 'works_code_show',
            ],
            [
                'id'    => '107',
                'title' => 'works_code_delete',
            ],
            [
                'id'    => '108',
                'title' => 'works_code_access',
            ],
            [
                'id'    => '109',
                'title' => 'submittals_rfa_create',
            ],
            [
                'id'    => '110',
                'title' => 'submittals_rfa_edit',
            ],
            [
                'id'    => '111',
                'title' => 'submittals_rfa_show',
            ],
            [
                'id'    => '112',
                'title' => 'submittals_rfa_delete',
            ],
            [
                'id'    => '113',
                'title' => 'submittals_rfa_access',
            ],
            
            // RFA Custom

            [
                'id'    => '114',
                'title' => 'rfa_edit_all',
            ],
            [
                'id'    => '115',
                'title' => 'rfa_revision',
            ],
            [
                'id'    => '116',
                'title' => 'rfa_panel_a',
            ],
            [
                'id'    => '117',
                'title' => 'rfa_panel_b',
            ],
            [
                'id'    => '118',
                'title' => 'rfa_panel_c',
            ],
            [
                'id'    => '119',
                'title' => 'rfa_pannel_d',
            ],
            [
                'id'    => '120',
                'title' => 'rfa_form',
            ],
        ];

        Permission::insert($permissions);
    }
}
