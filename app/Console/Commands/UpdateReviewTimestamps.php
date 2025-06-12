<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Review;
use App\Models\Testimonial;
use Illuminate\Console\Command;

class UpdateReviewTimestamps extends Command
{
    
    protected $signature = 'timestamps:update-random';
    protected $description = 'Update reviews and testimonials created on a specific date to a random timestamp';

    public function handle()
{
    $from = Carbon::parse('2022-01-31 23:00:00');
    $to = now();

    $this->info("Updating ALL Reviews...");
    Review::chunk(100, function ($reviews) use ($from, $to) {
        foreach ($reviews as $review) {
            $review->created_at = Carbon::createFromTimestamp(rand($from->timestamp, $to->timestamp));
            $review->save();
        }
    });

    $this->info("Updating ALL Testimonials...");
    Testimonial::chunk(100, function ($testimonials) use ($from, $to) {
        foreach ($testimonials as $testimonial) {
            $testimonial->created_at = Carbon::createFromTimestamp(rand($from->timestamp, $to->timestamp));
            $testimonial->save();
        }
    });

    $this->info("Timestamps for all reviews and testimonials updated successfully.");
}
}
