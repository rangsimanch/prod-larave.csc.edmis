<?php

namespace Tests\Feature;

use App\AuditLog;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuditLogSubject extends Model
{
    use Auditable;

    public $timestamps = false;

    protected $table = 'audit_subjects';

    protected $fillable = ['title', 'status'];
}

class AuditLogsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->unsignedInteger('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->text('properties')->nullable();
            $table->string('host', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('audit_subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('status');
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('audit_subjects');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_ajax_index_returns_a_maximum_of_20_rows_per_page(): void
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Alice Admin', 'email' => 'alice@example.com'],
        ]);
        $this->insertAuditLogs(25, 1);

        $response = (new AuditLogsController())->index($this->dataTableRequest());
        $data = $response->getData(true);

        $this->assertCount(20, $data['data']);
        $this->assertSame(25, $data['recordsTotal']);
        $this->assertSame(25, $data['recordsFiltered']);
    }

    public function test_ajax_index_filters_by_description_column(): void
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Alice Admin', 'email' => 'alice@example.com'],
        ]);
        $this->insertAuditLogs(2, 1);
        DB::table('audit_logs')->where('subject_id', 2)->update(['description' => 'deleted']);

        $response = (new AuditLogsController())->index($this->dataTableRequest([
            2 => 'deleted',
        ]));
        $data = $response->getData(true);

        $this->assertSame(1, $data['recordsFiltered']);
        $this->assertSame('deleted', $data['data'][0]['description']);
    }

    public function test_ajax_index_filters_by_created_date_range(): void
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Alice Admin', 'email' => 'alice@example.com'],
        ]);
        $this->insertAuditLogs(2, 1, 1, '2026-08-01 12:00:00');
        $this->insertAuditLogs(1, 1, 3, '2026-08-20 12:00:00');

        $request = $this->dataTableRequest();
        $request->merge([
            'created_from' => '2026-08-15',
            'created_to' => '2026-08-25',
        ]);

        $response = (new AuditLogsController())->index($request);
        $data = $response->getData(true);

        $this->assertSame(1, $data['recordsFiltered']);
        $this->assertSame('3', $data['data'][0]['subject_id']);
    }

    public function test_audit_log_user_relationship_exposes_name_and_email(): void
    {
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Alice Admin', 'email' => 'alice@example.com'],
        ]);
        $this->insertAuditLogs(1, 1);

        $user = \App\AuditLog::first()->user;

        $this->assertSame('Alice Admin', $user->name);
        $this->assertSame('alice@example.com', $user->email);
    }

    public function test_auditable_logs_before_and_after_values_for_updates(): void
    {
        $subject = AuditLogSubject::create(['title' => 'Before', 'status' => 'draft']);
        $subject->update(['title' => 'After']);

        $properties = AuditLog::where('subject_type', AuditLogSubject::class)->latest('id')->first()->properties->toArray();

        $this->assertSame('Before', $properties['old']['title']);
        $this->assertSame('After', $properties['new']['title']);
        $this->assertSame('After', $properties['changes']['title']);
    }

    public function test_auditable_logs_before_values_for_deletes(): void
    {
        $subject = AuditLogSubject::create(['title' => 'Removed', 'status' => 'done']);
        $subject->delete();

        $properties = AuditLog::where('subject_type', AuditLogSubject::class)->latest('id')->first()->properties->toArray();

        $this->assertSame('Removed', $properties['old']['title']);
        $this->assertSame([], $properties['new']);
        $this->assertSame([], $properties['changes']);
    }

    public function test_show_reconstructs_legacy_update_before_values(): void
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        DB::table('audit_logs')->insert([
            'description' => 'created',
            'subject_id' => 7,
            'subject_type' => 'App\\Document',
            'properties' => json_encode(['title' => 'Before', 'status' => 'draft']),
            'created_at' => '2026-08-29 09:00:00',
            'updated_at' => '2026-08-29 09:00:00',
        ]);
        DB::table('audit_logs')->insert([
            'description' => 'updated',
            'subject_id' => 7,
            'subject_type' => 'App\\Document',
            'properties' => json_encode(['title' => 'After', 'status' => 'done']),
            'created_at' => '2026-08-29 10:00:00',
            'updated_at' => '2026-08-29 10:00:00',
        ]);

        $view = (new AuditLogsController())->show(AuditLog::latest('id')->first());
        $data = $view->getData();

        $this->assertSame([
            ['field' => 'title', 'old' => 'Before', 'new' => 'After'],
            ['field' => 'status', 'old' => 'draft', 'new' => 'done'],
        ], $data['propertyChanges']);
    }

    public function test_show_uses_legacy_delete_properties_as_before_values(): void
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        DB::table('audit_logs')->insert([
            'description' => 'deleted',
            'subject_id' => 8,
            'subject_type' => 'App\\Document',
            'properties' => json_encode(['title' => 'Removed', 'status' => 'done']),
            'created_at' => '2026-08-29 10:00:00',
            'updated_at' => '2026-08-29 10:00:00',
        ]);

        $view = (new AuditLogsController())->show(AuditLog::latest('id')->first());
        $data = $view->getData();

        $this->assertSame([
            ['field' => 'title', 'old' => 'Removed', 'new' => null],
            ['field' => 'status', 'old' => 'done', 'new' => null],
        ], $data['propertyChanges']);
    }

    public function test_show_prepares_detailed_audit_change_data(): void
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Alice Admin', 'email' => 'alice@example.com'],
        ]);
        DB::table('audit_logs')->insert([
            'description' => 'updated',
            'subject_id' => 7,
            'subject_type' => 'App\\Document',
            'user_id' => 1,
            'properties' => json_encode([
                'old' => ['title' => 'Before', 'status' => 'draft', 'password' => 'secret'],
                'new' => ['title' => 'After', 'status' => 'done', 'password' => 'hash'],
                'changes' => ['title' => 'After', 'status' => 'done', 'password' => 'hash'],
            ]),
            'host' => '192.168.1.10',
            'created_at' => '2026-08-29 10:00:00',
            'updated_at' => '2026-08-29 10:00:00',
        ]);

        $view = (new AuditLogsController())->show(\App\AuditLog::first());
        $data = $view->getData();

        $this->assertSame('Document', $data['modelName']);
        $this->assertSame('Alice Admin', $data['auditLog']->user->name);
        $this->assertSame([
            ['field' => 'title', 'old' => 'Before', 'new' => 'After'],
            ['field' => 'status', 'old' => 'draft', 'new' => 'done'],
        ], $data['propertyChanges']);
    }

    public function test_ajax_index_filters_by_user_email(): void
    {
        Gate::shouldReceive('denies')->andReturnFalse();

        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Alice Admin', 'email' => 'alice@example.com'],
            ['id' => 2, 'name' => 'Bob Reviewer', 'email' => 'bob@example.com'],
        ]);
        $this->insertAuditLogs(3, 1);
        $this->insertAuditLogs(2, 2, 4);

        $response = (new AuditLogsController())->index($this->dataTableRequest([
            7 => 'bob@example.com',
        ]));
        $data = $response->getData(true);

        $this->assertSame(2, $data['recordsFiltered']);
        $this->assertCount(2, $data['data']);
        $this->assertSame('bob@example.com', $data['data'][0]['user_email']);
    }

    protected function insertAuditLogs(int $count, int $userId, int $start = 1, ?string $createdAt = null): void
    {
        $logs = [];
        for ($index = $start; $index < $start + $count; $index++) {
            $logs[] = [
                'description' => 'updated',
                'subject_id' => $index,
                'subject_type' => 'App\\Document',
                'user_id' => $userId,
                'properties' => '{}',
                'host' => '127.0.0.1',
                'created_at' => $createdAt ?: now(),
                'updated_at' => $createdAt ?: now(),
            ];
        }

        DB::table('audit_logs')->insert($logs);
    }

    protected function dataTableRequest(array $filters = []): Request
    {
        $columns = [
            'placeholder', 'id', 'description', 'subject_id', 'subject_type',
            'user_id', 'user_name', 'user_email', 'host', 'created_at', 'actions',
        ];

        $request = Request::create('/admin/audit-logs', 'GET', [
            'draw' => 1,
            'start' => 0,
            'length' => 20,
            'order' => [['column' => 1, 'dir' => 'desc']],
            'columns' => array_map(function ($column, $index) use ($filters) {
                return [
                    'data' => $column,
                    'name' => in_array($column, ['placeholder', 'actions', 'user_name', 'user_email']) ? $column : 'audit_logs.' . $column,
                    'searchable' => $column !== 'placeholder' && $column !== 'actions' ? 'true' : 'false',
                    'orderable' => $column !== 'placeholder' && $column !== 'actions' ? 'true' : 'false',
                    'search' => [
                        'value' => $filters[$index] ?? '',
                        'regex' => 'false',
                    ],
                ];
            }, $columns, array_keys($columns)),
        ], [], [], ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        $this->app->instance('request', $request);

        return $request;
    }
}
