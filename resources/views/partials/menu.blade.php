<aside class="main-sidebar">
    <section class="sidebar" style="height: auto;">
        <ul class="sidebar-menu tree" data-widget="tree">
            <li>
                <select class="searchable-field form-control">

                </select>
            </li>
            <li>
                <a href="{{ route("admin.home") }}">
                    <i class="fas fa-fw fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>
            @can('rfa_access')
                <li class="{{ request()->is('admin/rfas') || request()->is('admin/rfas/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.rfas.index") }}">
                        <i class="fa-fw fas fa-book">

                        </i>
                        <span>{{ trans('cruds.rfa.title') }}</span>
                    </a>
                </li>
            @endcan
            @can('request_for_inspection_access')
                <li class="{{ request()->is('admin/request-for-inspections') || request()->is('admin/request-for-inspections/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.request-for-inspections.index") }}">
                        <i class="fa-fw fas fa-book">

                        </i>
                        <span>{{ trans('cruds.requestForInspection.title') }}</span>
                    </a>
                </li>
            @endcan
            @can('request_for_information_access')
                <li class="{{ request()->is('admin/request-for-informations') || request()->is('admin/request-for-informations/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.request-for-informations.index") }}">
                        <i class="fa-fw fas fa-book">

                        </i>
                        <span>{{ trans('cruds.requestForInformation.title') }}</span>
                    </a>
                </li>
            @endcan
            @can('site_warning_notice_access')
                <li class="{{ request()->is('admin/site-warning-notices') || request()->is('admin/site-warning-notices/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.site-warning-notices.index") }}">
                        <i class="fa-fw fas fa-exclamation-triangle">

                        </i>
                        <span>{{ trans('cruds.siteWarningNotice.title') }}</span>
                    </a>
                </li>
            @endcan
            @can('non_conformance_notice_access')
                <li class="{{ request()->is('admin/non-conformance-notices') || request()->is('admin/non-conformance-notices/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.non-conformance-notices.index") }}">
                        <i class="fa-fw fas fa-file-signature">

                        </i>
                        <span>{{ trans('cruds.nonConformanceNotice.title') }}</span>
                    </a>
                </li>
            @endcan
            @can('non_conformance_report_access')
                <li class="{{ request()->is('admin/non-conformance-reports') || request()->is('admin/non-conformance-reports/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.non-conformance-reports.index") }}">
                        <i class="fa-fw fas fa-file-signature">

                        </i>
                        <span>{{ trans('cruds.nonConformanceReport.title') }}</span>
                    </a>
                </li>
            @endcan
            @can('request_document_setting')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-cog">

                        </i>
                        <span>{{ trans('cruds.requestForApproval.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('rfatype_access')
                            <li class="{{ request()->is('admin/rfatypes') || request()->is('admin/rfatypes/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.rfatypes.index") }}">
                                    <i class="fa-fw fas fa-clipboard-list">

                                    </i>
                                    <span>{{ trans('cruds.rfatype.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('rfa_comment_status_access')
                            <li class="{{ request()->is('admin/rfa-comment-statuses') || request()->is('admin/rfa-comment-statuses/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.rfa-comment-statuses.index") }}">
                                    <i class="fa-fw fas fa-tasks">

                                    </i>
                                    <span>{{ trans('cruds.rfaCommentStatus.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('rfa_document_status_access')
                            <li class="{{ request()->is('admin/rfa-document-statuses') || request()->is('admin/rfa-document-statuses/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.rfa-document-statuses.index") }}">
                                    <i class="fa-fw fas fa-spinner">

                                    </i>
                                    <span>{{ trans('cruds.rfaDocumentStatus.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('rfa_calendar_access')
                            <li class="{{ request()->is('admin/rfa-calendars') || request()->is('admin/rfa-calendars/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.rfa-calendars.index") }}">
                                    <i class="fa-fw far fa-calendar-alt">

                                    </i>
                                    <span>{{ trans('cruds.rfaCalendar.title') }}</span>
                                </a>
                            </li>
                        @endcan

                        <!-- WBS LV.1 (Project) -->
                        @can('wbs_level_five_access')
                            <li class="{{ request()->is('admin/wbs-level-fives') || request()->is('admin/wbs-level-fives/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.wbs-level-fives.index") }}">
                                    <i class="fa-fw fas fa-cogs">

                                    </i>
                                    <span>{{ trans('cruds.wbsLevelFive.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        <!-- WBS LV.2 (Contract) -->
                        @can('wbs_level_one_access')
                            <li class="{{ request()->is('admin/wbs-level-ones') || request()->is('admin/wbs-level-ones/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.wbs-level-ones.index") }}">
                                    <i class="fa-fw fas fa-cogs">

                                    </i>
                                    <span>{{ trans('cruds.wbsLevelOne.title') }}</span>
                                </a>
                            </li>
                        @endcan

                        <!-- WBS LV.3 (BoQ) -->
                        @can('bo_q_access')
                            <li class="{{ request()->is('admin/bo-qs') || request()->is('admin/bo-qs/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.bo-qs.index") }}">
                                    <i class="fa-fw fas fa-file-invoice">

                                    </i>
                                    <span>{{ trans('cruds.boQ.title') }}</span>
                                </a>
                            </li>
                        @endcan

                        <!-- WBS LV.4 (Work Type) -->
                        @can('wbs_level_three_access')
                            <li class="{{ request()->is('admin/wbs-level-threes') || request()->is('admin/wbs-level-threes/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.wbs-level-threes.index") }}">
                                    <i class="fa-fw fas fa-cogs">

                                    </i>
                                    <span>{{ trans('cruds.wbsLevelThree.title') }}</span>
                                </a>
                            </li>
                        @endcan

                        <!-- WBS LV.5 (Element) -->
                        @can('wbslevelfour_access')
                            <li class="{{ request()->is('admin/wbslevelfours') || request()->is('admin/wbslevelfours/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.wbslevelfours.index") }}">
                                    <i class="fa-fw fas fa-cogs">

                                    </i>
                                    <span>{{ trans('cruds.wbslevelfour.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        
                        @can('boq_item_access')
                            <li class="{{ request()->is('admin/boq-items') || request()->is('admin/boq-items/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.boq-items.index") }}">
                                    <i class="fa-fw fas fa-file-invoice-dollar">

                                    </i>
                                    <span>{{ trans('cruds.boqItem.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('submittals_rfa_access')
                            <li class="{{ request()->is('admin/submittals-rfas') || request()->is('admin/submittals-rfas/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.submittals-rfas.index") }}">
                                    <i class="fa-fw fas fa-cogs">

                                    </i>
                                    <span>{{ trans('cruds.submittalsRfa.title') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            
            <!-- SRT DOCUMENT -->
            @can('srt_document_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-archive">

                        </i>
                        <span>{{ trans('cruds.srtDocument.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('srt_input_document_access')
                            <li class="{{ request()->is("admin/srt-input-documents") || request()->is("admin/srt-input-documents/*") ? "active" : "" }}">
                                <a href="{{ route("admin.srt-input-documents.index") }}">
                                    <i class="fa-fw fas fa-passport">

                                    </i>
                                    <span>{{ trans('cruds.srtInputDocument.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('srt_head_office_document_access')
                            <li class="{{ request()->is("admin/srt-head-office-documents") || request()->is("admin/srt-head-office-documents/*") ? "active" : "" }}">
                                <a href="{{ route("admin.srt-head-office-documents.index") }}">
                                    <i class="fa-fw fas fa-building">

                                    </i>
                                    <span>{{ trans('cruds.srtHeadOfficeDocument.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('srt_pd_document_access')
                            <li class="{{ request()->is("admin/srt-pd-documents") || request()->is("admin/srt-pd-documents/*") ? "active" : "" }}">
                                <a href="{{ route("admin.srt-pd-documents.index") }}">
                                    <i class="fa-fw fas fa-building">

                                    </i>
                                    <span>{{ trans('cruds.srtPdDocument.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('srt_pe_document_access')
                            <li class="{{ request()->is("admin/srt-pe-documents") || request()->is("admin/srt-pe-documents/*") ? "active" : "" }}">
                                <a href="{{ route("admin.srt-pe-documents.index") }}">
                                    <i class="fa-fw fas fa-building">

                                    </i>
                                    <span>{{ trans('cruds.srtPeDocument.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('srt_external_document_access')
                            <li class="{{ request()->is("admin/srt-external-documents") || request()->is("admin/srt-external-documents/*") ? "active" : "" }}">
                                <a href="{{ route("admin.srt-external-documents.index") }}">
                                    <i class="fa-fw fas fa-door-open">

                                    </i>
                                    <span>{{ trans('cruds.srtExternalDocument.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('srt_document_status_access')
                            <li class="{{ request()->is("admin/srt-document-statuses") || request()->is("admin/srt-document-statuses/*") ? "active" : "" }}">
                                <a href="{{ route("admin.srt-document-statuses.index") }}">
                                    <i class="fa-fw fas fa-cogs">

                                    </i>
                                    <span>{{ trans('cruds.srtDocumentStatus.title') }}</span>

                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @can('task_access')
                <li class="{{ request()->is('admin/tasks') || request()->is('admin/tasks/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.tasks.index") }}">
                        <i class="fa-fw fas fa-marker">

                        </i>
                        <span>{{ trans('cruds.task.title') }}</span>
                    </a>
                </li>
            @endcan
            @can('task_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-cog">

                        </i>
                        <span>{{ trans('cruds.taskManagement.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('task_status_access')
                            <li class="{{ request()->is('admin/task-statuses') || request()->is('admin/task-statuses/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.task-statuses.index") }}">
                                    <i class="fa-fw fas fa-server">

                                    </i>
                                    <span>{{ trans('cruds.taskStatus.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('task_tag_access')
                            <li class="{{ request()->is('admin/task-tags') || request()->is('admin/task-tags/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.task-tags.index") }}">
                                    <i class="fa-fw fas fa-server">

                                    </i>
                                    <span>{{ trans('cruds.taskTag.title') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            <!-- @can('tasks_calendar_access')
                <li class="{{ request()->is('admin/tasks-calendars') || request()->is('admin/tasks-calendars/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.tasks-calendars.index") }}">
                        <i class="fa-fw fas fa-calendar">

                        </i>
                        <span>{{ trans('cruds.tasksCalendar.title') }}</span>
                    </a>
                </li>
            @endcan -->
            @can('daily_construction_activity_access')
                            <li class="{{ request()->is('admin/daily-construction-activities') || request()->is('admin/daily-construction-activities/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.daily-construction-activities.index") }}">
                                    <i class="fa-fw fas fa-camera">

                                    </i>
                                    <span>{{ trans('cruds.dailyConstructionActivity.title') }}</span>
                                </a>
                            </li>
            @endcan
            @can('daily_request_access')
                <li class="{{ request()->is('admin/daily-requests') || request()->is('admin/daily-requests/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.daily-requests.index") }}">
                        <i class="fa-fw fas fa-file-import">

                        </i>
                        <span>{{ trans('cruds.dailyRequest.title') }}</span>
                    </a>
                </li>
            @endcan
            @can('daily_report_access')
                <li class="{{ request()->is('admin/daily-reports') || request()->is('admin/daily-reports/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.daily-reports.index") }}">
                        <i class="fa-fw fas fa-file-export">

                        </i>
                        <span>{{ trans('cruds.dailyReport.title') }}</span>
                    </a>
                </li>
            @endcan
            
            <!-- Letter -->
            @can('letter_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw far fa-envelope">

                        </i>
                        <span>{{ trans('cruds.letter.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('add_letter_access')
                            <li class="{{ request()->is('admin/add-letters') || request()->is('admin/add-letters/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.add-letters.index") }}">
                                    <i class="fa-fw fas fa-at">

                                    </i>
                                    <span>{{ trans('cruds.addLetter.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @if(Auth::user()->team_id == 1)
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-envelope">

                                    </i>
                                    <span>{{ trans('cruds.letterSrt.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @if('srt_inbox_access')
                                        <li class="{{ request()->is("admin/srt-inboxes") || request()->is("admin/srt-inboxes/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.srt-inboxes.index") }}">
                                                <i class="fa-fw far fa-envelope">

                                                </i>
                                                <span>{{ trans('cruds.srtInbox.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('srt_sent_access')
                                        <li class="{{ request()->is("admin/srt-sents") || request()->is("admin/srt-sents/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.srt-sents.index") }}">
                                                <i class="fa-fw far fa-share-square">

                                                </i>
                                                <span>{{ trans('cruds.srtSent.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->team_id == 2)
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-envelope">

                                    </i>
                                    <span>{{ trans('cruds.letterPmc.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('pmc_inbox_access')
                                        <li class="{{ request()->is("admin/pmc-inboxes") || request()->is("admin/pmc-inboxes/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.pmc-inboxes.index") }}">
                                                <i class="fa-fw far fa-envelope">

                                                </i>
                                                <span>{{ trans('cruds.pmcInbox.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('pmc_sent_access')
                                        <li class="{{ request()->is("admin/pmc-sents") || request()->is("admin/pmc-sents/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.pmc-sents.index") }}">
                                                <i class="fa-fw far fa-share-square">

                                                </i>
                                                <span>{{ trans('cruds.pmcSent.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->team_id == 3)
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-envelope">

                                    </i>
                                    <span>{{ trans('cruds.letterCsc.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('csc_inbox_access')
                                        <li class="{{ request()->is("admin/csc-inboxes") || request()->is("admin/csc-inboxes/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.csc-inboxes.index") }}">
                                                <i class="fa-fw far fa-envelope">

                                                </i>
                                                <span>{{ trans('cruds.cscInbox.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('csc_sent_access')
                                        <li class="{{ request()->is("admin/csc-sents") || request()->is("admin/csc-sents/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.csc-sents.index") }}">
                                                <i class="fa-fw far fa-share-square">

                                                </i>
                                                <span>{{ trans('cruds.cscSent.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->team_id == 4)
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-envelope">

                                    </i>
                                    <span>{{ trans('cruds.letterCcsp.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('ccsp_inbox_access')
                                        <li class="{{ request()->is("admin/ccsp-inboxes") || request()->is("admin/ccsp-inboxes/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.ccsp-inboxes.index") }}">
                                                <i class="fa-fw far fa-envelope">

                                                </i>
                                                <span>{{ trans('cruds.ccspInbox.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('ccsp_sent_access')
                                        <li class="{{ request()->is("admin/ccsp-sents") || request()->is("admin/ccsp-sents/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.ccsp-sents.index") }}">
                                                <i class="fa-fw far fa-share-square">

                                                </i>
                                                <span>{{ trans('cruds.ccspSent.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->team_id == 5)
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-envelope">

                                    </i>
                                    <span>{{ trans('cruds.letterNwr.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('nwr_inbox_access')
                                        <li class="{{ request()->is("admin/nwr-inboxes") || request()->is("admin/nwr-inboxes/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.nwr-inboxes.index") }}">
                                                <i class="fa-fw far fa-envelope">

                                                </i>
                                                <span>{{ trans('cruds.nwrInbox.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('nwr_sent_access')
                                        <li class="{{ request()->is("admin/nwr-sents") || request()->is("admin/nwr-sents/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.nwr-sents.index") }}">
                                                <i class="fa-fw far fa-share-square">

                                                </i>
                                                <span>{{ trans('cruds.nwrSent.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->team_id == 6)
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-envelope">

                                    </i>
                                    <span>{{ trans('cruds.letterCivil.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('civil_inbox_access')
                                        <li class="{{ request()->is("admin/civil-inboxes") || request()->is("admin/civil-inboxes/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.civil-inboxes.index") }}">
                                                <i class="fa-fw far fa-envelope">

                                                </i>
                                                <span>{{ trans('cruds.civilInbox.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('cilvil_sent_access')
                                        <li class="{{ request()->is("admin/cilvil-sents") || request()->is("admin/cilvil-sents/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.cilvil-sents.index") }}">
                                                <i class="fa-fw far fa-share-square">

                                                </i>
                                                <span>{{ trans('cruds.cilvilSent.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->team_id == 7)
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-envelope">

                                    </i>
                                    <span>{{ trans('cruds.letterTei.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('tei_inbox_access')
                                        <li class="{{ request()->is("admin/tei-inboxes") || request()->is("admin/tei-inboxes/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.tei-inboxes.index") }}">
                                                <i class="fa-fw far fa-envelope">

                                                </i>
                                                <span>{{ trans('cruds.teiInbox.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('tei_sent_access')
                                        <li class="{{ request()->is("admin/tei-sents") || request()->is("admin/tei-sents/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.tei-sents.index") }}">
                                                <i class="fa-fw far fa-share-square">

                                                </i>
                                                <span>{{ trans('cruds.teiSent.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                        @if(Auth::user()->team_id == 8)
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-envelope">

                                    </i>
                                    <span>{{ trans('cruds.letterSptk.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('sptk_inbox_access')
                                        <li class="{{ request()->is("admin/sptk-inboxes") || request()->is("admin/sptk-inboxes/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.sptk-inboxes.index") }}">
                                                <i class="fa-fw far fa-envelope">

                                                </i>
                                                <span>{{ trans('cruds.sptkInbox.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('sptk_sent_access')
                                        <li class="{{ request()->is("admin/sptk-sents") || request()->is("admin/sptk-sents/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.sptk-sents.index") }}">
                                                <i class="fa-fw far fa-share-square">

                                                </i>
                                                <span>{{ trans('cruds.sptkSent.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->team_id == 13)
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-envelope">

                                    </i>
                                    <span>{{ trans('cruds.letterItd.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('itd_inbox_access')
                                        <li class="{{ request()->is("admin/itd-inboxes") || request()->is("admin/itd-inboxes/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.itd-inboxes.index") }}">
                                                <i class="fa-fw far fa-envelope">

                                                </i>
                                                <span>{{ trans('cruds.itdInbox.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                    @can('itd_sent_access')
                                        <li class="{{ request()->is("admin/itd-sents") || request()->is("admin/itd-sents/*") ? "active" : "" }}">
                                            <a href="{{ route("admin.itd-sents.index") }}">
                                                <i class="fa-fw far fa-share-square">

                                                </i>
                                                <span>{{ trans('cruds.itdSent.title') }}</span>

                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif

                    </ul>
                </li>
            @endcan
            
            <!-- Download System -->
            @can('download_system_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-cloud-download-alt">

                        </i>
                        <span>{{ trans('cruds.downloadSystem.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('contract_and_component_access')
                            <li class="{{ request()->is('admin/contract-and-components') || request()->is('admin/contract-and-components/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.contract-and-components.index") }}">
                                    <i class="fa-fw fas fa-book-open">

                                    </i>
                                    <span>{{ trans('cruds.contractAndComponent.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('department_document_access')
                            <li class="{{ request()->is('admin/department-documents') || request()->is('admin/department-documents/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.department-documents.index") }}">
                                    <i class="fa-fw far fa-building">

                                    </i>
                                    <span>{{ trans('cruds.departmentDocument.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('meeting_document_access')
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-chalkboard-teacher">

                                    </i>
                                    <span>{{ trans('cruds.meetingDocument.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('meeting_monthly_access')
                                        <li class="{{ request()->is('admin/meeting-monthlies') || request()->is('admin/meeting-monthlies/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.meeting-monthlies.index") }}">
                                                <i class="fa-fw fas fa-file-invoice">

                                                </i>
                                                <span>{{ trans('cruds.meetingMonthly.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('meeting_weekly_access')
                                        <li class="{{ request()->is('admin/meeting-weeklies') || request()->is('admin/meeting-weeklies/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.meeting-weeklies.index") }}">
                                                <i class="fa-fw fas fa-file-invoice">

                                                </i>
                                                <span>{{ trans('cruds.meetingWeekly.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('meeting_other_access')
                                        <li class="{{ request()->is('admin/meeting-others') || request()->is('admin/meeting-others/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.meeting-others.index") }}">
                                                <i class="fa-fw far fa-clipboard">

                                                </i>
                                                <span>{{ trans('cruds.meetingOther.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('manual_access')
                            <li class="{{ request()->is('admin/manuals') || request()->is('admin/manuals/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.manuals.index") }}">
                                    <i class="fa-fw fas fa-book">

                                    </i>
                                    <span>{{ trans('cruds.manual.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('procedure_access')
                            <li class="{{ request()->is('admin/procedures') || request()->is('admin/procedures/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.procedures.index") }}">
                                    <i class="fa-fw fas fa-circle-notch">

                                    </i>
                                    <span>{{ trans('cruds.procedure.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('drone_vdo_recorded_access')
                            <li class="{{ request()->is('admin/drone-vdo-recordeds') || request()->is('admin/drone-vdo-recordeds/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.drone-vdo-recordeds.index") }}">
                                    <i class="fa-fw fas fa-cloud-upload-alt">

                                    </i>
                                    <span>{{ trans('cruds.droneVdoRecorded.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        <!-- @can('daily_construction_activity_access')
                            <li class="{{ request()->is('admin/daily-construction-activities') || request()->is('admin/daily-construction-activities/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.daily-construction-activities.index") }}">
                                    <i class="fa-fw fas fa-camera">

                                    </i>
                                    <span>{{ trans('cruds.dailyConstructionActivity.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('records_of_visitor_access') -->
                            <li class="{{ request()->is('admin/records-of-visitors') || request()->is('admin/records-of-visitors/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.records-of-visitors.index") }}">
                                    <i class="fa-fw fas fa-male">

                                    </i>
                                    <span>{{ trans('cruds.recordsOfVisitor.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('check_sheet_access')
                            <li class="{{ request()->is('admin/check-sheets') || request()->is('admin/check-sheets/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.check-sheets.index") }}">
                                    <i class="fa-fw fas fa-check-square">

                                    </i>
                                    <span>{{ trans('cruds.checkSheet.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('check_list_access')
                            <li class="{{ request()->is('admin/check-lists') || request()->is('admin/check-lists/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.check-lists.index") }}">
                                    <i class="fa-fw fas fa-check-double">

                                    </i>
                                    <span>{{ trans('cruds.checkList.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('drawing_access')
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-drafting-compass">

                                    </i>
                                    <span>{{ trans('cruds.drawing.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('add_drawing_access')
                                        <li class="{{ request()->is('admin/add-drawings') || request()->is('admin/add-drawings/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.add-drawings.index") }}">
                                                <i class="fa-fw fas fa-feather-alt">

                                                </i>
                                                <span>{{ trans('cruds.addDrawing.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('shop_drawing_access')
                                        <li class="{{ request()->is('admin/shop-drawings') || request()->is('admin/shop-drawings/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.shop-drawings.index") }}">
                                                <i class="fa-fw fas fa-file-pdf">

                                                </i>
                                                <span>{{ trans('cruds.shopDrawing.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('as_build_plan_access')
                                        <li class="{{ request()->is('admin/as-build-plans') || request()->is('admin/as-build-plans/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.as-build-plans.index") }}">
                                                <i class="fa-fw fas fa-hotel">

                                                </i>
                                                <span>{{ trans('cruds.asBuildPlan.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('commercial_file_access')
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-hand-holding-usd">

                                    </i>
                                    <span>{{ trans('cruds.commercialFile.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('variation_order_access')
                                        <li class="{{ request()->is('admin/variation-orders') || request()->is('admin/variation-orders/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.variation-orders.index") }}">
                                                <i class="fa-fw fas fa-dollar-sign">

                                                </i>
                                                <span>{{ trans('cruds.variationOrder.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('provisional_sum_access')
                                        <li class="{{ request()->is('admin/provisional-sums') || request()->is('admin/provisional-sums/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.provisional-sums.index") }}">
                                                <i class="fa-fw fas fa-file-invoice-dollar">

                                                </i>
                                                <span>{{ trans('cruds.provisionalSum.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('interim_payment_access')
                                        <li class="{{ request()->is('admin/interim-payments') || request()->is('admin/interim-payments/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.interim-payments.index") }}">
                                                <i class="fa-fw fas fa-hand-holding-usd">

                                                </i>
                                                <span>{{ trans('cruds.interimPayment.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('monthly_report_access')
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-chart-bar">

                                    </i>
                                    <span>{{ trans('cruds.monthlyReport.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('monthly_report_csc_access')
                                        <li class="{{ request()->is('admin/monthly-report-cscs') || request()->is('admin/monthly-report-cscs/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.monthly-report-cscs.index") }}">
                                                <i class="fa-fw fas fa-user-edit">

                                                </i>
                                                <span>{{ trans('cruds.monthlyReportCsc.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('monthly_report_constructor_access')
                                        <li class="{{ request()->is('admin/monthly-report-constructors') || request()->is('admin/monthly-report-constructors/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.monthly-report-constructors.index") }}">
                                                <i class="fa-fw fas fa-user-tie">

                                                </i>
                                                <span>{{ trans('cruds.monthlyReportConstructor.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                        @can('documents_and_assignment_access')
                            <li class="{{ request()->is('admin/documents-and-assignments') || request()->is('admin/documents-and-assignments/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.documents-and-assignments.index") }}">
                                    <i class="fa-fw fas fa-hands-helping">

                                    </i>
                                    <span>{{ trans('cruds.documentsAndAssignment.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('download_system_setting_access')
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-fw fas fa-cogs">

                                    </i>
                                    <span>{{ trans('cruds.downloadSystemSetting.title') }}</span>
                                    <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    @can('document_tag_access')
                                        <li class="{{ request()->is('admin/document-tags') || request()->is('admin/document-tags/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.document-tags.index") }}">
                                                <i class="fa-fw fas fa-bookmark">

                                                </i>
                                                <span>{{ trans('cruds.documentTag.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('download_system_activity_access')
                                        <li class="{{ request()->is('admin/download-system-activities') || request()->is('admin/download-system-activities/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.download-system-activities.index") }}">
                                                <i class="fa-fw fas fa-cogs">

                                                </i>
                                                <span>{{ trans('cruds.downloadSystemActivity.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('download_system_work_access')
                                        <li class="{{ request()->is('admin/download-system-works') || request()->is('admin/download-system-works/*') ? 'active' : '' }}">
                                            <a href="{{ route("admin.download-system-works.index") }}">
                                                <i class="fa-fw fas fa-cogs">

                                                </i>
                                                <span>{{ trans('cruds.downloadSystemWork.title') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('user_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-users">

                        </i>
                        <span>{{ trans('cruds.userManagement.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('user_access')
                            <li class="{{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.users.index") }}">
                                    <i class="fa-fw fas fa-user">

                                    </i>
                                    <span>{{ trans('cruds.user.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('organization_access')
                            <li class="{{ request()->is("admin/organizations") || request()->is("admin/organizations/*") ? "active" : "" }}">
                                <a href="{{ route("admin.organizations.index") }}">
                                    <i class="fa-fw fas fa-sitemap">

                                    </i>
                                    <span>{{ trans('cruds.organization.title') }}</span>

                                </a>
                            </li>
                        @endcan
                        @can('team_access')
                            <li class="{{ request()->is('admin/teams') || request()->is('admin/teams/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.teams.index") }}">
                                    <i class="fa-fw fas fa-users">

                                    </i>
                                    <span>{{ trans('cruds.team.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('department_access')
                            <li class="{{ request()->is('admin/departments') || request()->is('admin/departments/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.departments.index") }}">
                                    <i class="fa-fw fas fa-chalkboard-teacher">

                                    </i>
                                    <span>{{ trans('cruds.department.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('jobtitle_access')
                            <li class="{{ request()->is('admin/jobtitles') || request()->is('admin/jobtitles/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.jobtitles.index") }}">
                                    <i class="fa-fw fas fa-address-card">

                                    </i>
                                    <span>{{ trans('cruds.jobtitle.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="{{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.roles.index") }}">
                                    <i class="fa-fw fas fa-briefcase">

                                    </i>
                                    <span>{{ trans('cruds.role.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('permission_access')
                            <li class="{{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.permissions.index") }}">
                                    <i class="fa-fw fas fa-unlock-alt">

                                    </i>
                                    <span>{{ trans('cruds.permission.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('audit_log_access')
                            <li class="{{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.audit-logs.index") }}">
                                    <i class="fa-fw fas fa-file-alt">

                                    </i>
                                    <span>{{ trans('cruds.auditLog.title') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('technical_document_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-cog">

                        </i>
                        <span>{{ trans('cruds.technicalDocument.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('construction_contract_access')
                            <li class="{{ request()->is('admin/construction-contracts') || request()->is('admin/construction-contracts/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.construction-contracts.index") }}">
                                    <i class="fa-fw fas fa-book-open">

                                    </i>
                                    <span>{{ trans('cruds.constructionContract.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('works_code_access')
                            <li class="{{ request()->is('admin/works-codes') || request()->is('admin/works-codes/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.works-codes.index") }}">
                                    <i class="fa-fw fas fa-cogs">

                                    </i>
                                    <span>{{ trans('cruds.worksCode.title') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('user_alert_access')
                <li class="{{ request()->is('admin/user-alerts') || request()->is('admin/user-alerts/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.user-alerts.index") }}">
                        <i class="fa-fw fas fa-bell">

                        </i>
                        <span>{{ trans('cruds.userAlert.title') }}</span>
                    </a>
                </li>
            @endcan
           
            <!-- @can('file_manager_access')
                <li class="{{ request()->is('admin/file-managers') || request()->is('admin/file-managers/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.file-managers.index") }}">
                        <i class="fa-fw fas fa-file-pdf">

                        </i>
                        <span>{{ trans('cruds.fileManager.title') }}</span>
                    </a>
                </li>
            @endcan -->

            @can('ticket_access')
                <li class="{{ request()->is("admin/tickets") || request()->is("admin/tickets/*") ? "active" : "" }}">
                    <a href="{{ route("admin.tickets.index") }}">
                        <i class="fa-fw fab fa-github-square">

                        </i>
                        <span>{{ trans('cruds.ticket.title') }}</span>

                    </a>
                </li>
            @endcan

            @can('complaint_access')
                <li class="{{ request()->is("admin/complaints") || request()->is("admin/complaints/*") ? "active" : "" }}">
                    <a href="{{ route("admin.complaints.index") }}">
                        <i class="fa-fw fas fa-list-alt">

                        </i>
                        <span>{{ trans('cruds.complaint.title') }}</span>

                    </a>
                </li>
            @endcan
                        
            <li class="{{ request()->is('admin/system-calendar') || request()->is('admin/system-calendar/*') ? 'active' : '' }}">
                <a href="{{ route("admin.systemCalendar") }}">
                    <i class="fas fa-fw fa-calendar">

                    </i>
                    <span>{{ trans('global.systemCalendar') }}</span>
                </a>
            </li>
            @php($unread = \App\QaTopic::unreadCount())
                <li class="{{ request()->is('admin/messenger') || request()->is('admin/messenger/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.messenger.index") }}">
                        <i class="fa-fw fa fa-envelope">

                        </i>
                        <span>{{ trans('global.messages') }}</span>
                        @if($unread > 0)
                            <strong>( {{ $unread }} )</strong>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <i class="fas fa-fw fa-sign-out-alt">

                        </i>
                        {{ trans('global.logout') }}
                    </a>
                </li>
        </ul>
    </section>
</aside>