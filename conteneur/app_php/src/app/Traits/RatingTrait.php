<?php

namespace App\Traits;

trait RatingTrait
{
    public function generateRatingHtml($rating): string
    {
        $roundedRating = (int) round($rating ?? 0);
        $finalRating = max(0, min(5, $roundedRating));

        if ($finalRating <= 0) {
            return '<span class="text-gray-300 text-[10px] italic cursor-help" title="Pas encore de note">Aucun avis</span>';
        }

        $html = '<div class="flex items-center text-base" title="Note : ' . $roundedRating . '/5">';
        for ($i = 1; $i <= 5; $i++) {
            $html .= ($i <= $finalRating)
                ? '<span class="text-orange-400">★</span>'
                : '<span class="text-gray-200">★</span>';
        }
        $html .= '</div>';

        return $html;
    }

    // Alias for generateRatingHtml to match Review entity usage
    public function generateStarHtml($rating): string
    {
        return $this->generateRatingHtml($rating);
    }
}
