<?php

namespace App\Traits;

use App\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::audit('created', $model);
        });

        static::updated(function (Model $model) {
            self::audit('updated', $model);
        });

        static::deleted(function (Model $model) {
            self::audit('deleted', $model);
        });
    }

    protected static function audit($description, $model)
    {
        $old = $description === 'created' ? [] : $model->getOriginal();
        $new = $description === 'deleted' ? [] : $model->getAttributes();
        $changes = $description === 'updated' ? $model->getChanges() : $new;

        AuditLog::create([
            'description'  => $description,
            'subject_id'   => $model->id ?? null,
            'subject_type' => get_class($model) ?? null,
            'user_id'      => auth()->id() ?? null,
            'properties'   => [
                'old'     => self::sanitizeAuditAttributes($old),
                'new'     => self::sanitizeAuditAttributes($new),
                'changes' => self::sanitizeAuditAttributes($changes),
            ],
            'host'         => request()->ip() ?? null,
        ]);
    }

    protected static function sanitizeAuditAttributes(array $attributes): array
    {
        $sanitized = [];

        foreach ($attributes as $key => $value) {
            if (preg_match('/password|token|secret|api[_-]?key|private[_-]?key/i', (string) $key) === 1) {
                continue;
            }

            if (is_array($value)) {
                $value = self::sanitizeAuditAttributes($value);
            }

            $sanitized[$key] = $value;
        }

        return $sanitized;
    }
}
