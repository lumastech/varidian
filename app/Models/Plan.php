<?php

namespace App\Models;

use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'slug', 'audience', 'hestia_package', 'price_ngwee',
    'storage_gb', 'bandwidth_gb', 'websites', 'databases', 'cron_jobs', 'backups',
    'is_featured', 'is_active', 'sort_order',
])]
class Plan extends Model
{
    /** @use HasFactory<PlanFactory> */
    use HasFactory;

    /**
     * Scope the query to active plans in display order.
     *
     * @param  Builder<Plan>  $query
     */
    public function scopeForSale(Builder $query): void
    {
        $query->where('is_active', true)->orderBy('sort_order')->orderBy('price_ngwee');
    }

    /**
     * Get the monthly price in whole kwacha, as displayed on the site.
     */
    public function priceInKwacha(): string
    {
        $kwacha = $this->price_ngwee / 100;

        return $this->price_ngwee % 100 === 0 ? (string) intdiv($this->price_ngwee, 100) : number_format($kwacha, 2);
    }

    /**
     * Get the human-readable feature list for pricing cards.
     *
     * @return list<string>
     */
    public function features(): array
    {
        return [
            "{$this->storage_gb}GB storage",
            "{$this->bandwidth_gb}GB bandwidth",
            $this->countLabel($this->websites, 'website'),
            $this->countLabel($this->databases, 'database'),
            $this->countLabel($this->cron_jobs, 'cron job'),
            "{$this->backups} backups kept",
        ];
    }

    /**
     * Get the plan as passed to marketing pages.
     *
     * @return array{name: string, slug: string, audience: ?string, price: string, spec: string, features: list<string>, featured: bool}
     */
    public function toMarketingArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'audience' => $this->audience,
            'price' => $this->priceInKwacha(),
            'spec' => $this->countLabel($this->websites, 'website')." · {$this->storage_gb}GB storage",
            'features' => $this->features(),
            'featured' => $this->is_featured,
        ];
    }

    private function countLabel(int $count, string $noun): string
    {
        return "{$count} ".($count === 1 ? $noun : "{$noun}s");
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_ngwee' => 'integer',
            'storage_gb' => 'integer',
            'bandwidth_gb' => 'integer',
            'websites' => 'integer',
            'databases' => 'integer',
            'cron_jobs' => 'integer',
            'backups' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
