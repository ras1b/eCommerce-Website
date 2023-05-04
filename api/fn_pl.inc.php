<?php

// Calculate the average rating from the user reviews
function getAverageRating($user_reviews)
{
    $total_rating = 0;
    $num_reviews = count($user_reviews);
    if ($num_reviews > 0) {
        foreach ($user_reviews as $review) {
            $total_rating += $review["rating"];
        }
        $average_rating = $total_rating / $num_reviews;
        return round($average_rating, 1);
    } else {
        return 0;
    }
}

