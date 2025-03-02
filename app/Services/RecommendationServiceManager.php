<?php

namespace App\Services;

use App\Services\RecommendationService;
use App\Services\RecommendationService2;
use App\Services\RecommendationService3;
use App\Services\RecommendationService4;
use App\Models\RecommendationAlgorithm;

class RecommendationServiceManager
{
    public function getService()
    {
        // Pobierz aktywny algorytm z bazy danych
        $activeAlgorithm = RecommendationAlgorithm::where('is_active', true)->first();

        if (!$activeAlgorithm) {
            // Jeśli brak aktywnego algorytmu, domyślnie wybierz Naive Bayes
            return new RecommendationService();
        }

        // Na podstawie aktywnego algorytmu wybieramy odpowiedni serwis
        switch ($activeAlgorithm->algorithm_name) {
            case 'knn':
                return new RecommendationService2(); // Klasa z algorytmem KNN
            case 'mlp':
                return new RecommendationService3(); // Klasa z algorytmem MLP
            case 'decision_tree':
                return new RecommendationService4(); // Klasa z algorytmem Drzewa Decyzyjnego
            case 'naive_bayes':
            default:
                return new RecommendationService(); // Domyślnie Naive Bayes
        }
    }
}
