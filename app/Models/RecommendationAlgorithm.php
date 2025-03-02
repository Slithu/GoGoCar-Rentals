<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationAlgorithm extends Model
{
    use HasFactory;

    protected $fillable = [
        'algorithm_name',
        'is_active',
        'activated_at',
        'deactivated_at',
        'activation_count',
        'reservations_count',
        'knn_k',
        'mlp_hidden_layer_1',
        'mlp_hidden_layer_2',
        'mlp_iterations',
        'decision_tree_depth',
    ];

    public $timestamps = true;

    // Metoda do pobrania aktywnego algorytmu lub domyślnego
    public static function getActiveAlgorithm()
    {
        return self::where('is_active', true)->first();
    }

    // Tworzenie domyślnego algorytmu (naive_bayes)
    public static function createDefaultAlgorithm(): RecommendationAlgorithm
    {
        return self::create([
            'algorithm_name' => 'naive_bayes',
            'activated_at' => now(),
        ]);
    }

    public function activate()
    {
        $this->is_active = true;
        $this->activated_at = now();
        $this->save();
    }

    public function deactivate()
    {
        $this->is_active = false;
        $this->deactivated_at = now();
        $this->save();
    }
}
