<?php

namespace Experience\Models;

use Carbon\Carbon;
use Category\Support\Traits\BelongsToCategory;
use DateTime;
use Experience\Models\Availability;
use Experience\Support\Traits\BelongsToManyAmenities;
use Experience\Support\Traits\MorphToManyRating;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Pluma\Models\Model;
use Travel\Models\User;
use Travel\Models\Wishlist;
use User\Support\Traits\BelongsToUser;

class Experience extends Model
{
    use SoftDeletes, BelongsToCategory, BelongsToUser, BelongsToManyAmenities, MorphToManyRating;

    protected $with = ['category'];

    protected $appends = ['wishlisted', 'amount', 'rate', 'date', 'categoryname', 'url', 'manager', 'created', 'modified'];

    protected $searchables = ['name', 'feature', 'code', 'reference_number', 'category_id', 'type', 'body', 'created_at', 'updated_at'];

    public function availabilities()
    {
        return $this->morphMany(Availability::class, 'available');
    }

    public function getUrlAttribute()
    {
        return route('experiences.show', $this->code);
    }

    public function getAmountAttribute()
    {
        // setlocale(LC_MONETARY, settings('locale', 'en_US'));
        return settings('site_currency.symbol', '₱') . " " . number_format($this->price, 2);
    }

    public function getDateAttribute()
    {
        $date = $this->availabilities->first();

        if (date('m-d-Y', strtotime($date->date_start)) == date('m-d-Y', strtotime($date->date_end))) {
            return date('m-d-Y', strtotime($date->date_start));
        }

        $m = date('m-Y', strtotime($date->date_start)) == date('m-Y', strtotime($date->date_end))
            ? date('M d', strtotime($date->date_start)) . " - " . date('d, Y', strtotime($date->date_end))
            : date('M d, Y', strtotime($date->date_start)) . " - " . date('M d, Y', strtotime($date->date_end));

        return $m;
    }

    public function getTimeAttribute()
    {
        $date = $this->availabilities->first();

        return date('h:ia', strtotime($date->date_start));
    }

    public function getDayAttribute()
    {
        $date = $this->availabilities->first();
        return date('l', strtotime($date->date_start));
    }

    public function getDaysAttribute()
    {
        $date = $this->availabilities->first();
        $date2 = $this->availabilities->last();
        $start = Carbon::parse($date->date_start);
        $end = Carbon::parse($date2->date_end);
        $total = $end->diffInDays($start);
        return "$total " . ($total == 1 ? "day" : "days");
    }

    public function getCategorynameAttribute()
    {
        return $this->category ? $this->category->name : false;
    }

    public function getManagerpicAttribute()
    {
        return isset($this->user) ? $this->user->thumbnail : '';
    }

    public function getStartDateAttribute()
    {
        $date = $this->availabilities->first();
        return date('M d, Y', strtotime($date->date_start));
    }

    public function getCurrencyAttribute()
    {
        return settings('site_currency.code', 'PHP');
    }

    public function getRefnumAttribute()
    {
        return isset($this->reference_number) ? $this->reference_number : date('Ymd', strtotime($this->created_at)).user()->id.'00'.date('His');
    }

    public function getManagerAttribute()
    {
        return $this->user;
    }

    //
    public function experience()
    {
        return $this->morphMany('Review', 'reviewable');
    }

    public function reviews()
    {
        return $this->morphMany(\Review\Models\Review::class, 'reviewable');
    }

    public function getRateAttribute()
    {
        return round($this->rating, 2);
    }

    public function getAvailabilitiesListAttribute()
    {
        $availables = $this->availabilities()->select(['*', DB::raw('MONTH (date_start) as month')])->orderBy('month')->get();

        $m = [];
        foreach ($availables as $i => $available) {
            $m[date('F Y', strtotime($available->date_start))][] = $available;
        }

        return $m;
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getWishlistedAttribute()
    {
        if (! user()) {
            return false;
        }

        $wishlists = User::find(user()->id)->wishlists ?? [];
        foreach ($wishlists as $wishlist) {
            if ($wishlist->experience_id === $this->id) {
                return true;
            }
        }

        return false;
    }

    public function haveReviewedBy($user)
    {
        return $this->whereHas('reviews', function ($query) use ($user) {
            return $query->where('user_id', $user->id);
        })->count();
    }
}
