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
            @can('request_for_approval_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-file-signature">

                        </i>
                        <span>{{ trans('cruds.requestForApproval.title') }}</span>
                        <span class="pull-right-container"><i class="fa fa-fw fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        @can('rfa_access')
                            <li class="{{ request()->is('admin/rfas') || request()->is('admin/rfas/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.rfas.index") }}">
                                    <i class="fa-fw fas fa-book">

                                    </i>
                                    <span>{{ trans('cruds.rfa.title') }}</span>
                                </a>
                            </li>
                        @endcan
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
            @can('task_management_access')
                <li class="treeview">
                    <a href="#">
                        <i class="fa-fw fas fa-list">

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
                        @can('task_access')
                            <li class="{{ request()->is('admin/tasks') || request()->is('admin/tasks/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.tasks.index") }}">
                                    <i class="fa-fw fas fa-briefcase">

                                    </i>
                                    <span>{{ trans('cruds.task.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('tasks_calendar_access')
                            <li class="{{ request()->is('admin/tasks-calendars') || request()->is('admin/tasks-calendars/*') ? 'active' : '' }}">
                                <a href="{{ route("admin.tasks-calendars.index") }}">
                                    <i class="fa-fw fas fa-calendar">

                                    </i>
                                    <span>{{ trans('cruds.tasksCalendar.title') }}</span>
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
            @can('file_manager_access')
                <li class="{{ request()->is('admin/file-managers') || request()->is('admin/file-managers/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.file-managers.index") }}">
                        <i class="fa-fw fas fa-file-pdf">

                        </i>
                        <span>{{ trans('cruds.fileManager.title') }}</span>
                    </a>
                </li>
            @endcan
            @can('construction_contract_access')
                <li class="{{ request()->is('admin/construction-contracts') || request()->is('admin/construction-contracts/*') ? 'active' : '' }}">
                    <a href="{{ route("admin.construction-contracts.index") }}">
                        <i class="fa-fw fas fa-book-open">

                        </i>
                        <span>{{ trans('cruds.constructionContract.title') }}</span>
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