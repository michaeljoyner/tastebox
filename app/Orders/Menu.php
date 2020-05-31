<?php

namespace App\Orders;

use App\DatePresenter;
use App\Meals\Meal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Menu extends Model
{
    const CURRENT = 'current';
    const UPCOMING = 'upcoming';
    const ARCHIVED = 'archived';

    protected $fillable = ['current_from', 'current_to', 'delivery_from'];
    protected $dates = ['current_from', 'current_to', 'delivery_from'];

    protected $casts = ['can_order' => 'boolean'];

    public function scopeUpcoming($query)
    {
        return $query->where('current_from', '>=', Carbon::now()->startOfWeek());
    }

    public static function generateWeekly($number_of_weeks)
    {
        $latest = Menu::latest('current_from')->first();
        $start = $latest->current_from ?? Carbon::today();

        collect(range(1, $number_of_weeks))
            ->each(function ($week) use ($start) {
                $from = Carbon::parse($start)->addWeeks($week)->startOfWeek();
                $to = Carbon::parse($start)->addWeeks($week)->startOfWeek()->addDays(5);
                $delivery = Carbon::parse($start)->addWeeks($week)->endOfWeek()->addDays(1);

                Menu::create([
                    'current_from'  => $from,
                    'current_to'    => $to,
                    'delivery_from' => $delivery
                ]);
            });
    }

    public function weekOfYear()
    {
        return $this->current_from->week;
    }

    public function isCurrent()
    {
        return Carbon::now()->isBetween($this->current_from, $this->current_to);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class);
    }

    public function setMeals(array $meal_ids)
    {
        $this->meals()->sync($meal_ids);
    }

    public function toArray()
    {
        return [
            'id'                   => $this->id,
            'can_order'            => $this->can_order,
            'current_from_date'    => $this->current_from->format('Y-m-d'),
            'current_from_pretty'  => $this->current_from->format('jS M, Y'),
            'current_to_date'      => $this->current_to->format('Y-m-d'),
            'current_to_pretty'    => $this->current_to->format('jS M, Y'),
            'current_range_pretty' => DatePresenter::range($this->current_from, $this->current_to),
            'delivery_from_date'   => $this->delivery_from->format('Y-m-d'),
            'delivery_from_pretty' => $this->delivery_from->format('jS M, Y'),
            'week_number'          => $this->current_from->week,
            'is_current'           => $this->isCurrent(),
            'status'               => Menu::UPCOMING,
            'meals'                => $this->meals->map->toArray()->all(),
        ];
    }
}
