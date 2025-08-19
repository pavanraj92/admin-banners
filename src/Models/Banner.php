<?php

namespace admin\banners\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;

class Banner extends Model
{
    use HasFactory, Sortable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'sub_title',
        'description',
        'button_title',
        'button_url',
        'sort_order',
        'image',
        'status'
    ];

     /**
     * The attributes that should be sortable.
     */
    public $sortable = [
        'title',
        'sub_title',
        'button_title',
        'status',
        'created_at',
    ];

    public function scopeFilter($query, $keyword)
    {
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('sub_title', 'like', '%' . $keyword . '%')
                  ->orWhere('button_title', 'like', '%' . $keyword . '%');
            });
        }
        return $query;
    }
        /**
     * filter by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
}

