<?php

namespace App\Config;

class AppConstants
{
    // User Preferences
    public const DEFAULT_BUDGET_LIMIT = 100000;

    public const DEFAULT_QUALITY = 'normal';

    // Price Tracking Thresholds
    public const PRICE_ALERT_THRESHOLD_PERCENTAGE = 20;

    public const PRICE_TREND_UP_THRESHOLD = 5;

    public const PRICE_TREND_DOWN_THRESHOLD = -5;

    public const OPTIMAL_BUYING_THRESHOLD = -10;

    public const HIGH_VOLATILITY_THRESHOLD = 1000;

    // Price Simulation
    public const PRICE_VARIATION_MIN = -100;

    public const PRICE_VARIATION_MAX = 100;

    public const PRICE_VARIATION_DIVISOR = 1000;

    public const MINIMUM_PRICE_RATIO = 0.5;

    // Onboarding
    public const MIN_SELECTED_RECIPES = 3;

    public const MAX_SELECTED_RECIPES = 5;

    public const MIN_BUDGET = 10000;

    // Price Tracking Days
    public const MIN_TRACKING_DAYS = 7;

    public const MAX_TRACKING_DAYS = 365;

    public const DEFAULT_TREND_ANALYSIS_DAYS = 30;

    public const SHORT_TERM_ANALYSIS_DAYS = 7;
}
